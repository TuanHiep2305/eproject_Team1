<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700&family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>Sports</title>
</head>
<body>
<div class="container">
    <div class="row navbar">

        <!-- logo -->
        <div class="logo col-6 d-flex align-items-center">
            <img class="logo-icon" src="image/logo.jpg" alt="">
            <a class="" href="#">NEW EXPRESS</a>
        </div>

        <!-- login (đăng nhập)-->
        <div class="login col-6 d-flex justify-content-end">
            <i class="fa-regular fa-user" id="userIcon"></i>    
            <div class="login-options" id="loginOptions">
                <?php 
                    include('../include/connections.php');
                    if (isset($_SESSION['user_id']) && isset($_SESSION['user_nickname'])){
                        $user_id = $_SESSION['user_id'];

                        $query = "SELECT * FROM User WHERE user_id = $user_id";
                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                            $data = $result->fetch_assoc();
                            echo "<p class='login-name'>Hello: " . $data['user_nickname'] . "</p>";

                        }
                    }
                ?>
            </div>
            
            <div class="table-selector" id="tableSelector">
                <?php
                // Connect to the database (kết nối với database)
                    if (isset($_SESSION['user_id'])) {
                        echo "<a href='create.php'>Create a new Post</a>";
                        echo "<a href='user_update.php'>Change Information</a>";
                        echo "<a href='change_password.php'>Change Password</a>";
                        echo "<a href='user_logout.php'>Logout</a>";
                    } else {
                        echo '<a href="user_login.php">Login</a>';
                        echo '<a href="user_register.php">Register</a>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>


  <!-- category (thể loại)-->
<div class="category-list">
    
    <ul class="list-unstyled list-inline">
        <a href="home.php"><i class="icon-home fa-solid fa-house"></i></a>
        <li class="list-inline-item"><a href="business.php">Business</a></li>
        <li class="list-inline-item"><a href="technology.php">Technology</a></li>
        <li class="list-inline-item"><a href="sports.php">Sports</a></li>
        <li class="list-inline-item"><a href="beauty.php">Beauty</a></li>
        <li class="list-inline-item"><a href="sociaty.php">Sociaty</a></li>
    </ul>

</div>
</body>
<script>
    document.getElementById('userIcon').addEventListener('click', function() {
    var tableSelector = document.getElementById('tableSelector');
    if (tableSelector.style.display === 'none' || tableSelector.style.display === '') {
        tableSelector.style.display = 'block';
    } else {
        tableSelector.style.display = 'none';
    }
});

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</html>





<?php
// Pagination 
$record_per_page = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $record_per_page;

// Retrieve approved records from the database
$query = "SELECT * FROM Post, Category WHERE Post.category_id = Category.category_id AND Category.category_name = 'Sports' AND status = 1";
$result = $conn->query($query);

if ($result === false) {
    echo "Error: " . $conn->error;
    exit;
}

// Calculate the total number of approved posts
$total_approved_posts = $result->num_rows;

// Calculate the total number of pages based on the number of approved posts and the records per page
$total_pages = ceil($total_approved_posts / $record_per_page);

// Retrieve records for the current page
$query = "SELECT * FROM Post, Category WHERE Post.category_id = Category.category_id AND Category.category_name = 'Sports' AND status = 1
          LIMIT $offset, $record_per_page";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<div class='post-container'>";
    // echo "<div class='row'>";
    $itemNumber = 1;
    while ($data = $result->fetch_assoc()) {
        echo "<div class='post item-" . $itemNumber . "'>";
            // echo "<p>" . $data['category_name'] . "</p>"; // Category displayed
            echo "<img src='" . $data['post_image'] . "' width='100%'>"; // Image displayed
            echo "<div class='item-box'>";
                echo "<a href='read.php?post_id=" . $data['post_id'] . "'>" . $data['post_title'] . "</a>"; //Title displayed
                echo "<p>" . shorten_text($data['post_content'], 200) . "</p>"; // Content displayed (shortened)
            echo "</div>";

        // Check if the user is logged in and display the "Update" and "Delete" buttons
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['user_id'] == $data['user_id']){
                echo "<a href='delete.php?post_id=" . $data['post_id'] . "'>" . "Delete</a>";
                echo "<a href='update.php?post_id=" . $data['post_id'] . "'>" . "Update</a>";
            }
        }
        echo "</div>";
        $itemNumber++;
    }
    // echo "</div>";
    echo "</div>";

    // Pagination links
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
} else {
    echo 'Error: No data found !';
}

//Shorten text for display content
function shorten_text($text, $max_length)
{
    if (mb_strlen($text, 'UTF-8') > $max_length) {
        $shorten_text = mb_substr($text, 0, $max_length, 'UTF-8');
        $shorten_text .= '...';
        return $shorten_text;
    }
    return $text;
}
?>


<?php
// Create a new Post
// if (isset($_SESSION['user_id'])) {
//     echo "<a href='user_update.php'>Change Information</a>";
//     echo "<a href='change_password.php'>Change Password</a>";
//     echo "<a href='user_logout.php'>Logout</a>";
// }else{
//     echo '<a href="user_login.php">Login</a>';
//     echo '<a href="user_register.php">Register</a>';
// }
// ?>

