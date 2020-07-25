<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'detail.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <div class="container">
    <?php if (count($display) === 0) { ?>
      <p class="alert alert-danger"><span><?php print '不正なリクエストです。'; ?></span></p>
    <?php } else { ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>合計金額</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php print $order_id; ?></td>
            <td><?php print $date; ?></td>
            <td><?php print $sum; ?>円</td>
          </tr>
        </tbody>
      </table>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>購入時の商品価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($display as $value) { ?>
            <tr>
              <td><?php print h($value['name']); ?></td>
              <td><?php print h($value['price']); ?>円</td>
              <td><?php print h($value['number']); ?></td>
              <td><?php print h($value['price'] * $value['number']); ?>円</td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } ?>
  </div>
</body>
</html>