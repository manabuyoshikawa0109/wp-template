<?php

/**
 * wordpressの命名規則では変数・関数はスネークケース(全て小文字)にする
 * 参考：https://www.sejuku.net/blog/104248
 */

// このメソッドが呼び出されたファイル(functions.php)があるディレクトリのフルパスを取得
$dir = __DIR__;
require_once("{$dir}/vendor/autoload.php");

use Jenssegers\Blade\Blade;

/**
 * Bladeテンプレートをレンダリングする
 */
if (!function_exists('render_blade')) {
    function render_blade($template_name)
    {
        /**
         * グローバル変数を呼び出し
         * 参考：https://webukatu.com/wordpress/blog/28688/
         */
        global $dir;
        $blade = new Blade("{$dir}/views", "{$dir}/cache");
        return $blade->make("pages/{$template_name}");
    }
}