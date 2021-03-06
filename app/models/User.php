<?php
class User{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        
    }

    //Find user by email
    public function findUserByEmail($email){
        $this->db->query('SELECT * from users where `email` = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        if($this->db->rowCount()>0){
            return true;
        }else {
            return false;
        }
    }

    public function login($email, $password){
        $this->db->query('SELECT * from users where `email` = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        $hashed_password = $row->password;
        if(password_verify($password, $hashed_password)){
            return $row;
        }else{
            return false;
        }
    }

    public function register($data){
        $this->db->query('INSERT INTO users (`email`, `name`, `password`) VALUES (:email, :name, :password)');
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':password', $data['password']);
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }
    public function getUsernameById($user_id){
        $this->db->query('SELECT `name` from users where `id` = :user_id');
        $this->db->bind(':user_id', $user_id);
        $row = $this->db->single();
        if(!empty($row)){
            return $row->name;
        }else{
            return '';
        }
    }

}