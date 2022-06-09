<?php
require APPROOT . '/views/inc/header.php';
?>

<div class="container">
    <div class="m-4">
        <a href="<?= URLROOT; ?>/pages/" class="text-decoration-none">
            <i class="fa fa-solid fa-angle-left iButton" style="font-size: 30px;"></i>
        </a>
    </div>
    <?= flash('reply_post_sus'); ?>

    <div id="pagecontent">
        <div class="card border border-radius p-4 m-3 col-lg-6 col-sm-10 mx-auto postcard" id="PostCard<?= $data['post']->id; ?>">
            <div class="d-flex align-items-center">
                <div class="col-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height:70px;background:#ccc;">
                        <i class="fa fa-solid fa-user" style="font-size:30px;"></i>
                    </div>
                </div>
                <div class="col-10 ">
                    <div id="Post<?= $data['post']->id; ?>">
                        <div class="m-2">
                            <?= getPostText($data['post']->post, (!empty($data['querys']) ? $data['querys'] : array())); ?>
                        </div>
                        <p class="text-muted  text-end ">
                            written by <?= getUsernameById($data['post']->user_id); ?> on <?= $data['post']->published_at; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="toolbox d-flex  justify-content-around">
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <div id="likePost<?= $data['post']->id; ?>" onclick="likePost(<?= $data['post']->id; ?>)" class="<?= $data['post']->is_liked ? 'liked' : ''; ?>">
                        <i id="likeIcon<?= $data['post']->id; ?>" class="fa fa-solid fa-thumbs-up iButton"></i>
                        <span id="likeCount<?= $data['post']->id; ?>"><?= $data['post']->likes; ?></span>
                    </div>
                    <div onclick="commentToggle(<?= $data['post']->id; ?>)">
                        <i class="fa fa-solid fa-comment-dots iButton"></i>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id']) && $data['post']->user_id == $_SESSION['user_id']) : ?>
                    <div id="editHide<?= $data['post']->id; ?>">
                        <a onclick="showEditBlock(<?= $data['post']->id; ?>)">
                            <i class="fa fa-solid fa-pen iButton"></i>
                        </a>
                    </div>

                    <div onclick="deletePost(<?= $data['post']->id; ?>)">
                        <i class="fa fa-solid fa-trash iButton"></i>
                    </div>
                <?php endif; ?>

            </div>

            <div  id="comment<?= $data['post']->id; ?>">
                <?= (empty($data['post']->comments) && !isset($_SESSION['user_id'])) ? '' : '<hr>'; ?>
                <?php foreach ($data['post']->comments as $comment) : ?>
                    <p><a href="#" class="<?= $data['post']->user_id == $comment->user_id ? 'fw-bolder' : ''; ?> text-body text-decoration-none">
                            <?= getUsernameById($comment->user_id); ?></a> :
                        <?= htmlspecialchars($comment->post, ENT_QUOTES)  ?></p>
                <?php endforeach; ?>

                <?php if (isset($_SESSION['user_id'])) : ?>
                    <form action="<?= URLROOT; ?>/pages/reply" method="POST">
                        <div class="input-group mb-3">
                            <input type="hidden" name="reply_post" value="<?= $data['post']->id; ?>">
                            <input type="text" name="post" class="form-control" placeholder="輸入留言" aria-label="輸入留言" aria-describedby="button-addon2" required="required">
                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Submit</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>

            <div id="editShow<?= $data['post']->id; ?>" style="display: none;">
                <?= (empty($data['post']->comments) && !isset($_SESSION['user_id'])) ? '' : '<hr>'; ?>
                <textarea id="editPost<?= $data['post']->id; ?>" class="editpost" cols="30" rows="10" required="required"><?= htmlspecialchars($data['post']->post, ENT_QUOTES); ?></textarea>
                <a class="btn btn-primary" onclick="edit(<?= $data['post']->id; ?>)">確定編輯</a>
                <a class="btn btn-primary" onclick="editCancel(<?= $data['post']->id; ?>)">放棄編輯</a>
            </div>

        </div>

    </div>
</div>
<script>
    currentPage = 1;

    function showEditBlock($postId) {
        originalPost = document.getElementById("editPost" + $postId);
        $("#editPost" + $postId).val(originalPost.defaultValue);
        $("#editShow" + $postId).toggle();
    }

    function editCancel($postId) {
        originalPost = document.getElementById("editPost" + $postId);
        $("#editPost" + $postId).val(originalPost.defaultValue);
        $("#editShow" + $postId).toggle();
    }

    function edit($postId) {
        new_post = $("#editPost" + $postId).val();
        $.post("<?= URLROOT; ?>/pages/edit/" + $postId, {
            'new_post': new_post
        }, function(result) {
            if (result == 'sus') {
                alert('編輯成功');
                $("#editHide" + $postId).toggle();
                $("#editShow" + $postId).toggle();
                $("#Post" + $postId).html(new_post);

            } else {
                alert('編輯失敗，請聯絡系統管理員');
            }
        });
    }

    function deletePost($postId) {
        check = confirm('確定要刪除此篇文章嗎?');
        if (check) {
            $.post("<?= URLROOT; ?>/pages/delete/" + $postId, function(result) {
                if (result == 'sus') {
                    
                    alert('刪除成功');

                    location.href='<?= URLROOT; ?>/pages/index'
                } else {
                    alert('刪除失敗，請聯絡系統管理員');
                }
            });
        }
    }

    function commentToggle($postId) {
        $("#comment" + $postId).toggle();
    }

    var canClicked = 1;

    function likePost($postId) {
        if (canClicked == 1) {
            canClicked = 0;
            //設定不可點擊'

            if (!$("#likePost" + $postId).hasClass('liked')) {

                $.post("<?= URLROOT; ?>/pages/likePost/" + $postId, function(result) {
                    if (result == 'sus') {
                        count = $("#likeCount" + $postId).text();
                        count++;
                        $("#likeCount" + $postId).text(count);
                        $("#likePost" + $postId).addClass('liked');

                    } else {
                        alert('失敗，請聯絡系統管理員');
                    }
                    canClicked = 1;
                    //解除設定不可點擊
                });

            } else {
                count = $("#likeCount" + $postId).text();
                count--;
                $.post("<?= URLROOT; ?>/pages/unlikePost/" + $postId, function(result) {
                    if (result == 'sus') {
                        $("#likeCount" + $postId).text(count);
                        $("#likePost" + $postId).removeClass('liked');
                    } else {
                        alert('失敗，請聯絡系統管理員');
                    }
                    canClicked = 1;
                    //解除設定不可點擊

                });

            }
        }
    }
</script>