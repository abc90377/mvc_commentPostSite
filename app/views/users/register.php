<?php
require APPROOT . '/views/inc/header.php';
?>
<div class="col-md-6 mx-auto">
    <div class="postcard card border border-radius p-4 m-3 w-100 ">
        <h1>註冊</h1>
        <form action="<?= URLROOT; ?>/users/register" method="POST">
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

            <div class="form-group">
                <label for="name">暱稱:<sup>*</sup></label>
                <input type="text" class="form-control <?= !empty($data['name_err']) ? 'is-invalid' : ''; ?>" name="name" value="<?= $data['name']; ?>" placeholder="暱稱可在註冊後更改">
                <span class="invalid-feedback"><?= $data['name_err']; ?></span>
            </div>
            <div class="row m-3 mx-auto">
                <div class="col">
                    <input type="submit" value="註冊" class="btn btn-block btn-success">
                </div>
            </div>
        </form>
    </div>
</div>