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
        <div class="title">MANAGE POST</div>
    </div>
    <?php
function search($keyword) {
    global $conn;
    // Sanitize the keyword to prevent SQL injection
    $keyword = mysqli_real_escape_string($conn, $keyword);

    $query_search = "SELECT * FROM Post WHERE post_content LIKE '%$keyword%' OR post_title LIKE '%$keyword%'";
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

if (isset($_POST['search'])) {
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
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $record_per_page = 6;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $record_per_page;

        $query = "SELECT * FROM User, Post 
                        WHERE User.user_id = Post.user_id 
                        AND Post.user_id = '$user_id' 
                        AND status = 1";
        $result = $conn->query($query);

        $total_approved_posts = $result->num_rows;

        $total_pages = ceil($total_approved_posts / $record_per_page);

        $query = "SELECT * FROM User, Post, Category 
                        WHERE Category.category_id = Post.category_id 
                        AND Post.user_id = '$user_id' 
                        AND User.user_id = Post.user_id 
                        AND status = 1
              LIMIT $offset, $record_per_page";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo "<div class='container'>";
                echo "<div class='row row-cols-1 row-cols-md-3 g-4'>";
                    while ($data = $result->fetch_assoc()) {
                        echo "<div class='col-md-4'>";
                            echo "<div class='card'>";
                                echo "<img src='" . $data['post_image'] . "' class='card-img-top' alt='...'>";
                                echo "<div class='card-body'>";
                                    echo "<h5 class='card-title'>" . $data['post_title'] . "</h5>";
                                    echo "<p class='card-text'>" . shorten_text($data['post_content'], 100) . "</p>";
                                    echo "<div class='card-button'>";
                                        echo "<a href='delete.php?post_id=" . $data['post_id'] . "' class='btn btn-danger'>Delete</a>";
                                        echo "<a href='update.php?post_id=" . $data['post_id'] . "' class='btn btn-primary'>Update</a>";
                                    echo "</div>";
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                    }
                echo "</div>";
            echo "</div>";

            // Pagination links (Liên kết phân trang)
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
        }
        } else {
        echo 'Error: No data found !';
    }

    //Shorten text for display content (Rút gọn văn bản để hiển thị nội dung)
    function shorten_text($text, $max_length)
    {
        if (mb_strlen($text, 'UTF-8') > $max_length) {
            $shorten_text = mb_substr($text, 0, $max_length, 'UTF-8');
            $shorten_text .= '...';
            return $shorten_text;
        }
        return $text;
    }
    include('footer.php');
    ?>
    

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
