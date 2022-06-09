<?php
class Page
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function addPost($data)
    {
        $this->db->query('INSERT INTO posts (`user_Id`, `post`) VALUES (:user_id, :post)');
        $this->db->bind(':post', $data['post']);
        $this->db->bind(':user_id', $data['user_id']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function replyPost($data)
    {
        $this->db->query('INSERT INTO comments (`user_Id`, `post`, `related_post`) VALUES (:user_id, :post, :related_post)');
        $this->db->bind(':post', $data['post']);
        $this->db->bind(':related_post', $data['reply_post']);
        $this->db->bind(':user_id', $data['user_id']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function editPost($data)
    {
        $this->db->query('UPDATE posts SET `post` = :post WHERE id = :id');

        $this->db->bind(':post', $data['post']);
        $this->db->bind(':id', $data['post_id']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function likePost($post_id)
    {
        $this->db->query('INSERT INTO likes (`user_id`, `post_id`) VALUES (:user_id, :post_id)');
        $this->db->bind(':post_id', $post_id);
        $this->db->bind(':user_id', $_SESSION['user_id']);
        $likedUser = $this->db->execute();

        $this->db->query('UPDATE posts SET `likes` = `likes`+1 WHERE `id` = :post_id');
        $this->db->bind(':post_id', $post_id);
        $likedCount = $this->db->execute();

        if ($likedCount && $likedUser) {
            return true;
        } else {
            return false;
        }
    }
    public function unlikePost($post_id)
    {
        $this->db->query('DELETE FROM likes WHERE `post_id`= :postId && `user_id`=:userId');
        $this->db->bind(':postId', $post_id);
        $this->db->bind(':userId', $_SESSION['user_id']);
        $unlikedUser = $this->db->execute();

        $this->db->query('UPDATE posts SET `likes` = `likes`-1 WHERE `id` = :post_id');
        $this->db->bind(':post_id', $post_id);
        $unlikedCount = $this->db->execute();

        if ($unlikedCount && $unlikedUser) {
            return true;
        } else {
            return false;
        }
    }
    public function deletePost($post_id)
    {
        $this->db->query('DELETE FROM posts WHERE id = :id');
        $this->db->bind(':id', $post_id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function showAllPost($p = 0)
    {
        $this->db->query('SELECT * FROM posts 
         ORDER BY posts.published_at DESC limit :p,4');
        $this->db->bind(':p', $p);

        $row = $this->db->resultSet();
        $posts = $this->getComments($row);
        if (!empty($_SESSION['user_id'])) {
            $posts = $this->checkPostsIsLiked($row, $_SESSION['user_id']);
        }
        
        return $posts;
    }
    public function FindMyPost($userId, $p = 0)
    {
        $this->db->query('SELECT * FROM posts 
         WHERE posts.user_id = :userId
         ORDER BY posts.published_at DESC limit :p,4');
        $this->db->bind(':p', $p);
        $this->db->bind(':userId', $userId);

        $row = $this->db->resultSet();

        $posts = $this->getComments($row);
        $posts = $this->checkPostsIsLiked($row, $userId);


        return $posts;
    }

    public function FindLikedPost($userId, $p = 0)
    {
        $this->db->query('SELECT * ,posts.id as id 
        FROM posts
         INNER JOIN likes ON posts.id = likes.post_id 
         WHERE likes.user_id = :userId
         ORDER BY posts.published_at DESC limit :p,4');
        $this->db->bind(':p', $p);
        $this->db->bind(':userId', $userId);

        $row = $this->db->resultSet();

        $posts = $this->getComments($row);
        $posts = $this->checkPostsIsLiked($row, $_SESSION['user_id']);

        return $posts;
    }

    public function getComments($posts)
    {
        foreach ($posts as $index => $post) {
            $this->db->query('SELECT * FROM comments 
            WHERE related_post = :post_id
            ');
            $this->db->bind(':post_id', $post->id);

            $comments = $this->db->resultSet();
            $posts[$index]->comments = $comments;
        }

        return $posts;
    }

    public function checkPostsIsLiked($posts, $user_id = '')
    {
        foreach ($posts as $index => $post) {
            if (!empty($user_id)) {
                $this->db->query('SELECT * FROM likes 
            WHERE post_id = :post_id && user_id =:user_id
            ');
                $this->db->bind(':post_id', $post->id);
                $this->db->bind(':user_id', $user_id);
                $is_liked = $this->db->rowCount();

                if (!empty($is_liked)) {
                    $posts[$index]->is_liked = 1;
                } else {
                    $posts[$index]->is_liked = 0;
                }
            }
        }
        return $posts;
    }
    public function findSinglePost($post, $userId)
    {
        $this->db->query('SELECT *FROM posts 
         WHERE posts.id = :post');
        $this->db->bind(':post', $post);
        $row = $this->db->single();
        if (!empty($row)) {
            $comments = $this->getSinglePostComments($post);
            $row->comments = $comments;
            if (!empty($userId)) {
                $row->is_liked = $this->checkSinglePostIsLiked($post, $userId);
            }
        }

        return $row;
    }
    public function checkSinglePostIsLiked($post, $user_id = '')
    {

            if (!empty($user_id)) {
                $this->db->query('SELECT * FROM likes 
            WHERE post_id = :post_id && user_id =:user_id
            ');
                $this->db->bind(':post_id', $post);
                $this->db->bind(':user_id', $user_id);
                $is_liked = $this->db->rowCount();

                if (!empty($is_liked)) {
                    $is_liked = 1;
                } else {
                    $is_liked = 0;
                }
            }
        
        return $is_liked;
    }
    public function getSinglePostComments($post)
    {
        $this->db->query('SELECT * FROM comments 
            INNER JOIN users ON comments.user_id = users.id
            WHERE related_post = :post_id
            ');
        $this->db->bind(':post_id', $post);

        $comments = $this->db->resultSet();

        return $comments;
    }

    public function accessCheck($post_id, $user_id)
    {
        $this->db->query('SELECT * from posts where `id` = :id && `user_id`= :user_id');
        $this->db->bind(':id', $post_id);
        $this->db->bind(':user_id', $user_id);
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function searchPost($query){
        foreach ($query as $value) {
            $query_sql[] = "`post` LIKE BINARY CONCAT('%','". $value ."', '%')";
        }
        $query_sql = implode(' AND ', $query_sql);
        $this->db->query("SELECT * FROM posts WHERE ".$query_sql ."ORDER BY published_at DESC");
        $posts = $this->db->resultSet();
        $posts = $this->getComments($posts);
        if (isset($_SESSION['user_id'])) {
            $posts = $this->checkPostsIsLiked($posts, $_SESSION['user_id']);
        }
        return $posts;
    }
}
