<!DOCTYPE html>
<html lang="ja">

<head>
    {{-- WordPressの管理画面から設定した文字コードを反映 --}}
    <meta charset="{{ bloginfo('charset') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- WordPressの管理画面から設定したタイトルを反映 --}}
    <title>{{ wp_title() }}</title>
    {{-- WordPress独自のメソッド。このテーマに使用されているstyle.cssを読み込んでくれる --}}
    <link rel="stylesheet" href="{{ get_stylesheet_uri() }}">

    {{-- WordPress管理画面から設定したkeyword, description, author等が反映される --}}
    {{ wp_head() }}
</head>

{{-- WordPressで用意されたメソッド。CSS用にWordPressで使うクラス属性を付与 --}}
<body {{ body_class() }}>
    {{-- ヘッダーの読み込み --}}
    @include('commons.header')
    <main>
        @yield('content')
    </main>
    {{-- フッターの読み込み --}}
    @include('commons.footer')
</body>

</html>
