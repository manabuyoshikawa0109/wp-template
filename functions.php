<?php

/**
 * 開発に関するルール
 * ■ 命名規則
 * WordPressの命名規則では変数・関数はスネークケース(全て小文字)にする
 * 参考：https://www.sejuku.net/blog/104248
 */

/**
 * このメソッドが呼び出されたファイル(functions.php)があるディレクトリのフルパスを取得
 * 参考:https://hara-chan.com/it/programming/php-magic-conatants/
 */
$dir = __DIR__;
require_once("{$dir}/vendor/autoload.php");

use Jenssegers\Blade\Blade;

/**
 * Bladeテンプレートをレンダリングする
 * @param string $template_name
 * @return string
 */
if (!function_exists('render_blade')) {
    function render_blade(string $template_name)
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

/**
 * カスタムヘッダー設定でWordPress管理画面からヘッダーにあるロゴ画像を登録できるようにする
 * カスタムヘッダー画像の設定
 * get_bloginfo('template_url')でこのテンプレートまでのURLを取得
 */
$custom_header_defaults = [
    'default-image' => get_bloginfo('template_url') . '/images/headers/logo.png', // デフォルトで表示する画像のパス
    'text-image' => false, // ロゴ画像上にテキストを重ねるか
];

/**
 * カスタムヘッダー機能を有効化
 * 第1引数：custom-headerを渡すことでカスタムヘッダーを有効化することをWordPressに伝えている
 * 第2引数：カスタムヘッダー利用時の設定条件を渡している
 */
add_theme_support('custom-header', $custom_header_defaults);

/**
 * カスタムメニューを使用
 * 第1引数：wp_nav_menuメソッドに渡している配列のtheme_locationの値を渡す
 * 第2引数：WordPress管理画面に表示させるメニュー位置の名前
 */
register_nav_menu('header_menu', 'ヘッダーメニュー');

register_nav_menu('footer_menu', 'フッターメニュー');

/**
 * ページネーション
 * @param int $total_page 投稿一覧画面の総ページ数(全投稿数 / 投稿一覧1ページ毎の投稿表示数)
 * @param int $range ページネーションを前後何ページ分表示するか
 * @return string
 */
function pagination(int $total_page = null, int $range = 2)
{
    // WordPressで用意されている現在のページ数を表すグローバル変数
    global $paged;
    // 現在のページ数が1ページ目の場合、$pagedには0が返ってくるのでその場合1と変換
    $current_page = (0 < $paged) ? $paged : 1;

    // 引数の総ページ数がない場合、内部で総ページ数を取得(取得できなければ1とする)
    if ($total_page == null) {
        global $wp_query;
        $total_page = ($wp_query->max_num_pages) ? $wp_query->max_num_pages : 1;
    }

    // 総ページ数が1の場合、何も表示しない
    if ($total_page == 1) {
        return null;
    }

    $pagination_html = "<div class=\"pagination\"><ul>";

    // 現在のページが1ページ目でない場合、「前へ」のリンク表示
    if ($current_page != 1) {
        $previous_page_url = get_pagenum_link($current_page - 1);
        $pagination_html .= "<li class=\"prev\"><a href=\"{$previous_page_url}\">Prev</a></li>";
    }


    // 総ページ数分ループ
    for ($page = 1; $page <= $total_page; $page++) {
        // (現在のページ数 - 表示範囲) <= ページ数 <= (現在のページ数 + 表示範囲)の場合のみページネーション表示
        // 例：現在のページ数が10ページ目の場合、「8・9・『10』・11・12」と表示したい
        if (($current_page - $range) <= $page && $page <= ($current_page + $range)){
            // 現在のページ数の場合
            if ($page == $current_page) {
                $pagination_html .= "<li class=\"current\">{$page}</li>";
            } else {
                $page_url = get_pagenum_link($page);
                $pagination_html .= "<li><a href=\"{$page_url}\">{$page}</a></li>";
            }
        }
    }


    // 現在のページ数が総ページ数より小さい場合、「次へ」のリンク表示
    if ($current_page < $total_page) {
        $next_page_url = get_pagenum_link($current_page + 1);
        $pagination_html .= "<li class=\"next\"><a href=\"{$next_page_url}\">Next</a></li>";
    }

    $pagination_html .= "</ul></div>";

    return $pagination_html;
}
