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

//
$history = get_all_history($db);

include_once VIEW_PATH . '/admin_history_view.php';
