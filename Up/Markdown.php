<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 07.10.2015
 * Time: 11:04
 */

namespace Innovacy\Up;

use cebe\markdown\GithubMarkdown;

class Markdown extends GithubMarkdown
{
    private $tableCellTag = 'td';
    private $tableCellCount = 0;
    private $tableCellAlign = [];
    private $firstHeadline = true;

    protected function renderTable($block)
    {
        $content = '';
        $this->tableCellAlign = $block['cols'];
        $content .= "<thead>\n";
        $first = true;
        foreach ($block['rows'] as $row) {
            $this->tableCellTag = $first ? 'th' : 'td';
            $align = empty($this->tableCellAlign[$this->tableCellCount]) ? '' : ' align="' . $this->tableCellAlign[$this->tableCellCount] . '"';
            $this->tableCellCount++;
            $tds = "<$this->tableCellTag$align>" . trim($this->renderAbsy($this->parseInline($row))) . "</$this->tableCellTag>"; // TODO move this to the consume step
            $content .= "<tr>$tds</tr>\n";
            if ($first) {
                $content .= "</thead>\n<tbody>\n";
            }
            $first = false;
            $this->tableCellCount = 0;
        }
        return "<table class=\"table table-bordered\">\n$content</tbody>\n</table>\n";
    }

    /**
     * @marker |
     */
    protected function parseTd($markdown)
    {
        if (isset($this->context[1]) && $this->context[1] === 'table') {
            $align = empty($this->tableCellAlign[$this->tableCellCount])
                ? ''
                : ' align="' . $this->tableCellAlign[$this->tableCellCount] . '"';
            $this->tableCellCount++;
            return [['text', "</$this->tableCellTag><$this->tableCellTag$align>"],
                isset($markdown[1]) && $markdown[1] === ' ' ? 2 : 1];
        }
        return [['text', $markdown[0]], 1];
    }

    /**
     * Handles alert triggers in paragraph
     * @param $block
     * @return string
     */
    protected function renderParagraph($block)
    {
        $alertClass = '';
        // concatenates from an array of arrays of 2-column arrays (index 0 is type and index 1 is content)
        // all content that is of type text
        $text = join('', array_map(
            function ($arr) {
                return $arr[1];
            },
            array_filter($block['content'], function ($var) {
                return ($var[0] == 'text');
            })
        ));
        if (preg_match('/^(warning|achtung|attention|warnung|atención|guarda|advertimiento)[\:\!]\s/i', $text)) {
            $alertClass = 'warning';
        } elseif (preg_match('/^(note|beachte)[\:\!]\s/i', $text)) {
            $alertClass = 'info';
        } elseif (preg_match('/^(hint|tip|tipp|hinweis)[\:\!]\s/i', $text)) {
            $alertClass = 'success';
        }
        return (!empty($alertClass) ? '<div class="alert alert-'.$alertClass.'"><p class="md-text">' : '<p>') .
        $this->renderAbsy($block['content']) .
        (!empty($alertClass) ? '</p></div>' : '</p>') . "\n";
    }

    /**
     * Handles gimmick links by currently removing them to avoid text like "gimmick:something" with invalid links
     * @param $block
     * @return string
     */
    protected function renderLink($block)
    {
        if (strpos($block['orig'], '[gimmick:') !== false) {
            return '';
        }
        if (isset($block['refkey'])) {
            if (($ref = $this->lookupReference($block['refkey'])) !== false) {
                $block = array_merge($block, $ref);
            } else {
                return $block['orig'];
            }
        }
        if (isset($block['url']) && strpos($block['url'], '://') === false) {
            $block['url'] = preg_replace('/\.md$/', '.html', $block['url']);
        }
        return '<a href="' . htmlspecialchars($block['url'], ENT_COMPAT | ENT_HTML401, 'UTF-8') . '"'
        . (empty($block['title'])
            ? ''
            : ' title="' . htmlspecialchars($block['title'], ENT_COMPAT | ENT_HTML401 | ENT_SUBSTITUTE, 'UTF-8') . '"')
        . '>' . $this->renderAbsy($block['text']) . '</a>';
    }

    /**
     * Renders the first headline (only if it is a <h1>) as page header
     * @param $block
     * @return string
     */
    protected function renderHeadline($block)
    {
        $output = '';
        if ($block['level'] == 1 && $this->firstHeadline) {
            $output = '<div class="page-header"><h1>'.$this->renderAbsy($block['content']).'</h1></div>';
        } else {
            $tag = 'h' . $block['level'];
            $output = "<$tag>" . $this->renderAbsy($block['content']) . "</$tag>\n";
        }
        $this->firstHeadline = false;
        return $output;
    }
}
