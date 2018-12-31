<?php
/**
 * Created by PhpStorm.
 * User: dolhen
 * Date: 28/12/18
 * Time: 22:50
 */

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('bbCode', array($this, 'bbCodeFormat')),
            new TwigFilter('cut', array($this, 'cut')),
        );
    }

    public function bbCodeFormat(string $text)
    {
        $text = htmlentities($text);
        $array = $this->bbCodeArray();

        foreach ($array as $item) {
            if ($item[0] != null) {
                $text = str_replace($item[0], $item[2], $text);
                $text = str_replace($item[1], $item[3], $text);
            }

            if ($item[0] === null) {
                if ($item[1] !== null) {
                    $text = preg_replace($item[1], $item[2], $text);
                    $text= str_replace($item[3], $item[4], $text);
                }

                if ($item[1] === null) {
                    if (preg_match($item[2], $text)) {
                        $text = preg_replace($item[2], $item[4], $text);
                    }

                    if (preg_match($item[3], $text)) {
                        $text = preg_replace($item[3], $item[5], $text);
                    }
                }
            }
        }

        return $text;
    }

    public function cut(string $text, int $num = 25)
    {
        if (strlen($text) > $num) {
            $text = substr($text, 0,$num).'...';
        }

        return $text;
    }

    public function bbCodeArray()
    {
        return $array = [
            ['[b]', '[/b]', '<strong>', '</strong>'],
            ['[i]', '[/i]', '<i>', '</i>'],
            ['[u]', '[/u]', '<u>', '</u>'],
            ['[/br]', null , '</br>' , null],
            ['[center]', '[/center]', '<div style="text-align: center">', '</div>'],
            ['[right]', '[/right]', '<div style="text-align: right">', '</div>'],
            ['[justify]', '[/justify]', '<div style="text-align: justify">', '</div>'],
            ['[/]', null, '<hr width="100%" size="1" />', null],
            [ null , '/\[item\](.{0,10})\[\/item\]/','<a href="https://wowhead.com/item=\\1" class="wowhead">\\1</a>' , null , null  ],
            [ null , '/\[color= ?(([[:alpha:]]+)|(#[[:digit:][:alpha:]]{6})) ?\]/' , '<span style="color: \\1">' , '[/color]', '</span>'],
            [ null , '/\[size= ?([[:digit:]]+) ?\]/' , '<span style="font-size: \1 px">' , '[/size]' , '</span>'],
            [ null , null , '/\[url\] ?([^\[]*) ?\[\/url\]/' , '/\[url ?=([^\[]*) ?] ?([^]]*) ?\[\/url\]/', '<a href=\1>\1</a>' , '<a href=\1>\2</a>'],
            [ null , null , '/\[email\] ?([^\[]*) ?\[\/email\]/' , '/\[email ?=([^\[]*) ?] ?([^]]*) ?\[\/email\]/' , '<a href=mailto:\1>\1</a>' , '<a href=mailto:\1>\2</a>'],
            [ null , null , '/\[img\] ?([^\[]*) ?\[\/img\]/' , '/\[img ?= ?([^\[]*) ?\]/' , '<img src=\1 alt=\"\" border=\"0\" />' , '<img src=\1 alt=\"\" border=\"0\" />'],
        ];
    }
}

