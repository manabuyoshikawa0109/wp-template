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

        global $post;
        global $wp_query;
        return $blade->make("pages/{$template_name}", compact('post', 'wp_query'));
    }
}

/**
 * BootstrapをWordPressへ読み込み
 * @return void
 */
function register_bootstrap()
{
    /**
     * WordPressが生成したページにCSSファイル/リンクを読み込み
     * 第1引数：スタイルの名前
     * 第2引数：CSSのパス/URL
     */
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css');
    /**
     * WordPressが生成したページにJavaScriptファイル/リンクを読み込み
     * 第1引数：スクリプトの名前
     * 第2引数：スクリプトのパス/URL
     */
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js');
}

/**
 * WordPress管理画面にBootstrap読み込み
 * ※admin_enqueue_scriptsを指定することでWordPressの管理画面に読み込んでくれる
 */
add_action('admin_enqueue_scripts', 'register_bootstrap');

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

    $pagination_html = '<div class="pagination"><ul>';

    // 現在のページが1ページ目でない場合、「前へ」のリンク表示
    if ($current_page != 1) {
        $pagination_html .= '<li class="prev"><a href="' . get_pagenum_link($current_page - 1) . '">Prev</a></li>';
    }


    // 総ページ数分ループ
    for ($page = 1; $page <= $total_page; $page++) {
        // (現在のページ数 - 表示範囲) <= ページ数 <= (現在のページ数 + 表示範囲)の場合のみページネーション表示
        // 例：現在のページ数が10ページ目の場合、「8・9・『10』・11・12」と表示したい
        if (($current_page - $range) <= $page && $page <= ($current_page + $range)){
            // 現在のページ数の場合
            if ($page == $current_page) {
                $pagination_html .= '<li class="current">' . $page . '</li>';
            } else {
                $pagination_html .= '<li><a href="' . get_pagenum_link($page) . '">' . $page . '</a></li>';
            }
        }
    }


    // 現在のページ数が総ページ数より小さい場合、「次へ」のリンク表示
    if ($current_page < $total_page) {
        $pagination_html .= '<li class="next"><a href="' . get_pagenum_link($current_page + 1) . '">Next</a></li>';
    }

    $pagination_html .= '</ul></div>';

    return $pagination_html;
}

/**
 * カスタムフィールド
 * Wordpressで用意されているadd_actionメソッドを使い、カスタムフィールドを使うことをWordPressに伝える
 */
// WordPress管理画面の投稿ページへ表示するカスタムフィールドを定義
add_action('admin_menu', 'add_custom_box');
// WordPress管理画面の投稿ページに追加したカスタムフィールドの保存アクションフック(プログラム中の特定の箇所に、後から別のプログラムによって処理を追加できる仕組み)
add_action('save_post', 'save_postdata');

/**
 * WordPress管理画面の投稿ページへ表示するカスタムフィールドの設定を定義
 * ※入力フォームのタイトル、入力フォームの種類(input、textarea)、どのページに表示させるか等の設定が可能
 * 第1引数：メタボックス(エリアのような閉じたり移動できたりする箱)であるdivタグのid属性(管理画面側で入力フォームのデザインを変えたい際に使用)
 *     ※第1引数は必ず一意のものをつけないとカスタムフィールドが表示されない
 * 第2引数：メタボックスのタイトル名
 * 第3引数：メタボックスに出力するHTMLを定義した関数名
 * 第4引数：カスタムフィールドを管理画面のどのページに表示するか(postなら投稿ページ、pageなら固定ページ)
 * 第5引数：配置される順序
 * @return void
 */
function add_custom_box()
{
    add_meta_box('top-banner-image-url', 'トップ画像URL', 'top_banner_image_url_custom_box', 'page', 'normal');
    add_meta_box('google-map-embedded-html', 'Googleマップ地図埋め込みコード', 'google_map_embedded_html_custom_box', 'page', 'normal');
    add_meta_box('company-info', '会社情報', 'company_info_custom_box', 'page', 'normal');
}

/**
 * トップバナー画像URL入力フォームをWordPress管理画面に表示
 * @return void
 */
