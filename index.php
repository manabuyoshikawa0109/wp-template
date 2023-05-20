<?php
/*
固定ページ新規追加・編集時にどのテンプレートを使用するか選択できるよう以下のコメント必須
下記は「これは固定ページのテンプレートですよ」ということをWordpressに伝えている

Template Name: トップページ
*/

echo render_blade(basename(__FILE__, '.php'));