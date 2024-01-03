<?php
include('../include/connections.php');
session_start();
if (isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id']; // Use the session user_id instead of $_GET['user_id']
    $query = "SELECT * FROM User WHERE user_id = '$user_id'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0){
        $data = $result->fetch_assoc();
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $old_user_password = $_POST['old_password']; // Change the input name to 'old_password'
            $new_user_password = $_POST['new_password']; // Change the input name to 'new_password'
            $reentered_password = $_POST['reenter_password']; // Change the input name to 'reenter_password'

            // Check if the old password matches the one in the database
            if ($old_user_password == $data['user_password']) {
                // Check if the new password and the re-entered password match
                if ($new_user_password == $reentered_password) {
                    $query_update = "UPDATE User
                                    SET user_password = '$new_user_password'
                                    WHERE user_id = '$user_id'";
                    $result_update = $conn->query($query_update);

                    // Check update result
                    if ($result_update) {
                        echo "Password updated successfully";
                    } else {
                        echo "Password update failed: " . $conn->error;
                    }
                } else {
                    echo "New password and re-entered password do not match";
                }
            } else {
                echo "Old password is incorrect";
            }
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>
<body>
<form method='POST'>
    <h2>Change Password</h2>
        <div>
            <label>Old Password</label>
            <input type="password" name="old_password"> <!-- Change the input type to 'password' -->
        </div>
        <div>
            <label>New Password</label>
            <input type="password" name='new_password'> <!-- Change the input type to 'password' -->
        </div>
        <div>
            <label>Re-Enter New Password</label>
            <input type="password" name="reenter_password"> <!-- Change the input type to 'password' -->
        </div>
        <button type='submit'>Change</button>
</form>
    <a href="home.php">Cancel</a>
</body>
</html>
<?php
    } else {
        echo 'User not found';
    }

} else {
    echo 'You have to login first';
}
?>

<a href="home.php">Home</a>