<?php
include('../include/connections.php');

function random_password($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $random_strings = '';

    for ($i = 0; $i < $length; $i++) {
        $random_strings .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $random_strings;
}

if (isset($_POST['submit'])){
    $user_email = $_POST['user_email'];
    $user_username = $_POST['user_username'];

    $query = "SELECT * FROM User WHERE user_username = '$user_username' AND user_email = '$user_email'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();

        $new_password = random_password();
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the user's hashed password in the database
        $update_query = "UPDATE User SET user_password = '$hashed_password' WHERE user_username = '$user_username' AND user_email = '$user_email'";
        $update_result = $conn->query($update_query);

        if ($update_result) {
            echo 'New Password: ' . $new_password;
        } else {
            echo 'Failed to reset password.';
        }
    } else {
        echo 'Failed to reset password. Invalid username or email.';
    }
}
?>

<form method="POST">
    <div>
        <label>UserName</label>
        <input type="text" name='user_username' required>
    </div>
    <div>
        <label>Email address:</label>
        <input type="email" name="user_email" required>
    </div>
    <button type="submit" name="submit">Reset Password</button>
</form>
<a href="user_login.php">Login</a>