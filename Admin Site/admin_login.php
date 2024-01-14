<?php
include('../include/connections.php');
session_start();
    if (isset($_POST['login'])){
        $admin_username = $_POST['admin_username'];
        $admin_password = $_POST['admin_password'];
        
        $query = "SELECT * FROM Admin WHERE admin_username = '$admin_username' AND admin_password = '$admin_password'";
        $result = $conn->query($query);

        if ($result == TRUE && $result->num_rows > 0){
            $data = $result->fetch_assoc();
            $_SESSION['admin_id'] = $data['admin_id'];
            $_SESSION['admin_nickname'] = $data['admin_nickname'];
            
            header('Location: home.php');
        }else {
            echo 'Thất bại';
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <form action="" method='POST' class="col-md-6 offset-md-3 bg-light p-4">
        <h2 class="mb-4">Admin Login</h2>
        <div class="form-group">
            <label for="admin_username">Username</label>
            <input type="text" name='admin_username' class="form-control">
        </div>
        <div class="form-group">
            <label for="admin_password">Password</label>
            <input type="password" name='admin_password' class="form-control">
        </div>
        <button type='submit' name='login' class="btn btn-primary">Login</button>
    </form>
</body>
</html>