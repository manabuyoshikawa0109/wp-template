{{-- 記事詳細画面 --}}
@extends('layouts.base')

@section('content')
    <div class="container">
        <div>
            <h1>ブログ詳細</h1>
            <div id="content">
                {{-- 投稿があるか存在チェック --}}
                @if (have_posts())
                    {{-- 投稿がある場合はその回数分ループ --}}
                    @while (have_posts())
                        <article>
                            {{-- 投稿内容を取得(the_postを記載しないと無限ループになってしまう) --}}
                            {{ the_post() }}
                            {{-- 記事ページのタイトル及び記事詳細ページへのリンク --}}
                            <h2><a href="{{ the_permalink() }}">{{ get_the_title() }}</a></h2>
                            {{-- 記事投稿者、記事投稿日、カテゴリー名 --}}
                            {{ the_author_nickname() }} {{ the_time('Y年n月j日') }} {{ single_cat_title('カテゴリー: ') }}
                            {{-- 記事内容 --}}
                            {{ the_content() }}
                        </article>
                    @endwhile
                    <div class="pagination">
                        <ul>
                            {{-- 記事の移動ページネーション(WordPressで用意されているメソッド) --}}
                            <li>{{ previous_post_link('%link', 'PREV') }}</li>
                            <li>{{ next_post_link('%link', 'NEXT') }}</li>
                        </ul>
                    </div>
                    {{-- コメントの入力欄と記事に対してのコメントを自動表示 --}}
                    {{ comments_template() }}
                @else
                    記事が見つかりませんでした。検索で見つかるかもしれません。
                    {{--
                        WordPressで用意されている記事検索用のフォームを表示
                        カスタマイズしたい場合はsearchform.phpを用意
                        --}}
                    {{ get_search_form() }}
                @endif
            </div>
            {{-- 記事一覧用サイドバー --}}
            @include('commons.blog_sidebar')
        </div>
    </div>
@endsection
