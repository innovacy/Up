<?php
/**
 * $Id$
 *
 * Up! - An extremely simple, yet powerful markdown-based CMS
 *
 * Copyright (c) 2015 Innovacy, Dimitrios Karvounaris
 *
 * @version 0.9.0
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

use cebe\markdown\Markdown;

/**
 * Renders the navigation.md file
 * @package Innovacy\Up
 */
class Navigation extends Markdown
{
    private $firstLink = true;
    private $withinDropdown = false;

    /** @var string Relative path for links */
    private $baseUri = '';

    /**
     * Renders Headlines as header of the navbar or as dropdown header
     * @param $block
     * @return string
     */
    protected function renderHeadline($block)
    {
        if ($block['level'] == 1 && !$this->withinDropdown) {
            return '<div class="navbar-header">'.
                '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">'
                .'<span class="sr-only">Toggle navigation</span>'
                .'<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>'
                .'</button>'
                .'<a class="navbar-brand" href="#">'.$this->renderAbsy($block['content']).'</a>'
                .'</div>';
        } elseif ($block['level'] == 1 && $this->withinDropdown) {
            return '<li class="dropdown-header">'.$this->renderAbsy($block['content']).'</li>';
        } else {
            $tag = 'h' . $block['level'];
            return "<$tag>" . $this->renderAbsy($block['content']) . "</$tag>\n";
        }
    }

    /**
     * Renders dividers as list item
     * @param $block
     * @return string
     */
    protected function renderHr($block)
    {
        return '<li class="divider"></li>';
    }

    /**
     * Creates links as part of a list, replaces '.md' to '.html' and handles the beginning of dropdowns
     * @param $block
     * @return string
     */
    protected function renderLink($block)
    {
        $pre = '';
        if ($this->firstLink) {
            $this->firstLink = false;
            $pre = '<ul class="nav navbar-nav">';
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
        $li_start = empty($block['url']) ? '<li class="dropdown">' : '<li>';
        $li_end = '';
        $this->withinDropdown = (bool)empty($block['url']) || $this->withinDropdown;
        // avoid duplicate <li>'s within dropdowns as they are rendered as list already
        if ($this->withinDropdown && !empty($block['url'])) {
            $li_start = '';
            $li_end = '';
        }

        return $pre . $li_start . '<a href="'
        . $this->baseUri
        . (empty($block['url']) ? '#' : htmlspecialchars($block['url'], ENT_COMPAT | ENT_HTML401, 'UTF-8'))
        . '"'
        . (empty($block['title'])
            ? ''
            : ' title="' . htmlspecialchars($block['title'], ENT_COMPAT | ENT_HTML401 | ENT_SUBSTITUTE, 'UTF-8') . '"')
        . (empty($block['url'])
            ? '  data-toggle="dropdown" class="dropdown-toggle"'
            : ''
        )
        . '>' . $this->renderAbsy($block['text'])
        . (empty($block['url']) ? '<b class="caret"></b>' : '')
        . '</a>'
        . (($this->withinDropdown && empty($block['url'])) ? '' : $li_end) . "\n";
    }

    /**
     * Is responsible to ignore paragraphs in navigation
     * @param $block
     * @return string
     */
    protected function renderParagraph($block)
    {
        return $this->renderAbsy($block['content']) . "\n";
    }

    /**
     * Is responsible to handle the dropdown submenus and the end of the dropdown
     * @param $block
     * @return string
     */
    protected function renderList($block)
    {
        $type = $block['list'];
        $output = '<' . $type . ' class="dropdown-menu">' . "\n";
        foreach ($block['items'] as $item => $itemLines) {
            $output .= '<li class="dropdown">' . $this->renderAbsy($itemLines) . '</li>' . "\n";
        }
        $output .= "</$type>\n";
        if ($this->withinDropdown) {
            $output .= '</li>' . "\n";
            $this->withinDropdown = false;
        }
        return $output;
    }

    /**
     * Parses the given text considering the full language.
     *
     * This includes parsing block elements as well as inline elements.
     *
     * @param string $text the text to parse
     * @param string $baseUri relative path for links
     * @return string parsed markup
     */
    public function parse($text, $baseUri = '')
    {
        $this->baseUri = $baseUri;
        $markup = parent::parse($text);
        return '<div class="collapse navbar-collapse navbar-ex1-collapse">'.$markup.'</div>';
    }
}
