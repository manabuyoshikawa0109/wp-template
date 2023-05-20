<header>
    <h1>
        {{-- ホーム画面に戻るURL --}}
        <a href="{{ home_url() }}">
            {{-- 管理画面から入力されたロゴ画像を反映、alt属性にWordPress環境構築時に指定したこのサイトのタイトルを反映 --}}
            <img style="width: 100px;" src="{{ header_image() }}" alt="{{ bloginfo('name') }}">
        </a>
    </h1>
    <nav>
        <ul>
            {{-- メニューを管理画面から登録・表示できるようにする --}}
            {{ wp_nav_menu([
                'theme_location' => 'header_menu', // functions.phpのregister_nav_menu()で登録したロケーション名を指定
                'container' => false, // ulタグをラップするか、ラップする場合、どのタグでラップするか
                'menu_class' => '', // ulタグに適用するクラス名
                'items_wrap' => '<ul id=”%1$s” class=”%2$s”>%3$s</ul>', // 生成されるメニューのフォーマット指定(%1$sにmenu_idの値、%2$sにmenu_classの値、%3$sはリスト項目)
            ]) }}
        </ul>
    </nav>
</header>
