<?php
session_start();
include('../include/connections.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700&family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="default.css">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="content.css">
    <link rel="stylesheet" href="footer.css">
    <title>Home</title>
</head>

<body>
    <header>
        <div class="container">
            <div class="row navbar">
                <!-- logo -->
                <div class="logo col-6 d-flex align-items-center">
                    <a href="home.php">
                        <img class="logo-icon" src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/Aptech_Limited_Logo.svg/1200px-Aptech_Limited_Logo.svg.png" alt="">
                    </a>
                </div>

                <!-- login (đăng nhập)-->
                <div class="login col-6 d-flex justify-content-end ">
                    <div class="form-search">
                        <form method='POST' class="form">
                            <input class="form-search-input" type="text" name='search'>
                            <button class="form-search-btn" type='submit'>Search</button>
                        </form>
                    </div>
                    <i class="fa-regular fa-user" id="userIcon"></i>
                    <div class="login-options" id="loginOptions">
                            
                        <?php
                        if (isset($_SESSION['user_id']) && isset($_SESSION['user_nickname'])) {
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
                    if (isset($_SESSION['user_id'])) {
                      echo "<a href='create.php'>Create a new Post</a>";
                      echo "<a href='manager.php'>Manager Posts</a>";
                      echo "<a href='user_update.php'>Change Information</a>";
                      echo "<a href='change_password.php'>Change Password</a>";
                      echo "<a href='view_feedback.php'>View Feedback</a>";
                      echo "<a href='user_logout.php'>Logout</a>";
                    } else {
                      echo '<a href="user_login.php">Login</a>';
                      echo '<a href="user_register.php">Register</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="title">Your Response's Feedback</div>
    </div>
    <?php
function search($keyword) {
    global $conn;
    // Sanitize the keyword to prevent SQL injection
    $keyword = mysqli_real_escape_string($conn, $keyword);

    $query_search = "SELECT * FROM Post WHERE post_title LIKE '%$keyword%' OR post_content LIKE '%$keyword%'";
    $result_search = $conn->query($query_search);

    // Check if any results were found
    if ($result_search->num_rows > 0) {
        $results = array(); // Create an empty array to store the results

        while ($row = $result_search->fetch_assoc()) {
            $results[] = $row; // Add each row to the results array
        }
        return $results; // Return the array of results
    } else {
        return array(); // Return an empty array if no results found
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search = $_POST['search'];
    $search_result = search($search);

    if (!empty($search_result)) {
        // Store the search results in a session variable
        session_start();
        $_SESSION['search_results'] = $search_result;

        header("Location: search_result.php");
        exit();
    } else {
        echo "No results found.";
    }
}
?>

<?php
$record_per_page = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $record_per_page;

// Retrieve approved records from the database (Truy xuất các bản ghi đã được phê duyệt từ cơ sở dữ liệu)
$query = "SELECT * FROM Feedback, User WHERE Feedback.user_id = User.user_id AND User.user_id = $user_id";
$result = $conn->query($query);

// Calculate the total number of approved posts (Tính tổng số bài viết được phê duyệt)
$data = $result->num_rows;

//  Calculate the total number of pages based on the number of approved posts and the records per page (
$total_pages = ceil($data / $record_per_page);


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM Feedback, User 
          WHERE Feedback.user_id = User.user_id AND User.user_id = $user_id 
          LIMIT $offset, $record_per_page";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {
            $fb_id = $data['fb_id'];
            ?>
            <div class="container mt-3" style="border:1px solid #ccc; padding:20px;margin-bottom:30px !important">
                <h3>Feedback</h3>
                <p>Feedback's Title: <?php echo $data['fb_title']; ?></p>
                <p>Feedback's Content: <?php echo $data['fb_content']; ?></p>
                <?php
                $response_query = "SELECT * FROM Response, Feedback
                                   WHERE Response.fb_id = Feedback.fb_id AND Feedback.fb_id = '$fb_id'";
                $response_result = $conn->query($response_query);
                if ($response_result == false) {
                    echo "Error: " . $conn->error;
                }

                if ($response_result->num_rows > 0) {
                    $response_data = $response_result->fetch_assoc();
                    ?>
                    <h3>Response</h3>
                    <p>Response Title: <?php echo $response_data['response_title']; ?></p>
                    <p>Response Content: <?php echo $response_data['response_content']; ?></p>
                    <?php
                }else{
                    echo '<h3>Response</h3>';
                    echo '<div class="alert alert-info">
                    <strong>Note!</strong> Please be patient to wait admin response your feedback !!!.
                  </div>';
                } 
                ?>
            </div>
            
            <?php

        }
    }else {
        echo "<div class='container mt-3 alert alert-warning' role='alert'>You haven't feedback yet !!!</div>";
    }
    echo "<div class='container'>";
    echo "<div class='row'>";
    echo "<div class='col-md-12 text-center mx-auto'>";
    echo "<ul class='pagination justify-content-center'>";
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
} 

include ('footer.php');
?>
<!-- Add Bootstrap JS and Popper.js scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
        document.getElementById('userIcon').addEventListener('click', function () {
            var tableSelector = document.getElementById('tableSelector');
            if (tableSelector.style.display === 'none' || tableSelector.style.display === '') {
                tableSelector.style.display = 'block';
            } else {
                tableSelector.style.display = 'none';
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

