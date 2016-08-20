<?php
/**
 * Created by PhpStorm.
 * User: Dimitrios
 * Date: 21.08.2016
 * Time: 00:38
 */

namespace Innovacy\Up\Gimmick;

/**
 * Renders alert paragraphs
 * @package Innovacy\Up\Gimmick
 */
class Alerts extends GimmickBase
{
    /**
     * @var bool Should this be registered as paragraph gimmick? Requires renderParagraph() to be implemented.
     */
    public $isParagraphGimmick = true;

    /**
     * Renders an alert paragraph
     * @param array $block
     * @param string $text
     * @return bool|string
     */
    public function renderParagraph($block, $text)
    {
        $alertClass = '';
        if (preg_match('/^(warning|achtung|attention|warnung|atención|guarda|advertimiento)[\:\!]\s/i', $text)) {
            $alertClass = 'warning';
        } elseif (preg_match('/^(note|beachte)[\:\!]\s/i', $text)) {
            $alertClass = 'info';
        } elseif (preg_match('/^(hint|tip|tipp|hinweis)[\:\!]\s/i', $text)) {
            $alertClass = 'success';
        }
        if (empty($alertClass)) {
            return true;
        }
        return '<div class="alert alert-' . $alertClass . '"><p class="md-text">'
        . $this->parser->renderAbsy($block['content'])
        . '</p></div>' . "\n";
    }
}