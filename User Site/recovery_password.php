<?php
include('../include/connections.php');

$new_password = '';

if (isset($_POST['submit'])) {
    $user_email = $_POST['user_email'];
    $user_username = $_POST['user_username'];

    $query = "SELECT * FROM User WHERE user_username = '$user_username' AND user_email = '$user_email'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();

        $new_password = random_password();
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the user's hashed password in the database
        $update_query = "UPDATE User SET user_password = '$hashed_password' WHERE user_username = '$user_username' AND user_email = '$user_email'";
        $update_result = $conn->query($update_query);

        if ($update_result) {
            // echo 'New Password: ' . $new_password . '<br>';
            echo '<script> alert("New Password: '. $new_password. '"); </script>';   
            echo '<script>window.location.href = "user_login.php";</script>';     
        }else {
            echo 'Failed to reset password.';
        }
    } else {
        echo 'Failed to reset password. Invalid username or email.';
    }
}


function random_password($length = 8)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $random_strings = '';

    for ($i = 0; $i < $length; $i++) {
        $random_strings .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $random_strings;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="stylee.css">
  </head>
  <body>
    <!-- MDB -->
    <script type="text/javascript"src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    
    <form class="vh-100" method='POST'>
      <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card bg-primary rounded-end-circle  bg-opacity-75" style="border-radius: 1rem">
              <div class="card-body p-5">
                <a href="home.php">
                  <img class="logo-icon" src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/Aptech_Limited_Logo.svg/1200px-Aptech_Limited_Logo.svg.png" alt="">
                </a>
                <div class="mb-md-5 mt-md-4 ">
                  <h2 class="fw-bold mb-2 text-white text-uppercase">Reset Password</h2>
                  <p class="text-white mb-5">
                    Please enter your Username and Email!
                  </p>
                  <div class="user mb-4">
                    <input type="text" placeholder="Enter username" class="login" name='user_username' id="typeUsernameX" required>
                  </div>

                  <div class="email mb-4">
                    <input type="email" placeholder="Enter email" id="typeEmailX" class="login" name='user_email' required/>
                  </div>

                  <button class="login-btn" type="submit" name='submit'>
                    Reset Password
                  </button>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>


</body>

</html>




<?php
if (!empty($new_password_display)) {
    echo $new_password_display;
}
?>

<a href="user_login.php">Login</a>