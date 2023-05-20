{{-- 記事一覧画面 --}}
@extends('layouts.base')

@section('content')
    <div class="container">
        <div>
            <h1>ブログ一覧</h1>
            <div id="content">
                {{-- 投稿があるか存在チェック --}}
                @if (have_posts())
                    {{-- 投稿がある場合はその回数分ループ --}}
                    @while (have_posts())
                        {{-- 投稿内容を取得(the_postを記載しないと無限ループになってしまう) --}}
                        {{ the_post() }}
                        {{-- 記事ページのタイトル及び記事詳細ページへのリンク --}}
                        <h2><a href="{{ the_permalink() }}">{{ get_the_title() }}</a></h2>
                        {{-- 記事投稿者、記事投稿日、カテゴリー名 --}}
                        {{ the_author_nickname() }} {{ the_time('Y年n月j日') }} {{ single_cat_title('カテゴリー: ') }}
                        {{-- 記事内容 --}}
                        {{ the_content() }}
                    @endwhile
                @endif
                {{-- ページネーション --}}
                @if (function_exists('pagination'))
                    {!! pagination() !!}
                @endif
            </div>
            {{-- 記事一覧用サイドバー --}}
            @include('commons.blog_sidebar')
        </div>
    </div>
@endsection
