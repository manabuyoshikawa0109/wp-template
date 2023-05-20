<?php
/*
ブログ記事一覧用(デフォルト、カテゴリー・タグ・日付絞り込み時)のファイル名は「archive.php」と決まっている
*/

echo render_blade(basename(__FILE__, '.php'));