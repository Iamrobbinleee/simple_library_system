<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Login Page</title>
</head>
<body>
<?php 
    session_start();
    if(isset($_SESSION["user_id"])){
        header("Location: /views/dashboard.php");
        exit();
    }
?>

<div class="container">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6" style="margin-top: 100px;">
        <form action="/controllers/login.php" method="POST">
            <div class="col-md-12">
                <label class="form-label" for="username">Username:</label>
                <input class="form-control" type="text" name="username" required>
            </div>
            <br>
            <div class="col-md-12">
                <label class="form-label" for="password">Password:</label>
                <input class="form-control" type="password" name="password" required>
            </div>
            <br>
            <div style="text-align: center; gap: 15px;">
                <button class="btn btn-primary btn-md" type="submit">Login</button>
                <a href="/views/register_page.php">Register now!</a>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</body>
</html>