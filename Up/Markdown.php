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

}
