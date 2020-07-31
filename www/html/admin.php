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

if(get_get('page') === '') {
  $page = 1;
} else{
  $page = get_get('page');    
}

$items = get_all_items($db);
$count = count($items);

// ページ設定
if($count % MAX_ITEM_PER_PAGE_ADMIN === 0) {
  $total_page = $count / MAX_ITEM_PER_PAGE_ADMIN;
} else {
  $total_page = floor($count / MAX_ITEM_PER_PAGE_ADMIN) + 1;
}

$f_position = ($page - 1) * MAX_ITEM_PER_PAGE_ADMIN;
  
if($page === 1) {
  $prev_page = 0;
} else {
  $prev_page = $page - 1;
}
  
if($count <= $f_position + MAX_ITEM_PER_PAGE_ADMIN) {
  $t_position = $count;
  $num_record = $t_position - ($page - 1) * MAX_ITEM_PER_PAGE_ADMIN;
  $next_page = 0;
    
} else {
  $t_position = $page * MAX_ITEM_PER_PAGE_ADMIN;
  $num_record = MAX_ITEM_PER_PAGE_ADMIN;
  $next_page = $page + 1;
}

// DB操作
$items = get_all_page_items($db, $f_position, $num_record);

include_once VIEW_PATH . '/admin_view.php';