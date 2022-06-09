<nav class="navbar navbar-light sticky-top ">
    <div class="container container-fluid">

        <a class="navbar-brand" href="<?= URLROOT; ?>/pages/index">
            <i class="fa fa-solid fa-leaf"></i>
            <span>QuickThought!</span>
        </a>
        <div class="d-flex ">
            <div class="m-2">
                <form class="input-group" action="<?= URLROOT; ?>/pages/search" method="GET">
                    <input type="text" name="q" class="form-control" placeholder="搜尋全站" aria-label="搜尋全站" aria-describedby="basic-addon2">
                    <button class="input-group-text" type="submit" id="button-addon2">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
            <div  class="m-2">
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <a href="<?= URLROOT; ?>/users/logout" class="btn rounded-pill btn-outline-primary">登出</a>
                <?php else : ?>
                    <a href="<?= URLROOT; ?>/users/login" class="btn rounded-pill btn-outline-primary">登入</a>
                <?php endif; ?>
            </div>
        </div>





    </div>

</nav>