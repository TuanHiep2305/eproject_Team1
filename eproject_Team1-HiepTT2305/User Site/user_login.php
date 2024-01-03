


<?php
include('../include/connections.php');
session_start();
    if (isset($_POST['login'])){
        $user_username = $_POST['user_username'];
        $user_password = $_POST['user_password'];
        $query = "SELECT * FROM User WHERE user_username = '$user_username' AND user_password = '$user_password'";
        $result = $conn->query($query);

        if ($result == TRUE && $result->num_rows > 0){
            $data = $result->fetch_assoc();
            $_SESSION['user_id'] = $data['user_id'];
            $_SESSION['user_nickname'] = $data['user_nickname'];

            header('Location: home.php');
        }else {
            echo 'Thất bại';
        }

    }
?>




<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <!-- Font Awesome -->
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
      rel="stylesheet"
    />
    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
      rel="stylesheet"
    />
    <!-- MDB -->
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.css"
      rel="stylesheet"
    />
  </head>
  <body>
    <!-- MDB -->
    <script
      type="text/javascript"
      src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.umd.min.js"
    ></script>
    <style>
      .gradient-custom {
        /* fallback for old browsers */
        background: #6a11cb;

        /* Chrome 10-25, Safari 5.1-6 */
        background: -webkit-linear-gradient(
          to right,
          rgba(106, 17, 203, 1),
          rgba(37, 117, 252, 1)
        );

        /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        background: linear-gradient(
          to right,
          rgba(106, 17, 203, 1),
          rgba(37, 117, 252, 1)
        );
      }
      .form-control{
        width: 300px;
        margin-left: 90px;
      }
    </style>

    <form class="vh-100 gradient-custom" method='POST'>
      <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card bg-dark text-white" style="border-radius: 1rem">
              <div class="card-body p-5 text-center">
                <div class="mb-md-5 mt-md-4 pb-5">
                  <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                  <p class="text-white-50 mb-5">
                    Please enter your login and password!
                  </p>

                  <div class="form-outline form-white mb-4">
                    <input type="text" id="typeEmailX" class="form-control form-control-lg" name='user_username'/>
                    <label class="form-label" for="typeEmailX">Username</label>
                  </div>

                  <div class="form-outline form-white mb-4">
                    <input
                      type="password"
                      id="typePasswordX"
                      class="form-control form-control-lg " name='user_password'
                    />
                    <label class="form-label" for="typePasswordX"
                      >Password</label
                    >
                  </div>

                  <button
                    class="btn btn-outline-light btn-lg px-5"
                    type="submit" name='login'
                  >
                    Login
                  </button>
                </div>

                <div>
                  <p class="mb-0">
                    Don't have an account?
                    <a href="user_register.php" class="text-white-50 fw-bold" >Sign Up</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
    <a href="home.php">HOME</a>
  </body>
</html>