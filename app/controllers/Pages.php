<?php
class Pages extends Controller
{
    public function __construct()
    {
        $this->PageModel = $this->model('Page');
        $this->UserModel = $this->model('User');
    }

    public function index()
    {
        $data = [
            'posts' => $this->PageModel->showAllPost(),
            'controller' => 'index',
        ];
        $this->view('pages/index', $data);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'post' => trim($_POST['post']),
                'user_id' => $_SESSION['user_id'],
            ];
            if ($this->PageModel->addPost($data)) {
                flash('add_post_sus', '發文成功');
                redirect('pages/index');
            } else {
                die('發文失敗，請聯絡系統管理員 ');
            };
        }
    }
    public function reply()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'post' => trim($_POST['post']),
                'reply_post' => $_POST['reply_post'],
                'user_id' => $_SESSION['user_id'],
            ];

            if ($this->PageModel->replyPost($data)) {
                flash('reply_post_sus', '留言成功');
                redirect('pages/post/' . $data['reply_post']);
            } else {
                die('留言失敗，請聯絡系統管理員 ');
            };
        }
    }

    public function edit($post_id)
    {
        if ($this->PageModel->accessCheck($post_id, $_SESSION['user_id'])) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data = [
                    'post_id' => $post_id,
                    'post' => trim($_POST['new_post']),
                ];

                if ($this->PageModel->editPost($data)) {
                    echo 'sus';
                } else {
                    echo 'fail';
                };
            }
        }
    }
    public function delete($post_id)
    {
        if ($this->PageModel->accessCheck($post_id, $_SESSION['user_id'])) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($this->PageModel->deletePost($post_id)) {
                    echo 'sus';
                } else {
                    echo 'fail';
                };
            }
        }
    }
    public function post($post_id)
    {
        $data = [
            'post' => $this->PageModel->findSinglePost($post_id,(isset($_SESSION['user_id'])?$_SESSION['user_id']:'')),
        ];
        if (!empty($data['post'])) {
            $this->view('pages/post', $data);
        } else {
            die('發生錯誤，請聯繫系統管理員');
        }
    }
    public function load($new_page = 0, $limit = 4)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            switch ($_POST['controller']) {
                case 'index':
                    $posts = $this->PageModel->showAllPost($limit * ($new_page - 1));
                    break;
                case 'mypost':
                    $posts = $this->PageModel->FindMyPost($_SESSION['user_id'], $limit * ($new_page - 1));
                    break;
                case 'likes':
                    $posts = $this->PageModel->FindLikedPost($_SESSION['user_id'], $limit * ($new_page - 1));
                    break;

                default:
                    # code...
                    break;
            }
            $data = [
                'posts' => $posts,
            ];
            if (!empty($data['posts'])) {
                $this->view('pages/load', $data);
            }
        }
    }
    public function mypost()
    {
        if (isset($_SESSION['user_id'])) {
            $data = [
                'posts' => $this->PageModel->FindMyPost($_SESSION['user_id']),
                'controller' => 'mypost',
            ];
            $this->view('pages/index', $data);
        } else {
            redirect('/users/login');
        }
    }
    public function likes()
    {
        if (isset($_SESSION['user_id'])) {
            $data = [
                'posts' => $this->PageModel->FindLikedPost($_SESSION['user_id']),
                'controller' => 'likes',
            ];

            $this->view('pages/index', $data);
        } else {
            redirect('/users/login');
        }
    }

    public function likePost($post_id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['user_id']) {
            if ($this->PageModel->likePost($post_id)) {
                echo 'sus';
            } else {
                echo 'fail';
            };
        }
    }
    public function unlikePost($post_id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['user_id']) {
            if ($this->PageModel->unlikePost($post_id)) {
                echo 'sus';
            } else {
                echo 'fail';
            };
        }
    }

    public function search()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $querys=explode(' ', $_GET['q']);
            $data = [
                'controller'=>'search',
                'posts'=>$this->PageModel->searchPost($querys),
                'querys'=>$querys,
            ];
            $this->view('pages/index', $data);
        }
    }
}
