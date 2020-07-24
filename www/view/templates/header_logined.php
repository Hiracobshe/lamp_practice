<header>
  <nav class="navbar navbar-expand-sm navbar-light bg-light">
    <a class="navbar-brand" href="<?php print(HOME_URL);?>">Market</a>
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#headerNav" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="ナビゲーションの切替">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="headerNav">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?php print(CART_URL);?>">カート</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php print(LOGOUT_URL);?>">ログアウト</a>
        </li>
        <!-- // 購入履歴 -->
        <li class="nav-item">
          <!-- GETメソッドでデータ送信 -->
          <form method="get" enctype="multipart/form-data" action="./history.php">
            <a class="nav-link" href="<?php print(HISTORY_URL) . '?user_id=' . $user['user_id']; ?>">購入履歴</a>
            <input type="hidden" name="user_id" value="<?php print $user['user_id']; ?>">
          </form>
        </li>
        <?php if(is_admin($user)){ ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php print(ADMIN_URL);?>">管理</a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </nav>
  <p>ようこそ、<?php print($user['name']); ?>さん。</p>
</header>