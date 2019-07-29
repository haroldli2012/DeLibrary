<?php
/*require "/src/Pinyin/Pinyin.php";
\Overtrue\Pinyin\Pinyin::set('accent','');
\Overtrue\Pinyin\Pinyin::set('delimiter','');
echo \Overtrue\Pinyin\Pinyin::trans('万欣翠园..123 hello');
echo '<br/>';
echo \Overtrue\Pinyin\Pinyin::letter('明月花园');*/

composer require "overtrue/pinyin:3.0";

use Overtrue\Pinyin\Pinyin;

$py = new Pinyin();

$py->convert("带着希望去旅行,比到达终点更美好");

?>