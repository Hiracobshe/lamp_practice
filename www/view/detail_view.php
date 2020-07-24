<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'detail.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>

  
<div>注文番号：<?php print $order_id; ?></div>
<div>購入日時：<?php print $date; ?></div>
<div>合計金額：<?php print $sum; ?></div>

  <?php foreach($display as $value) { ?>
  
    <ul>
      <li>商品名：<?php print $value['name']; ?></li>
      <li>購入時の商品価格：<?php print $value['price']; ?></li>
      <li>購入数：<?php print $value['number']; ?></li>
      <li>小計：<?php print $value['price'] * $value['number']; ?></li>
    </ul>
  
  <?php } ?>

</body>
</html>