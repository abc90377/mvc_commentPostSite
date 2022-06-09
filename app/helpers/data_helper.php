<?php
function getUsernameById($user_id)
{
    $user = new User();
    return htmlspecialchars($user->getUsernameById($user_id), ENT_QUOTES);
}
function getPostText($post, $querys = array())
{
    $post = htmlspecialchars($post, ENT_QUOTES);

    if (!empty($querys)) {
        foreach ($querys as $query) {
            $querys_style[$query] = "<b class='highlight'>" . $query . "</b>";
        }
        $post = strtr($post, $querys_style);
    }

    $post = str_replace(PHP_EOL, '<br>', $post);

    return $post;
}
