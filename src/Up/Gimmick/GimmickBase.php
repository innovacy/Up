<?php
/**
 * Created by PhpStorm.
 * User: Dimitrios
 * Date: 16.08.2016
 * Time: 22:25
 */

namespace Innovacy\Up\Gimmick;

/**
 * Class Gimmick
 * All gimmick plugins must inherit this class
 * @method renderLink($block, $text)
 * @method renderParagraph($block, $text)
 * @package Innovacy\Up\Gimmick
 */
abstract class GimmickBase
{
    /**
     * @var \Innovacy\Up\MarkDown Active parser calling the gimmick class
     */
    protected $parser = null;

    /**
     * @var \Innovacy\Up\Configuration Configuration object
     */
    protected $config = null;

    /**
     * @var bool Should this be registered as link gimmick? Requires the method renderLink() to be implemented.
     */
    public $isLinkGimmick = false;

    /**
     * @var string The gimmick keyword suffix associated with a gimmick.
     */
    public $gimmickKeyword = '';

    /**
     * @var bool Should this be registered as paragraph gimmick? Requires renderParagraph() to be implemented.
     */
    public $isParagraphGimmick = false;

    /**
     * GimmickBase constructor.
     */
    public function __construct()
    {
        $this->config = \Innovacy\Up\IoC::get('config');
        $this->parser = \Innovacy\Up\IoC::get('parser');
    }

}