function top_banner_image_url_custom_box()
{
    global $post;
    $value = get_post_meta($post->ID, 'top_banner_image_url', true);
    // ヒアドキュメントを使い長い文字列を変数に格納
    $html = <<<EOM
    <input type="text" class="form-control" name="top_banner_image_url" value="{$value}" maxlength="" placeholder="">
    <small class="text-muted">※ 画像のURLは「メディア」でアップロードした画像を選択し、「ファイルのURL」から取得してください</small>
    EOM;
    echo $html;
}

/**
 * Googleマップ地図埋め込みコード入力フォームをWordPress管理画面に表示
 * @return void
 */
function google_map_embedded_html_custom_box()
{
    global $post;
    $value = get_post_meta($post->ID, 'google_map_embedded_html', true);
    // ヒアドキュメントを使い長い文字列を変数に格納
    $html = <<<EOM
    <textarea name="google_map_embedded_html" class="form-control" rows="4">{$value}</textarea>
    EOM;
    echo $html;
}

/**
 * 会社情報入力フォームをWordPress管理画面に表示
 * @return void
 */
function company_info_custom_box()
{
    global $post;
    $zip = get_post_meta($post->ID, 'zip', true);
    $address = get_post_meta($post->ID, 'address', true);
    $company_name = get_post_meta($post->ID, 'company_name', true);
    $tel = get_post_meta($post->ID, 'tel', true);
    $email = get_post_meta($post->ID, 'email', true);
    $business_hour = get_post_meta($post->ID, 'business_hour', true);
    // ヒアドキュメントを使い長い文字列を変数に格納
    $html = <<<EOM
    <table class="table">
        <tbody>
            <tr>
                <td class="table-active align-middle">郵便番号</td>
                <td colspan="2"><input type="text" class="form-control" name="zip" value="{$zip}" maxlength="8" placeholder="123-4567"></td>
                <td class="table-active align-middle">住所</td>
                <td colspan="2"><input type="text" class="form-control" name="address" value="{$address}" maxlength="" placeholder="大阪府大阪市北区梅田３丁目１−１"></td>
            </tr>
            <tr>
                <td class="table-active align-middle">会社名</td>
                <td colspan="2"><input type="text" class="form-control" name="company_name" value="{$company_name}" maxlength="" placeholder="株式会社ABC"></td>
                <td class="table-active align-middle">電話番号</td>
                <td colspan="2"><input type="text" class="form-control" name="tel" value="{$tel}" maxlength="13" placeholder="012-3456-7890"></td>
            </tr>
            <tr>
                <td class="table-active align-middle">メールアドレス</td>
                <td colspan="2"><input type="text" class="form-control" name="email" value="{$email}" maxlength="" placeholder="abc-company@gmail.com"></td>
                <td class="table-active align-middle">営業時間</td>
                <td colspan="2"><input type="text" class="form-control" name="business_hour" value="{$business_hour}" maxlength="" placeholder="平日9:00〜18:00"></td>
            </tr>
        </tbody>
    </table>
    EOM;

    echo $html;
}

/**
 * WordPress管理画面の固定・投稿ページ保存時のカスタムフィールドのデータ更新・削除
 *
 * @param integer $post_id 固定ページのID
 * @return void
 */
function save_postdata($post_id)
{
    // カスタムフィールドのname属性を配列内に定義
    $keys = ['top_banner_image_url', 'google_map_embedded_html', 'zip', 'address', 'company_name', 'tel', 'email', 'business_hour'];

    foreach ($keys as $key) {
        // 値が存在する場合、変数に格納
        $value = isset($_POST[$key]) ? $_POST[$key] : null;

        if ($value != get_post_meta($post_id, $key, true)) {
            // DBに保存されている内容と変わっている場合、保存されている内容を入力された内容へ更新
            update_post_meta($post_id, $key, $value);
        } elseif ($value == null) {
            // 入力された情報が空の場合、DBに保存されている内容も空にする
            delete_post_meta($post_id, $key, get_post_meta($post_id, $key, true));
        }
    }
}