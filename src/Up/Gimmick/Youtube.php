<?php
/**
 * $Id$
 *
 * Up! - An extremely simple, yet powerful markdown-based CMS
 *
 * Copyright (c) 2015 Innovacy, Dimitrios Karvounaris
 *
 * @version 1.2.1
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

namespace Innovacy\Up\Gimmick;

/**
 * Outputs youtube MarkDown links with empty captions as embedded youtube videos
 * Recognizes links for youtube.com and youtu.be
 * @package Innovacy\Up\Gimmick
 */
class Youtube extends GimmickBase
{
    /**
     * @var bool Should this be registered as link gimmick? Requires the method renderLink() to be implemented.
     */
    public $isLinkGimmick = false;

    /**
     * @var string No suffix keyword to recognize default youtube links.
     */
    public $gimmickKeyword = '';

    /**
     * Recognizes all links with youtube.com, youtu.be with protocol and protocol-less url's
     * @param $block
     * @return bool|string
     */
    public function renderLink($block)
    {
        // check first for not empty caption to increase performance and parse the host only when empty
        if (!empty($block['text']) || !preg_match('#^((https?:)?//)?(?P<host>youtube.com|youtu.be)/(watch\?v=)?(?P<videoid>[^&\#\?]+)(?P<params>.+)?$#', $block['url'], $m)) {
            return true;
        }
        // TODO: Responsive video frame, see CSS
        /* Basic CSS for all videos to 16:9:
        .video_frame {
            position: relative;
            padding-bottom: 56.25%;
            padding-top: 25px;
            height: 0;
        }
        .video_frame iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        */
        /* See https://css-tricks.com/NetMag/FluidWidthVideo/Article-FluidWidthVideo.php for javascript resize and non 16:9 */
        return '<div class="video_frame"><iframe width="560" height="315" '
            . 'src="//www.youtube.com/embed/'.$m['videoid'].'?iv_load_policy=3&rel=0'
            . (!empty($m['params']) ? ltrim($m['params'], '&?') : '')
            . '" frameborder="0" allowfullscreen></iframe></div>';
        return $block['url'];
    }
}