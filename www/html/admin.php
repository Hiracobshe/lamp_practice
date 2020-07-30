<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

// ログインしていない場合
if(is_logined() === false){

  // リダイレクトする
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);

// 管理者かどうかの判定
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

if(get_post('page') === '') {
  $page = 1;
} else{
  $page = get_post('page');    
}

print '[DEBUG] page = ' . $page . '<br>';

$items = get_all_items($db);

$count = count($items);

// ページ設定
if($count % 4 === 0) {
  $total_page = $count / 4;
} else {
  $total_page = floor($count / 4) + 1;
}

$from_page = ($page - 1) * MAX_ITEM_PER_PAGE;
  
if($page === 1) {
  $prev_page = 0;
} else {
  $prev_page = $page - 1;
}
  
if($count <= $from_page + MAX_ITEM_PER_PAGE) {
  $to_page = $count;
  $num = $to_page - ($page - 1) * MAX_ITEM_PER_PAGE;
  $next_page = 0;
    
} else {
  $to_page = $page * MAX_ITEM_PER_PAGE;
  $num = MAX_ITEM_PER_PAGE;
  $next_page = $page + 1;
}

// DB操作
$items = get_all_page_items($db, $from_page, $num);

print '[DEBUG] total_page = '. $total_page. '<br>';
print '[DEBUG] from_page = ' . $from_page . '<br>';
print '[DEBUG] to_page = '   . $to_page . '<br>';
print '[DEBUG] prev_page = ' . $prev_page . '<br>';
print '[DEBUG] next_page = ' . $next_page . '<br>';
print '[DEBUG] num = '       . $num . '<br>';


include_once VIEW_PATH . '/admin_view.php';
