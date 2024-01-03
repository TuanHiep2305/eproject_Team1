<?php
include('../include/connections.php');
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_nickname'])) {
    $user_id = $_SESSION['user_id'];

    $query = "SELECT * FROM User WHERE user_id = $user_id";
    $result = $conn->query($query);

    if ($result == TRUE && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fb_title = $_POST['fb_title'];
            $fb_content = $_POST['fb_content'];
            $user_id = $_SESSION['user_id'];

            $query_feedback = "INSERT INTO Feedback (fb_title, fb_content, user_id)
                               VALUES ('$fb_title', '$fb_content', '$user_id')";
            $result_feedback = $conn->query($query_feedback);

            if ($result_feedback == TRUE) {
                echo 'Feedback successfully' . '<br>';
            } else {
                echo 'Feedback Failed' . '<br>';
            }
        }
    }
} else {
    echo 'You have to login to leave feedback' . '<br>';
    echo '<a href="home.php">Home</a>';
    exit;
}
?>

<form method='POST'>
    <h2>Feedback</h2>
    <?php
    // Display the username only if the user is logged in
    if (isset($_SESSION['user_nickname'])) {
        echo "<h4>Username: " . $_SESSION['user_nickname'] . "</h4>";
    }
    ?>
    <div>
        <label>Feedback Title</label>
        <input type="text" name='fb_title'>
    </div>
    <div>
        <label>Feedback Content</label>
        <textarea name="fb_content" cols="30" rows="10"></textarea>
        <!-- <input type="text" name='fb_content'> -->
    </div>
    <button type='submit'>Submit</button>
    <a href="home.php">HOME</a>
</form>