<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

if (is_logined() === false) {
  redirect_to(LOGIN_URL);
}


$db = get_db_connect();
$user = get_login_user($db);

if (get_get('page') === '') {
  $page = 1;
} else {
  $page = get_get('page');
}

$items = get_open_items($db);
$count = count($items);

// ページ設定
if ($count % MAX_ITEM_PER_PAGE_INDEX === 0) {
  $total_page = $count / MAX_ITEM_PER_PAGE_INDEX;
} else {
  $total_page = floor($count / MAX_ITEM_PER_PAGE_INDEX) + 1;
}

$f_position = ($page - 1) * MAX_ITEM_PER_PAGE_INDEX;

if ($page === 1) {
  $prev_page = 0;
} else {
  $prev_page = $page - 1;
}

if ($count <= $f_position + MAX_ITEM_PER_PAGE_INDEX) {
  $t_position = $count;
  $num_record = $t_position - ($page - 1) * MAX_ITEM_PER_PAGE_INDEX;
  $next_page = 0;
} else {
  $t_position = $page * MAX_ITEM_PER_PAGE_INDEX;
  $num_record = MAX_ITEM_PER_PAGE_INDEX;
  $next_page = $page + 1;
}

$items = get_open_page_items($db, $f_position, $num_record);

include_once VIEW_PATH . 'index_view.php';
