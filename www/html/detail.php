<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

if (is_logined() === false) {
    redirect_to(LOGIN_URL);
}

var_dump($_GET);

$db = get_db_connect();
$user = get_login_user($db);
$order_id = get_get('order_id');
$date     = get_get('created');
$sum      = get_get('sum');

// 管理者かどうかの判定
if (is_admin($user)) {
    $display = get_display($db, $order_id);
} else {
    $display = get_display($db, $order_id, $user[0]['user_id']);
}

include_once VIEW_PATH . 'detail_view.php';
