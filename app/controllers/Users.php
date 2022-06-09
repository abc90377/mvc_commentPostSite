<?php
class Users extends Controller
{
    public function __construct()
    {
        $this->UserModel = $this->model('User');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
            ];

            if (empty($data['email'])) {
                $data['email_err'] = 'please enter email';
            }

            if (empty($data['password'])) {
                $data['password_err'] = 'please enter password';
            }

            //Check if User exit
            if (!$this->UserModel->findUserByEmail($data['email'])) {
                $data['email_err'] = 'no user found';
            }


            if (empty($data['email_err']) && empty($data['password_err'])) {
                //check and set login user
                $loggedInUser = $this->UserModel->login($data['email'], $data['password']);
                if ($loggedInUser) {
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'pw incorrect';
                    $this->view('users/login', $data);
                }
            } else {
                $this->view('users/login', $data);
            }
        } else {
            $data = [
                'email' => 'admin@gmail.com',
                'password' => 'admin1234',
            ];

            $this->view('users/login', $data);

        }
        //$this->view('users/login', $data);
    }
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'email' => trim($_POST['email']),
                'name' => trim($_POST['name']),
                'password' => trim($_POST['password']),
            ];

            if (empty($data['email'])) {
                $data['email_err'] = 'please enter email';
            }

            if (empty($data['name'])) {
                $data['name_err'] = 'please enter name';
            }

            if (empty($data['password'])) {
                $data['password_err'] = 'please enter password';
            } elseif(strlen($data['password']) < 6){
                $data['password_err'] = '密碼至少須由6個字組成';
            }
            if ($this->UserModel->findUserByEmail($data['email'])) {
                $data['email_err'] = '此email已被註冊過';
            }
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err'])) {

                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if ($this->UserModel->register($data)) {
                    flash('register_sus', '註冊成功!請輸入登入資料');
                    redirect('/users/login');
                } else {
                    die('註冊失敗，請聯絡系統管理員 ');
                };
            } else {
                $this->view('users/register', $data);
            }
        } else {
            $data = [
                'email' => '',
                'password' => '',
                'name' => '',
            ];

            $this->view('users/register', $data);

        }
    }

    public function createUserSession($loggedInUser){
        $_SESSION['user_id'] = $loggedInUser->id;
        redirect('pages/index');
    }

    public function logout(){
        unset($_SESSION['user_id']);
        redirect('pages/index');
    }
}
