{{-- トップ画面 --}}
@extends('layouts.base')

@section('content')
    <div class="container">
        <div id="main">
            <div>
                {{-- 投稿があるか存在チェック --}}
                @if (have_posts())
                    {{-- 投稿がある場合はその回数分ループ --}}
                    @while (have_posts())
                        {{-- 記事内容を取得(the_postを記載しないと無限ループになってしまう) --}}
                        {{ the_post() }}
                        {{-- 固定ページのタイトル --}}
                        <h1>{{ get_the_title() }}</h1>
                        {{-- 固定ページ一意のIDと、固定ページ投稿画面から入力できるクラス名を出力 --}}
                        <div id="post-{{ the_ID() }}" {{ post_Class() }}>
                            {{-- 固定ページの内容 --}}
                            {{ the_content() }}
                        </div>
                    @endwhile
                @else
                    記事がありません。
                    お探しの記事は見つかりませんでした。
                @endif
            </div>
        </div>
    </div>
@endsection
