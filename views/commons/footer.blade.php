<footer>
    <ul>
        {{-- メニューを管理画面から登録・表示できるようにする --}}
        {{ wp_nav_menu([
            'theme_location' => 'footer_menu', // functions.phpのregister_nav_menu()で登録したロケーション名を指定
            'container' => false, // ulタグをラップするか、ラップする場合、どのタグでラップするか
            'menu_class' => '', // ulタグに適用するクラス名
            'items_wrap' => '<ul id=”%1$s” class=”%2$s”>%3$s</ul>', // 生成されるメニューのフォーマット指定(%1$sにmenu_idの値、%2$sにmenu_classの値、%3$sはリスト項目)
        ]) }}
    </ul>
</footer>
