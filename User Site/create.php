<?php
include('../include/connections.php');
session_start();
$error = '';
$success = '';
// Create a new Post
if (isset($_SESSION['user_id'])) {
    if (isset($_POST['submit'])) {
        $post_title = $_POST['post_title'];
        $post_content = $_POST['post_content'];
        $post_image = $_POST['post_image'];
        $post_category = $_POST['post_category'];
        $user_id = $_SESSION['user_id'];

        $query = "INSERT INTO Post (post_title, post_content, post_image, category_id, user_id)
                  VALUES ('$post_title', '$post_content', '$post_image', '$post_category', '$user_id')";
        $result = $conn->query($query);

        if ($result == TRUE) {
            $success = "Thanks for creating a new post. Your new post will be sent to the Admin for approval. Please be patient and wait !!!";
        } else {
            $error = "Your creating was failed. Please try again !!!";
        }
    }
}

if (!empty($error)) {
    echo '<div class="alert alert-danger alert-dismissible fade show">
    <strong>Error!</strong> ' . $error . '
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>';
}

if (!empty($success)) {
    echo '<div class="alert alert-success alert-dismissible fade show">
    <strong>Success!</strong> ' . $success . '
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>';
}
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
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="default.css">
    <link rel="stylesheet" href="create.css">
</head>

<body>
<script type="text/javascript"src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

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
        $_SESSION['search_results'] = $search_result;

        header("Location: search_result.php");
        exit();
    } else {
        echo "No results found.";
    }
}
?>

                    <div class="table-selector" id="tableSelector">
                        <?php
                        //Check if user is logged in or not
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
        </div>

        <!-- category (thể loại)-->
        <div class="category-list">
            <ul class="list-unstyled list-inline">
                <li><a class="category-color" href="home.php"><i class="category-color icon-home fa-solid fa-house"></i></a></li>
                <li class="list-inline-item"><a href="business.php">Business</a></li>
                <li class="list-inline-item"><a href="technology.php">Technology</a></li>
                <li class="list-inline-item"><a href="sports.php">Sports</a></li>
                <li class="list-inline-item"><a href="beauty.php">Beauty</a></li>
                <li class="list-inline-item"><a href="sociaty.php">Sociaty</a></li>
                <li class="list-inline-item"><a href="about_us.php">About Us</a></li>
                <li class="list-inline-item"><a href="contact_us.php">Contact Us</a></li>
            </ul>
        </div>
    </div>
</header>
    <form class="" method='POST'>
        <div class="form">
            <div class="form-create">
                <div class="form-header">
                    <span>Create a New Post</span>
                    <p>Please enter your Post information below !!!</p>

                    <div class="form-box">
                        <input type="text" placeholder="Enter Post title here" id="form3Example1" class="form-box-title" name="post_title" required />
                        <div class="form-select-category">
                            <select name="post_category" class="">
                                <option>---Select a category---</option>
                                <?php
                                    $categories_query = "SELECT category_id, category_name FROM Category";
                                    $categories_result = $conn->query($categories_query);
                                    if ($categories_result->num_rows > 0) {
                                        while ($row = $categories_result->fetch_assoc()) {
                                        $category_id = $row['category_id'];
                                            $category_name = $row['category_name'];
                                            echo "<option class='category-id' value='$category_id'>$category_name</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-file-img">
                            <!-- <input type="file" id="uploadImage" class=""  name="post_image" required />
                            <label for="uploadImage" class="file-label">
                            </label>
                            <span id="displayFileName"></span> -->

                            <label for="uploadImage" class="file-label">
                                <i class="fas fa-upload" style='color: white'><span style='color: white; font-size: 15px'>Image Link</span></i>
                            </label>
                            <span id="displayFileName"></span>
                            <input type="text" id="uploadImage" class="" name="post_image" style='color: black' required />
                        </div>

                            <button class="form-btn" type="submit" name='submit'>Create</button>
                    </div>
                </div>
                
                <div class="form-box-content">                                        
                    <textarea class="form-content-area" placeholder="Place post's content here" name="post_content" id="form3Example4" cols="50" rows="10" required></textarea>         
                </div>
            </div>
        </div>
    </form>
    <script>
        document.getElementById('uploadImage').addEventListener('change', function () {
            var fileName = this.files[0].name;
            var displayFileName = document.getElementById('displayFileName');
            displayFileName.textContent = fileName;
        });
    </script>
</body>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.umd.min.js">
    </script>
</html>
<?php 
include('footer.php');
?>