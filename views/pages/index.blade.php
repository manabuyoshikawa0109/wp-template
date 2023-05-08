@extends('layouts.base')

@section('content')
    <div class="container">
        <ul>
            @if (have_posts())
                @while (have_posts())
                    <li>
                        <a href="{{ the_permalink() }}">{{ the_title() }}</a>
                    </li>
                @endwhile
            @else
                    まだ投稿がありません。
            @endif
        </ul>
    </div>
@endsection