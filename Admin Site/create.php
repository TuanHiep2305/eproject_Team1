<?php
// Connect to the database
include('../include/connections.php');
$message = ""; // Biến để lưu trữ thông báo

if (isset($_POST['submit'])) {
    $post_title = $_POST['post_title'];
    $post_content = $_POST['post_content'];
    $post_image = $_POST['post_image'];
    $post_category = $_POST['post_category'];

    // Đảm bảo các giá trị không rỗng trước khi thêm vào cơ sở dữ liệu
    if (!empty($post_title) && !empty($post_content) && !empty($post_image) && !empty($post_category)) {

        // Sử dụng Prepared Statements để tránh SQL Injection
        $stmt = $conn->prepare("INSERT INTO post (post_title, post_content, post_image, category_id) VALUES (?, ?, ?, ?)");

        // Kiểm tra xem prepare có thành công không
        if ($stmt) {
            $stmt->bind_param("sssi", $post_title, $post_content, $post_image, $post_category);

            // Thực hiện truy vấn và kiểm tra kết quả
            if ($stmt->execute()) {
                $message = "Post created successfully!";
            } else {
                $message = "Error executing query: " . $stmt->error;
            }

            // Đóng Prepared Statement
            $stmt->close();
        } else {
            $message = "Error preparing query: " . $conn->error;
        }
    } else {
        $message = "All fields are required!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            padding: 20px;
        }

        form {
            max-width: 600px;
            margin: auto;
        }

        label {
            margin-top: 10px;
        }

        select {
            margin-top: 10px;
        }

        button {
            margin-top: 20px;
        }

        a {
            display: block;
            margin-top: 20px;
        }
    </style>
</head>

<body>
<a href="home.php" class="btn btn-secondary">HOME</a>

    <form method="POST">
        <h2 class="mb-4">Create a new Post</h2>
        <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo ($message == "Post created successfully!") ? 'success' : 'danger'; ?> mb-3">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
        <div class="mb-3">
            <label for="post_title" class="form-label">Title</label>
            <input type="text" class="form-control" id="post_title" name="post_title" required>
        </div>
        <div class="mb-3">
            <label for="post_content" class="form-label">Content</label>
            <textarea class="form-control" id="post_content" name="post_content" cols="50" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label for="post_image" class="form-label">Image link</label>
            <input type="text" class="form-control" id="post_image" name="post_image" required>
        </div>
        <div class="mb-3">
            <label for="post_category" class="form-label">Select a category</label>
            <select class="form-select" id="post_category" name="post_category" required>
                <option value="">---Select a category---</option>
                <?php
                $categories_query = "SELECT category_id, category_name FROM category";
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
        <button type="submit" name="submit" class="btn btn-primary">Create</button>
    </form>

</body>

</html>
