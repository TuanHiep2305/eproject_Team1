<?php
include('../include/connections.php');
session_start();
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $query = "SELECT * FROM Feedback JOIN User ON Feedback.user_id = User.user_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()){
            $fb_id = $data['fb_id'];
?>
    <div>
        <h2>Feedback of <?php echo $data['user_nickname']; ?></h2>
        <h3>Username: <?php echo $data['user_username']; ?></h3>
        <p>Feedback's Title: <?php echo $data['fb_title']; ?></p>
        <p>Feedback's Content: <?php echo $data['fb_content']; ?></p>
<?php
            // Check if a response exists for the feedback
            $response_query = "SELECT * FROM Response, Feedback
                               WHERE Response.fb_id = Feedback.fb_id AND Feedback.fb_id = '$fb_id'";
            $response_result = $conn->query($response_query);
            if ($response_result == false){
                echo "Error: ". $conn->error;
            }

            if ($response_result->num_rows > 0) {
                // Display the response
                $response_data = $response_result->fetch_assoc();
?>
        <h3>Response</h3>
        <p>Response Title: <?php echo $response_data['response_title']; ?></p>
        <p>Response Content: <?php echo $response_data['response_content']; ?></p>
<?php
            } else {
                // Check if the response form is submitted
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['fb_id'] == $fb_id){
                    $response_title = $_POST['response_title'];
                    $response_content = $_POST['response_content'];

                    $query_response = "INSERT INTO Response (response_title, response_content, admin_id, fb_id)
                                       VALUES ('$response_title', '$response_content', '$admin_id', '$fb_id')";
                    $result_response = $conn->query($query_response);

                    if ($result_response == TRUE){
                        echo 'Response Successfully';
                    } else {
                        echo 'Response Failed';
                    }
                } else {
                    // Display the response form
?>
        <form method="POST">
            <h3>Response</h3>
            <div>
                <label>Response Title</label>
                <input type="text" name="response_title">
            </div>
            <div>
                <label>Response Content</label>
                <textarea name="response_content" cols="30" rows="10"></textarea>
            </div>
            <input type="hidden" name="fb_id" value="<?php echo $fb_id; ?>">
            <button type="submit">Response</button>
        </form>
<?php
                }
            }
?>
    </div>
<?php
        }
    }
}else {
    echo 'You have to login first !!!'. '<br>';
}
?>
<a href="home.php">HOME</a>