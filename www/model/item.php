<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// DB利用

function get_item($db, $item_id){
  $sql = "
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
    WHERE
      item_id = ?
  ";

  return fetch_query($db, $sql, [$item_id]);
}

function get_items($db, $is_open = false){
  $sql = '
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
  ';
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }

  return fetch_all_query($db, $sql);
}

function get_page_items($db, $from_page, $num, $is_open = false){
  $sql = '
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
    LIMIT ?, ?
  ';
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }

  return fetch_all_query($db, $sql, [$from_page, $num]);
}

function get_all_items($db){
  return get_items($db);
}

function get_all_page_items($db, $from_page, $num){
  return get_page_items($db, $from_page, $num);
}

function get_open_items($db){
  return get_items($db, true);
}


function get_history($db, $user_id = null) {
  $params = array();
  $sql = '
    SELECT
      history.order_id,
      history.created,
      history.sum
    FROM
      history
  ';
  if($user_id !== null){
    $params[] = $user_id;
    $sql .= '
      WHERE user_id = ?
    ';
  }  

  return fetch_all_query($db, $sql, $params);
}


function get_display($db, $order_id, $user_id = null) {
  $params = array($order_id);
  $sql = '
    SELECT
      items.name,
      detail.price,
      detail.number
    FROM
      detail
    INNER JOIN items
    ON         items.item_id = detail.item_id
    WHERE      detail.order_id = ?
  ';
  if($user_id !== null){
    array_push($params, $order_id, $user_id);
    $sql .= '
      AND exists(SELECT * 
                FROM history 
                WHERE order_id = ?
                AND   user_id = ?)
    ';
  }  

  return fetch_all_query($db, $sql, $params);
}


function regist_item($db, $name, $price, $stock, $status, $image){
  $filename = get_upload_filename($image);
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    return false;
  }
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}

function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){
  $db->beginTransaction();
  if(insert_item($db, $name, $price, $stock, $filename, $status) 
    && save_image($image, $filename)){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
  
}

function insert_item($db, $name, $price, $stock, $filename, $status){
  $status_value = PERMITTED_ITEM_STATUSES[$status];
  $sql = "
    INSERT INTO
      items(
        name,
        price,
        stock,
        image,
        status
      )
    VALUES(?, ?, ?, ?, ?);
  ";

  return execute_query($db, $sql, [$name, $price, $stock, $filename, $status_value]);
}

function update_item_status($db, $item_id, $status){
  $sql = "
    UPDATE
      items
    SET
      status = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";
  
  return execute_query($db, $sql, [$status, $item_id]);
}

function update_item_stock($db, $item_id, $stock){
  $sql = "
    UPDATE
      items
    SET
      stock = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";
  
  return execute_query($db, $sql, [$stock, $item_id]);
}

function insert_item_detail($db, $order_id, $item_id, $price, $number) {
  $sql = "
    INSERT INTO
      detail(
        order_id,
        item_id,
        price,
        number
      )
    VALUES(?, ?, ?, ?);
  ";

  return execute_query($db, $sql, [$order_id, $item_id, $price, $number]); 
}


function insert_item_history($db, $user_id, $date, $sum) {
  $sql = "
    INSERT INTO
      history(
        user_id,
        created,
        sum
      )
    VALUES(?, ?, ?);
  ";

  return execute_query($db, $sql, [$user_id, $date, $sum]);  
}


function destroy_item($db, $item_id){
  $item = get_item($db, $item_id);
  if($item === false){
    return false;
  }
  $db->beginTransaction();
  if(delete_item($db, $item['item_id'])
    && delete_image($item['image'])){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
}

function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = ?
    LIMIT 1
  ";
  
  return execute_query($db, $sql, [$item_id]);
}


// 非DB

function is_open($item){
  return $item['status'] === 1;
}

function validate_item($name, $price, $stock, $filename, $status){
  $is_valid_item_name = is_valid_item_name($name);
  $is_valid_item_price = is_valid_item_price($price);
  $is_valid_item_stock = is_valid_item_stock($stock);
  $is_valid_item_filename = is_valid_item_filename($filename);
  $is_valid_item_status = is_valid_item_status($status);

  return $is_valid_item_name
    && $is_valid_item_price
    && $is_valid_item_stock
    && $is_valid_item_filename
    && $is_valid_item_status;
}

function is_valid_item_name($name){
  $is_valid = true;
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}

function is_valid_item_price($price){
  $is_valid = true;
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

function is_valid_item_stock($stock){
  $is_valid = true;
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

function is_valid_item_filename($filename){
  $is_valid = true;
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}

function is_valid_item_status($status){
  $is_valid = true;
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    $is_valid = false;
  }
  return $is_valid;
}