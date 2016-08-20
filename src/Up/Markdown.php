<?php
/**
 * $Id$
 *
 * Up! - An extremely simple, yet powerful markdown-based CMS
 *
 * Copyright (c) 2015 Innovacy, Dimitrios Karvounaris
 *
 * @version 1.0.1
 * @copyright 2015 Innovacy - Dimitrios Karvounaris
 * @author Dimitrios Karvounaris, <d.karvounaris@innovacy.com>
 * @license See LICENSE file.
 *
 * --
 *
 * NOTICE OF LICENSE
 *
 * Up! is licensed under the terms of the GNU AGPLv3 with additional terms and
 * linking exceptions. The AGPLv3 license text can be found in the AGPLv3.txt file,
 * the additional terms can be found in the LICENSE file.
 *
 */

namespace Innovacy\Up;

use Innovacy\Up\Gimmick\GimmickBase;

/**
 * Class Markdown
 * Parses a document and converts it into HTML
 * @package Innovacy\Up
 */
class Markdown extends \cebe\markdown\GithubMarkdown
{
    private $tableCellTag = 'td';
    private $tableCellCount = 0;
    private $tableCellAlign = [];
    /** @var bool Assists in finding the first 1st level headline and ignore the others */
    private $isFirstBlock = true;
    public $useSideNav = false;
    private $sideNav = '';
    public $anchorCharacter = '&para;';

    /** @var string First 1st level headline found in document */
    public $title;

    /** @var array List of gimmick classes */
    protected $gimmicks = array();

    /**
     * Markdown constructor.
     */
    public function __construct()
    {
        $gimmickPath = __DIR__ . '/Gimmick';

        if ($d = opendir($gimmickPath)) {
            while (($filename = readdir($d)) !== false) {
                if (preg_match('#^(.+)\.php$#', $filename, $match)) {
                    if ($match[1] == 'GimmickBase') {
                        continue;
                    }
                    $class = __NAMESPACE__ . '\\Gimmick\\' . $match[1];
                    //require_once $gimmickPath.'/'.$match[1];
                    //if (!class_exists($class, false)) {
                    //    continue;
                    //}
                    if (!class_exists($class, true)) {
                        continue;
                    }
                    /** @var $gimmick GimmickBase */
                    $gimmick = new $class;
                    if (!is_subclass_of($gimmick, '\\Innovacy\\Up\\Gimmick\\GimmickBase')) {
                        continue;
                    }
                    $gimmick->parser = $this;
                    $reflector = new \ReflectionClass($gimmick);
                    if ($gimmick->isLinkGimmick && $reflector->hasMethod('renderLink')) {
                        if (!empty($gimmick->gimmickKeyword)) {
                            $this->gimmicks['link']['explicit'][$gimmick->gimmickKeyword] = $gimmick;
                        } else {
                            $this->gimmicks['link']['implicit'][] = $gimmick;
                        }
                    } elseif ($gimmick->isParagraphGimmick && $reflector->hasMethod('renderParagraph')) {
                        $this->gimmicks['paragraph']['implicit'][] = $gimmick;
                    }
                }
            }
            closedir($d);
        }
    }

    /**
     * @param $block
     * @return string
     */
    protected function renderTable($block)
    {
        $content = '';
        $this->tableCellAlign = $block['cols'];
        $content .= "<thead>\n";
        $first = true;
        foreach ($block['rows'] as $row) {
            $this->tableCellTag = $first ? 'th' : 'td';
            $align = empty($this->tableCellAlign[$this->tableCellCount])
                ? '' : ' align="' . $this->tableCellAlign[$this->tableCellCount] . '"';
            $this->tableCellCount++;
            $tds = "<$this->tableCellTag$align>" .
                trim($this->renderAbsy($this->parseInline($row)))
                . "</$this->tableCellTag>"; // TODO move this to the consume step
            $content .= "<tr>$tds</tr>\n";
            if ($first) {
                $content .= "</thead>\n<tbody>\n";
            }
            $first = false;
            $this->tableCellCount = 0;
        }
        return '<table class="table table-bordered">' . $content . '</tbody>\n</table>' . "\n";
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
        return (!empty($alertClass) ? '<div class="alert alert-' . $alertClass . '"><p class="md-text">' : '<p>') .
        $this->renderAbsy($block['content']) .
        (!empty($alertClass) ? '</p></div>' : '</p>') . "\n";
    }

