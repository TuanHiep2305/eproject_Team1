<?php
include('../include/connections.php');
session_start();
if (isset($_SESSION['user_id'])){
    $post_id = $_GET['post_id'];
    $query = "SELECT * FROM Post 
                       JOIN Category ON Post.category_id = Category.category_id 
                       JOIN User ON User.user_id = Post.user_id
                       WHERE post_id = '$post_id'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0){
        $data = $result->fetch_assoc();
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $new_post_title = $_POST['post_title'];
            $new_post_content = $_POST['post_content'];
            $new_post_image = $_POST['post_image'];
            $new_post_category = $_POST['post_category'];
            
            $query_update = "UPDATE Post
                            JOIN Category ON Post.category_id = Category.category_id 
                            JOIN User ON User.user_id = Post.user_id
                            SET Post.post_title = '$new_post_title', 
                                Post.post_content = '$new_post_content',
                                Post.post_image = '$new_post_image',
                                Post.category_id = '$new_post_category'   
                            WHERE post_id = '$post_id'";
            $result_update = $conn->query($query_update);

            //Check update result
            if ($result_update) {
                echo "Updated successfully";
            } else {
                echo "Update failed: " . $conn->error;
            }
        }
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
    <form method='POST'>
        <h2>Update Post</h2>
            <div>
                <label>Title</label>
                <input type="text" name="post_title" value='<?php echo $data['post_title']; ?>'>
            </div>
            <div>
                <label>Content</label>
                <textarea name="post_content" value='<?php echo $data['post_content']; ?>' cols="50" rows="10"><?php echo $data['post_content']; ?></textarea>
            </div>
            <div>
                <label>Image</label>
                <img src="<?php echo $data['post_image']; ?>" width="20%">
                <input type="text" name="post_image" value="<?php echo $data['post_image']; ?>">
            </div>
            <div>
                <select name="post_category">
                <?php
                $categories_query = "SELECT category_id, category_name FROM Category";
                $categories_result = $conn->query($categories_query);

                if ($categories_result->num_rows > 0){
                    while ($row = $categories_result->fetch_assoc()){
                        $category_id = $row['category_id'];
                        $category_name = $row['category_name'];
                        // Check if the current category is the selected category
                        $selected = ($category_id == $data['category_id']) ? 'selected' : '';
                        echo "<option value='$category_id' $selected>$category_name</option>";
                    }
                }
                ?>
                </select>
            </div>
            <button type='submit'>Update</button>
    </form>
        <a href="home.php">Return</a>
    </body>
    </html>

<?php
    }else {
        echo 'Update failed';
    }

}else {
    echo 'You have to Login first !!!'. '<br>';
}
?>

<a href="home.php">Home</a>