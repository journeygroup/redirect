<?php $this->layout('layouts/default', ['title' => 'Redirects']); ?>

<div class="login">
  <form action="/login" method="post">
    <?php if (isset($message)) : ?>
    <div class="form-element">
      <div class="login-message error"><?= $this->e($message) ?></div>
    </div>
    <?php endif; ?>

    <div class="form-element">
      <label for="username">Username</label>
      <input type="text" name="username" id="username">
    </div>

    <div class="form-element">
      <label for="password">Password</label>
      <input type="password" name="password" id="password">
    </div>

    <div class="form-element">
      <button type="submit" class="button">Login</button>
    </div>
  </form>
</div>
