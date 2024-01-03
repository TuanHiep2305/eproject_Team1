<?php
include('../include/connections.php');
session_start();
if (isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM User WHERE user_id = '$user_id'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0){
        $data = $result->fetch_assoc();
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $new_user_username = $_POST['user_username'];
            $new_user_email = $_POST['user_email'];
            $new_user_nickname = $_POST['user_nickname'];
            
            $query_update = "UPDATE User
                            SET user_username = '$new_user_username', 
                                user_email = '$new_user_email',
                                user_nickname = '$new_user_nickname'  
                            WHERE user_id = '$user_id'";
            $result_update = $conn->query($query_update);

            //Check update result
            if ($result_update) {
                echo "Updated successfully";
            } else {
                echo "Update failed: " . $conn->error;
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
    <form method='POST'>
        <h2>Change Information</h2>
            <div>
                <label>Username</label>
                <input type="text" name="user_username" value='<?php echo $data['user_username']; ?>'>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name='user_email' value='<?php echo $data['user_email'];?>'>
            </div>
            <div>
                <label>Nickname</label>
                <input type="text" name="user_nickname" value="<?php echo $data['user_nickname']; ?>">
            </div>
            <button type='submit'>Change</button>
    </form>
        <a href="home.php">Cancel</a>
    </body>
    </html>
<?php
    }else {
        echo 'Update failed';
    }

}else {
    echo 'You have to Login first !!!'. '<br>';
}
?>

<a href="home.php">Home</a>