    /**
     * Handles links and gimmicks
     * @param $block
     * @return string
     */
    protected function renderLink($block)
    {
        $parseText = true;
        if (preg_match('#^\[gimmick:(.+)\]\(.*\)$', $block['orig'], $m)) {
            if (array_key_exists($this->gimmicks['link']['explicit'], $m[1])) {
                /** @var Gimmick\GimmickBase $gimmick */
                $gimmick = $this->gimmicks['link']['explicit'][$m[1]];
                $return = $gimmick->renderLink($block);
                if ($return === false) {
                    return '';
                } else if ($return !== true) {
                    return $return;
                }
            }
            if (!isset($block['url']) || strpos($block['url'], '://') === false) {
                // output nothing if gimmick has no regular link
                return '';
            } else {
                // replace 'gimmick:' specification with url to output and no further parsing
                $block['text'] = $block['url'];
                $parseText = false;
            }
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
        . '>' . ($parseText ? $this->renderAbsy($block['text']) : $block['text']) . '</a>';
    }

    /**
     * @param $block
     * @return string
     */
    protected function renderHeadline($block)
    {
        $_r = $this->renderAbsy($block['content']);
        $_rs = str_replace(' ', '_', strip_tags($_r));
        if ($this->useSideNav && $block['level'] == 2) {
            $this->sideNav .= '<li class="list-group-item"><a href="#' .
                $_rs . '">' . strip_tags($_r) . '</a></li>';
        }
        $tag = 'h' . $block['level'];
        return '<' . $tag . ' id="' . $_rs . '"' .
        ((!empty($this->anchorCharacter))
            ? ' class="md-inpage-anchor">' . $_r . '<span class="anchor-highlight"><a
                href="#' . $_rs . '">' . $this->anchorCharacter . '</a></span>'
            : '>' . $_r) .
        '</' . $tag . '>' . "\n";
    }

    /**
     * @param $blocks
     * @return string
     */
    public function renderAbsy($blocks)
    {
        $output = '';
        if ($this->isFirstBlock && $blocks[0][0] == 'headline' && $blocks[0]['level'] == 1) {
            $this->isFirstBlock = false;
            $b = array_shift($blocks);
            $this->title = $this->renderAbsy($b['content']);
        }
        foreach ($blocks as $block) {
            array_unshift($this->context, $block[0]);
            $output .= $this->{'render' . $block[0]}($block);
            array_shift($this->context);
        }
        return $output;
    }

    /**
     * @param string $text
     * @return string
     */
    public function parse($text)
    {
        $head = '';
        $markup = parent::parse($text);
        if (!empty($this->title)) {
            $head = '
                <div class="container" id="md-title-container">
                    <div class="row" id="md-title-row">
                        <div id="md-title" class="col-md-12">
                            ' . '<div class="page-header"><h1>' . $this->title . '</h1></div>' . '
                        </div>
                    </div>
                </div>
            ';
        }
        $markup = $head . '
                    <div class="container" id="md-menu-container">
                        <div class="row" id="md-menu-row"></div>
                    </div>

                    <div class="container" id="md-content-container">
                        <div class="row" id="md-content-row">
                        ' . ($this->useSideNav && !empty($this->sideNav)
                ? '
                            <div class="col-md-3" id="md-left-column">
                                <div class="panel panel-default">
                                    <ul class="list-group">
                                    ' . $this->sideNav . '
                                    </ul>
                                </div>
                            </div>
                            <div id="md-content" class="col-md-9">'
                : '
                            <div id="md-content" class="col-md-12">'
            ) .
            $markup . '
                            </div>
                        </div>
                    </div>

        ';
        return $markup;
    }
}
