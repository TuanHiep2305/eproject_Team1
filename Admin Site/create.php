<?php
include('../include/connections.php');
session_start();
// Create a new Post
if (isset($_SESSION['admin_id'])) {
    if (isset($_POST['submit'])) {
        $post_title = $_POST['post_title'];
        $post_content = $_POST['post_content'];
        $post_image = $_POST['post_image'];
        $post_category = $_POST['post_category'];
        $admin_id = $_SESSION['admin_id'];

        $query = "INSERT INTO Post (post_title, post_content, post_image, category_id, admin_id, status)
                  VALUES ('$post_title', '$post_content', '$post_image', '$post_category', '$admin_id', '1')";
        $result = $conn->query($query);

        if ($result == TRUE) {
            echo '<script> alert("Your Post is created successfully !!!");</script>';
            header('Location: home.php');
        }else {
            echo 'Create failed: ' . $conn->error;
        }
    }
}else {
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
       <form method="POST">
        <h2>Create a new Post</h2>
        <div>
            <label>Title</label>
            <input type="text" name="post_title" required>
        </div>
        <div>
            <label>Content</label>
            <textarea name="post_content" cols="50" rows="5" required></textarea >
        </div>
        <div>
            <label>Image link</label>
            <input type="text" name="post_image" required>
        </div>
        <div>
            <select name="post_category">
                <option>---Select a category---</option>
                <?php
                $categories_query = "SELECT category_id, category_name FROM Category";
                $categories_result = $conn->query($categories_query);

                if ($categories_result->num_rows > 0) {
                    while ($row = $categories_result->fetch_assoc()) {
                        $category_id = $row['category_id'];
                        $category_name = $row['category_name'];
                        echo "<option value='$category_id'>$category_name</option>";
                    }
                }
                $conn->close();
                ?>
            </select>
        </div>
        <button type="submit" name="submit">Create</button>
    </form>

<a href="home.php">HOME</a>
</body>

</html>