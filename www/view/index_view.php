<!DOCTYPE html>
<html lang="ja">

<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>

  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>

<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>


  <div class="container">
    <h1>商品一覧(全<?php print $count; ?>件中<?php print $f_position + 1; ?>-<?php print $t_position; ?>件目を表示)</h1>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <div class="card-deck">
      <div class="row">
        <?php foreach ($items as $item) { ?>
          <div class="col-6 item">
            <div class="card h-100 text-center">
              <div class="card-header">
                <?php print(h($item['name'])); ?>
              </div>
              <figure class="card-body">
                <img class="card-img" src="<?php print(IMAGE_PATH . h($item['image'])); ?>">
                <figcaption>
                  <?php print(number_format(h($item['price']))); ?>円
                  <?php if ($item['stock'] > 0) { ?>
                    <form action="index_add_cart.php" method="post">
                      <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                      <input type="hidden" name="item_id" value="<?php print(h($item['item_id'])); ?>">
                    </form>
                  <?php } else { ?>
                    <p class="text-danger">現在売り切れです。</p>
                  <?php } ?>
                </figcaption>
              </figure>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
    <table class="table text-center">
      <td>
        <?php if ($prev_page === 0) { ?>
          ←前のページへ
        <?php } else { ?>
          <a class="btn btn-secondary" href="<?php print './index.php?page=' . $prev_page; ?>">←前のページへ</a>
        <?php } ?>
      </td>
      <?php for ($lp1 = 1; $lp1 <= $total_page; $lp1++) { ?>
        <td>
          <?php if ($lp1 === (int)$page) { ?>
            <a class="btn  btn-danger" href="<?php print './index.php?page=' . $lp1; ?>"><?php print $lp1; ?></a>
          <?php } else { ?>
            <a class="btn btn-secondary" href="<?php print './index.php?page=' . $lp1; ?>"><?php print $lp1; ?></a>
          <?php } ?>
        </td>
      <?php } ?>
      <td>
        <?php if ($next_page === 0) { ?>
          次のページへ→
        <?php } else { ?>
          <a class="btn btn-secondary" href="<?php print './index.php?page=' . $next_page; ?>">次のページへ→</a>
        <?php } ?>
      </td>
    </table>
  </div>

</body>

</html>