<?php
include('../include/connections.php');
if (isset($_POST['status'])) {
    $post_id = $_POST['post_id'];
    $status = $_POST['status'];

    // Update the status based on the value
    if ($status === 'approve') {
        $newStatus = 1; // Approved
    } elseif ($status === 'reject') {
        $newStatus = 2; // Rejected
    }

    // Update the status in the database
    $query = "UPDATE Post SET status = $newStatus WHERE post_id = $post_id";
    $result = $conn->query($query);

    if ($result === TRUE) {
        echo 'Status updated successfully';
        // Redirect back to the page displaying the posts
        header('Location: home.php');
        exit;
    } else {
        echo 'Status update failed';
    }

    $conn->close();
}
?>