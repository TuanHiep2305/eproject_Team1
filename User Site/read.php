<?php
include('../include/connections.php');
session_start();
if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
    $query = "SELECT *
          FROM Post
          INNER JOIN Category ON Post.category_id = Category.category_id
          LEFT JOIN Admin ON Post.admin_id = Admin.admin_id
          LEFT JOIN User ON Post.user_id = User.user_id
          WHERE Post.post_id = '{$post_id}'";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo "<div class='container'>";
        echo "<div class='row'>";
        echo "<div class='col-md-4'>";
        echo "<p>" . $data['category_name'] . "</p>"; // Category displayed
        echo "<p>" . $data['post_title'] . "</p>"; // Title displayed

        // Display the nickname if it exists
        echo !empty($data['admin_id']) ?
        "<p>Nickname: " . $data['admin_nickname'] . "</p>" :
        "<p>Nickname: " . $data['user_nickname'] . "</p>";

        echo "<p>" . date('l, d/m/Y - H:i', strtotime($data['upload_date'])) . "</p>"; // Date displayed
        $upload_date = strtotime($data['upload_date']);
        $current_date = strtotime(date('Y-m-d H:i:s'));
        $time_diff = $current_date - $upload_date;
        $days_diff = floor($time_diff / (60 * 60 * 24));
        $time_ago = ($days_diff > 0) ? $days_diff . " days ago" : "Today";
        echo "<p>Posted " . $time_ago . " at " . date('H:i', $upload_date) . "</p>";
        
        echo "<img src='" . $data['post_image'] . "' alt='' width='100%'>"; // Image displayed
        echo "<p>" . $data['post_content'] . "</p>"; // Content displayed (shortened)
        echo "</div>";
        echo "</div>";
        echo "</div>";

        // Display comments
        $comments_query = "SELECT Comment.*, User.user_nickname AS user_nickname, Admin.admin_nickname AS admin_nickname
                   FROM Comment LEFT JOIN User ON Comment.user_id = User.user_id
                   LEFT JOIN Admin ON Comment.admin_id = Admin.admin_id
                   WHERE Comment.post_id = '{$post_id}'
                   ORDER BY Comment.comment_date DESC";
        $comments_result = $conn->query($comments_query);
        if ($comments_result->num_rows > 0) {
            echo "<div class='container'>";
            echo "<div class='row'>";
            echo "<div class='col-md-4'>";
            echo "<h3>Comments</h3>";
            while ($comment_data = $comments_result->fetch_assoc()) {
                echo "<p>" . $comment_data['comment_content'] . "</p>";
                
                if ($comment_data['user_nickname']) {
                    echo "<p>Posted by: " . $comment_data['user_nickname'] . "</p>";
                } else if ($comment_data['admin_nickname']) {
                    echo "<p>Posted by: " . $comment_data['admin_nickname'] . "</p>";
                }
                
                echo "<p>Date: " . date('l, d/m/Y - H:i', strtotime($comment_data['comment_date'])) . "</p>";
                $comment_date = strtotime($comment_data['comment_date']);
                $current_date = strtotime(date('Y-m-d H:i:s'));
                $time_diff = $current_date - $comment_date;
                $days_diff = floor($time_diff / (60 * 60 * 24));
                $time_ago = ($days_diff > 0) ? $days_diff . " days ago" : "Today";
                echo "<p>Date: " . $time_ago . " at " . date('H:i', $comment_date) . "</p>";
            }
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }

        // Comment form
        if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
            // User or admin is logged in
            
            echo "<form method='POST'>"; // Submit the form to the current page
            echo "<textarea name='comment_content' placeholder='Enter your comment' required></textarea>";
            echo "<br>";
            echo "<button type='submit'>Comment</button>";
            echo "</form>";
        
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_content'])) {
                $comment_content = $_POST['comment_content'];
                $post_id = $_GET['post_id'];
                
                if (isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];
                    $query = "INSERT INTO Comment (comment_content, user_id, post_id) 
                              VALUES ('$comment_content', '$user_id', '$post_id')";
                } else if (isset($_SESSION['admin_id'])) {
                    $admin_id = $_SESSION['admin_id'];
                    $query = "INSERT INTO Comment (comment_content, admin_id, post_id) 
                              VALUES ('$comment_content', '$admin_id', '$post_id')";
                }
                
                $result = $conn->query($query);
        
                if ($result) {
                    header("Location: read.php?post_id=$post_id");
                } else {
                    echo "<div>Error occurred while submitting the comment.</div>";
                }
            }
        } else {
            // User or admin is not logged in
            echo "<div>You have to log in first to submit a comment.</div>";
        }
    }
}
?>

<a href="home.php">Home</a>