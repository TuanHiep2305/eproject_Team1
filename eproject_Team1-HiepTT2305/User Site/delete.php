<?php
include('../include/connections.php');
session_start();
if (isset($_SESSION['user_id'])){
    $post_id = $_GET['post_id'];
    $query = "DELETE FROM Post WHERE post_id='$post_id'";
    $result = $conn->query($query);

    if ($result == TRUE){
        header('Location: home.php');
    }

}else {
    echo 'You have to login first !!!'. '<br>';
}

?>

<a href="home.php">Home</a>