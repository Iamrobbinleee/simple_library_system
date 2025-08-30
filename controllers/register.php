<?php
    require_once '../config/database.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['username']) && isset($_POST['password'])
        && isset($_POST['name']) && isset($_POST['email'])){
            $name = $_POST['name'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $created_at = date("Y-m-d H:i:s");

            if(empty($username) || empty($password) || empty($email) || empty($name)){
                echo 'Name/Username/Email/Password is required.';
                exit();
            }

            $check_user = $connect->prepare('SELECT * FROM users where username = ? || email = ?');
            
            if($check_user === false) {
                die('Preparation Failed: ' . $connect->error);
            }
            
            $check_user->bind_param('ss', $username, $email);
            $check_user->execute();
            $result = $check_user->get_result();

            if($result->num_rows > 0){
                echo "Account already existed.";
                exit();
            }

            $register_user = $connect->prepare("INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)");
            if($register_user === false) {
                die('Preparation Failed: ' . $connect->error);
            }

            $register_user->bind_param('sssi', $name, $username, $email, $hashed_password);
            $register_user->execute();

            if($register_user === false){
                echo 'Failed to register user.';
                exit();
            }
            
            $res = $connect->insert_id;
            $user_registered = $connect->prepare('SELECT * FROM users WHERE id = ?');
            if($user_registered === false) {
                die('Preparation Failed: ' . $connect->error);
            }
            $user_registered->bind_param('i', $res);
            $user_registered->execute();
            $response = $user_registered->get_result();

            if($response->num_rows > 0){
                session_start();
                $user_data = $response->fetch_assoc();

                $_SESSION['user_id'] = $user_data['id'];
                $_SESSION['username'] = $user_data['username'];
                header('Location: /views/dashboard.php');
                exit();
            } else {
                echo 'User Account does not existed.';
                exit();
            }
        }
    }
?>