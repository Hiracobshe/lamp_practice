<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'history.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <div class="container">
    <?php if (count($history) === 0) { ?>
      <p class="alert alert-danger"><span><?php print '不正なリクエストです。'; ?></span></p>
    <?php } else { ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>合計金額</th>
            <th>明細</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($history as $value) { ?>
            <tr>
              <td><?php print h($value['order_id']); ?></td>
              <td><?php print h($value['created']); ?></td>
              <td><?php print h($value['sum']); ?>円</td>
              <form method="get" enctype="multipart/form-data" action="./history.php">
                <td><a href="./detail.php?order_id=<?php print $value['order_id'] . "&created=" . $value['created'] . "&sum=" . $value['sum']; ?>">明細へ</a></td>
              </form>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } ?>
  </div>
</body>
</html>