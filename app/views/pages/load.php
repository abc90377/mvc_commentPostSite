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