<?php
// Connect to the database
include('../include/connections.php');
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_nickname'])){

    echo '<div>
    <a href="home.php">Home</a>
    <a href="business.php">Business</a>
    <a href="technology.php">Technology</a>
    <a href="sports.php">Sports</a>
    <a href="beauty.php">Beauty</a>
    <a href="sociaty.php">Sociaty</a>  
        </div>';

    echo '<div> 
    <a href="create.php">Create a new Post</a>
    <a href="admin_logout.php">Logout</a>
    <a href="response.php">Response Feedback</a>
        </div>';
        
    $admin_id = $_SESSION['admin_id'];

    $query = "SELECT * FROM Admin WHERE admin_id = $admin_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo '<p>Hello: '. $data['admin_nickname']. '</p>'; 
    }

//Shorten text for display content
function shorten_text($text, $max_length){
    if (mb_strlen($text, 'UTF-8') > $max_length) {
        $shorten_text = mb_substr($text, 0, $max_length, 'UTF-8');
        $shorten_text .= '...';
        return $shorten_text;
    }
    return $text;
}

// Pagination (phân trang)
$record_per_page = 6;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $record_per_page;

        $query = "SELECT * FROM Post, Category WHERE Post.category_id = Category.category_id
                  LIMIT $offset, $record_per_page";

        $result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<div class='container'>";
    echo "<div class='row'>";
    while ($data = $result->fetch_assoc()) {
        echo "<div class='col-md-4'>";
        echo "<p>" . $data['category_name'] . "</p>"; // Category displayed
        echo "<a href='read.php?post_id=" . $data['post_id'] . "'>" . $data['post_title'] . "</a>"; //Title displayed
        echo "<img src='" . $data['post_image'] . "' width='100%'>"; // Image displayed
        echo "<p>" . shorten_text($data['post_content'], 100) . "</p>"; // Content displayed (shortened)

        if ($data['status'] == 0){
            echo 'Status: Pending'. '<br>';
        }else {
            echo ($data['status'] == 1) ? 'Status: Approved' : 'Status: Rejected';
            echo "<br>";
            echo "<a href='delete.php?post_id=" . $data['post_id'] . "'>" . "Delete</a>";
        }
        if ($data['status'] == 0) {
            echo '<form method="POST" action="update_status.php">';
            echo '<input type="hidden" name="post_id" value="' . $data['post_id'] . '">';
            echo '<button type="submit" name="status" value="approve">Approve</button>';
            echo '<button type="submit" name="status" value="reject">Reject</button>';
            echo '</form>';
        }

        // Check if the user is logged in and display the "Update" and "Delete" buttons
        if (isset($_SESSION['admin_id'])) {
            if ($_SESSION['admin_id'] == $data['admin_id']){
                echo "<a href='update.php?post_id=" . $data['post_id'] . "'>" . "Update</a>";
            }
        }

         // Rating form
         echo "<p>Current Rating: ". $data['rate'] . "</p>";
         echo "<form method='POST' action='home.php?post_id=" . $data['post_id'] . "'>";
         echo "<div>
         <select name='post_rating'>
         <option>--- Rating ---</option>
         <option value='1'>1</option>
         <option value='2'>2</option>
         <option value='3'>3</option>
         <option value='4'>4</option>
         <option value='5'>5</option>
         </select>
         <button type='submit' name='rate_submit'>Rate</button>
         </div>";
         echo "</form>";
        echo "</div>";
    }
    echo "</div>";
    echo "</div>";

    // Pagination links
    $query = "SELECT COUNT(*) as total FROM Post";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $total_pages = ceil($row['total'] / $record_per_page);

    echo "<div class='container'>";
    echo "<div class='row'>";
    echo "<div class='col-md-12'>";
    echo "<ul class='pagination'>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<li class='page-item";
        if ($i == $page) {
            echo " active";
        }
        echo "'><a class='page-link' href='?page=" . $i . "'>" . $i . "</a></li>";
    }
    echo "</ul>";
    echo "</div>";
    echo "</div>";
    echo "</div>";


    // Handle rating submission
    if (isset($_POST['rate_submit'])) {
        $post_id = $_GET['post_id'];
        $post_rating = $_POST['post_rating'];

        // Update the rating of the post in the database
        $query = "UPDATE Post SET rate = $post_rating WHERE post_id = $post_id";
        $result = $conn->query($query);

        if ($result == TRUE) {
            echo 'Post rated successfully';
        } else {
            echo 'Failed to rate the post: ' . $conn->error;
        }
    }

} else {
    echo 'Error: No data found !';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
        
<?php

}else {
    echo 'You have to Admin login first.'. '<br>';
    echo '<a href="admin_login.php">Login</a>';
}
?>


