<?php
require APPROOT . '/views/inc/header.php';
?>
<div class="col-md-6 mx-auto">
    <div class="postcard card border border-radius p-4 m-3 w-100 ">
        <h1>登入</h1>
        測試用帳號為admin@gmail.com
        <br>
        密碼為admin1234
        <?= flash('register_sus'); ?>
        <form action="<?= URLROOT; ?>/users/login" method="POST">
            <div class="form-group">
                <label for="email">email:<sup>*</sup></label>
                <input type="email" class="form-control <?= !empty($data['email_err']) ? 'is-invalid' : ''; ?>" name="email" value="<?= $data['email']; ?>" id="">
                <span class="invalid-feedback"><?= $data['email_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="password">password:<sup>*</sup></label>
                <input type="password" class="form-control <?= !empty($data['password_err']) ? 'is-invalid' : ''; ?>" name="password" value="<?= $data['password']; ?>" id="">
                <span class="invalid-feedback"><?= $data['password_err']; ?></span>
            </div>
            <div class="row m-3 mx-auto">
                <div class="col">
                    <input type="submit" value="登入" class="btn btn-block btn-success">
                    <a href="<?= URLROOT; ?>/users/register" class="btn btn-block btn-primary">註冊</a>
                </div>
            </div>
        </form>
    </div>

</div>