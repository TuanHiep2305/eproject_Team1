<?php
include('../include/connections.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
    <!-- Add Bootstrap CSS link -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add your custom styles if needed -->
    <style>
        /* Your custom styles here */
    </style>
</head>
<body>
<?php
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $query = "SELECT * FROM Feedback JOIN User ON Feedback.user_id = User.user_id";
    $result = $conn->query($query);
    echo'
    <div class="container">
    
    <a href="home.php" class="btn btn-secondary">HOME</a>
    </div>
    ';
    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {
            $fb_id = $data['fb_id'];
            ?>
            <div class="container mt-3" style="border:1px solid #ccc; padding:20px;margin-bottom:30px !important">
                <h2>Feedback of <?php echo $data['user_nickname']; ?></h2>
                <h3>Username: <?php echo $data['user_username']; ?></h3>
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
                } else {
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['fb_id'] == $fb_id) {
                        $response_title = $_POST['response_title'];
                        $response_content = $_POST['response_content'];

                        $query_response = "INSERT INTO Response (response_title, response_content, admin_id, fb_id)
                                           VALUES ('$response_title', '$response_content', '$admin_id', '$fb_id')";
                        $result_response = $conn->query($query_response);

                        if ($result_response == TRUE) {
                            echo '<div class="alert alert-success" role="alert">Response Successfully</div>';
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Response Failed</div>';
                        }
                    } else {
                        ?>
                        <form method="POST">
                            <h3>Response</h3>
                            <div class="form-group">
                                <label>Response Title</label>
                                <input type="text" class="form-control" name="response_title">
                            </div>
                            <div class="form-group">
                                <label>Response Content</label>
                                <textarea class="form-control" name="response_content" cols="30"
                                          rows="10"></textarea>
                            </div>
                            <input type="hidden" name="fb_id" value="<?php echo $fb_id; ?>">
                            <button type="submit" class="btn btn-primary">Response</button>
                        </form>
                        <?php
                    }
                }
                ?>
            </div>
            <?php
        }
    }
} else {
    echo '<div class="container mt-3 alert alert-warning" role="alert">You have to login first !!!</div>';
}
?>
<!-- Add Bootstrap JS and Popper.js scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
