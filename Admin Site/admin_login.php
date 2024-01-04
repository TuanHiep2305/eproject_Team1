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
    <title>Document</title>
</head>
<body>
    <form action="" method='POST'>
        <h2>Admin Login</h2>
        <div>
            <label for="">Username</label>
            <input type="text" name='admin_username'>
        </div>
        <div>
            <label for="">Password</label>
            <input type="password" name='admin_password'>
        </div>
        <button type='submit' name='login'>Login</button>
    </form>
</body>
</html>