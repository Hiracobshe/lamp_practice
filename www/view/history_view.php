<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'history.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>

<?php foreach($history as $value) { ?>

<ul class="list_history">
  <li>注文番号：<?php print $value['order_id']; ?></li>
  <li>購入日時：<?php print $value['created']; ?></li>
  <li>合計金額：<?php print $value['sum']; ?></li>
  <form method="get" enctype="multipart/form-data" action="./history.php">
    <li><a href="./detail.php?order_id=<?php print $value['order_id'] . "&created=" . $value['created'] . "&sum=" . $value['sum']; ?>">明細へ</a></li>
  </form>
</ul>

<?php } ?>
   
</body>
</html>