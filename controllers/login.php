<?php
    require_once '../config/database.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['username']) && isset($_POST['password'])){
            $username = $_POST['username'];
            $password = $_POST['password'];

            if(empty($username) || empty($password)){
                echo 'Username/Password is required.';
                exit();
            }

            $user_login = $connect->prepare('SELECT * FROM users where username = ?');

            if($user_login === false) {
                die('Preparation Failed: ' . $connect->error);
            }

            $user_login->bind_param('s', $username);
            $user_login->execute();
            $result = $user_login->get_result();

            if($result->num_rows > 0){
                session_start();
                $user_data = $result->fetch_assoc();

                if(password_verify($password, $user_data['password'])){
                    $_SESSION['user_id'] = $user_data['id'];
                    $_SESSION['username'] = $user_data['username'];
                    // echo 'Login Successfuly!';
                    header('Location: /views/dashboard.php');
                    exit();
                } else {
                    echo 'Incorrect Password.';
                    exit();
                }

                
            } else {
                echo 'Account does not existed.';
                exit();
            }
        }
    }
?>