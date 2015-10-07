<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 07.10.2015
 * Time: 11:04
 */

namespace Innovacy\Up;

use cebe\markdown\Markdown;

class Navigation extends Markdown
{
    protected function renderHeadline($block)
    {
        $tag = 'h' . $block['level'];
        return "<$tag>" . $this->renderAbsy($block['content']) . "</$tag>\n";
    }

    public function parse($text)
    {
        $markup = parent::parse($text);
        return $markup;
    }
}
