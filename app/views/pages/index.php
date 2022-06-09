<?php
require APPROOT . '/views/inc/header.php';
?>
<div class="container">

    <?php if (isset($_SESSION['user_id'])) : ?>
        <?= flash('add_post_sus'); ?>
        <div class="d-flex col-lg-6 col-md-10 col-sm-12 mx-auto">
            <form action="<?= URLROOT; ?>/pages/add" method="POST">
                <div class="form-row col-4 mb-3">
                    <div class="d-flex align-self-center align-items-center">
                        <div>
                            <textarea contenteditable="true" name="post" id="newpost" cols="50" rows="10" placeholder="Share your thought" required="required"></textarea>
                        </div>
                        <div class="m-2">
                            <button type="submit" class="btn btn-primary mb-2 float-righ">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>


    <?php endif; ?>

    <div class="d-flex justify-content-around m-3 col-lg-6 col-sm-10 mx-auto">


        <a href="<?= URLROOT; ?>/pages/index" class="btn btn-group <?= ($data['controller'] == 'index') ? 'nowBar' : ''; ?>">
            ALL
        </a>
        <a href="<?= URLROOT; ?>/pages/mypost" class="btn btn-group <?= ($data['controller'] == 'mypost') ? 'nowBar' : ''; ?>">MY POSTS</a>
        <a href="<?= URLROOT; ?>/pages/likes" class="btn btn-group <?= ($data['controller'] == 'likes') ? 'nowBar' : ''; ?>">LIKES</a>


    </div>

    <div id="pagecontent">
        <?php if ($data['controller'] == 'search') : ?>
            <div class="card border border-radius p-4 m-3 col-lg-6 col-sm-10 postcard mx-auto">目前搜尋的關鍵字為: <?= implode('', $data['querys']) ?></div>
        <?php endif; ?>
        <?php if (empty($data['posts'])) : ?>
            <p class="text-center">目前還沒有貼文</p>
        <?php endif; ?>
        <?php foreach ($data['posts'] as $post) : ?>
            <div class="card border border-radius p-4 m-3 col-lg-6 col-sm-10 mx-auto postcard" id="PostCard<?= $post->id; ?>">
                <div class="d-flex align-items-center">
                    <div class="col-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height:70px;background:#ccc;">
                            <i class="fa fa-solid fa-user" style="font-size:30px;"></i>
                        </div>
                    </div>
                    <div class="col-10 ">
                        <div class=" text-end">
                            <a href="<?= URLROOT; ?>/pages/post/<?= $post->id; ?>" class="text-body text-decoration-none"><i class="fa fa-solid fa-arrow-right iButton"></i></a>
                        </div>
                        <div id="Post<?= $post->id; ?>">
                            <div class="m-2">
                                <?= getPostText($post->post, (!empty($data['querys']) ? $data['querys'] : array())); ?>
                            </div>
                            <p class="text-muted  text-end ">
                                written by <?= getUsernameById($post->user_id); ?> on <?= $post->published_at; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="toolbox d-flex  justify-content-around">
                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <div id="likePost<?= $post->id; ?>" onclick="likePost(<?= $post->id; ?>)" class="<?= $post->is_liked ? 'liked' : ''; ?>">
                            <i id="likeIcon<?= $post->id; ?>" class="fa fa-solid fa-thumbs-up iButton"></i>
                            <span id="likeCount<?= $post->id; ?>"><?= $post->likes; ?></span>
                        </div>
                        <div onclick="commentToggle(<?= $post->id; ?>)">
                            <i class="fa fa-solid fa-comment-dots iButton"></i>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_id']) && $post->user_id == $_SESSION['user_id']) : ?>
                        <div id="editHide<?= $post->id; ?>">
                            <a onclick="showEditBlock(<?= $post->id; ?>)">
                                <i class="fa fa-solid fa-pen iButton"></i>
                            </a>
                        </div>

                        <div onclick="deletePost(<?= $post->id; ?>)">
                            <i class="fa fa-solid fa-trash iButton"></i>
                        </div>
                    <?php endif; ?>

                </div>

                <div style="display:none;" id="comment<?= $post->id; ?>">
                    <?= (empty($post->comments) && !isset($_SESSION['user_id'])) ? '' : '<hr>'; ?>
                    <?php foreach ($post->comments as $comment) : ?>
                        <p><a href="#" class="<?= $post->user_id == $comment->user_id ? 'fw-bolder' : ''; ?> text-body text-decoration-none">
                                <?= getUsernameById($comment->user_id); ?></a> :
                            <?= htmlspecialchars($comment->post, ENT_QUOTES)  ?></p>
                    <?php endforeach; ?>

                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <form action="<?= URLROOT; ?>/pages/reply" method="POST">
                            <div class="input-group mb-3">
                                <input type="hidden" name="reply_post" value="<?= $post->id; ?>">
                                <input type="text" name="post" class="form-control" placeholder="輸入留言" aria-label="輸入留言" aria-describedby="button-addon2" required="required">
                                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Submit</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>

                <div id="editShow<?= $post->id; ?>" style="display: none;">
                    <?= (empty($post->comments) && !isset($_SESSION['user_id'])) ? '' : '<hr>'; ?>
                    <textarea id="editPost<?= $post->id; ?>" class="editpost" cols="30" rows="10" required="required"><?= htmlspecialchars($post->post, ENT_QUOTES); ?></textarea>
                    <a class="btn btn-primary" onclick="edit(<?= $post->id; ?>)">確定編輯</a>
                    <a class="btn btn-primary" onclick="editCancel(<?= $post->id; ?>)">放棄編輯</a>
                </div>

            </div>

        <?php endforeach; ?>
    </div>
    <hr>
    <?php if ($data['controller'] != 'search') : ?>
        <div id="loadMore" onclick="loadMore()" class="text-center">
            <p><b>載入更多......</b></p>
        </div>
        <div id="postEnd" style="display: none;" class="text-center">
            <p><b>已經沒有更多貼文了</b></p>
        </div>
    <?php endif; ?>
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
                    $("#PostCard" + $postId).hide();
                    alert('刪除成功');
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

    function loadMore() {
        newpage = currentPage + 1;
        controller = '<?= $data['controller']; ?>';

        $.post("<?= URLROOT; ?>/pages/load/" + newpage, {
            'controller': controller
        }, function(result) {
            if (result) {
                $("#pagecontent").append(result);
                currentPage++
            } else {
                $("#loadMore").hide();
                $("#postEnd").show();
            }
        });
    }
</script>