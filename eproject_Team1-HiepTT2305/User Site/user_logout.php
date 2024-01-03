<?php
include('../include/connections.php');
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_nickname'])){
    unset ($_SESSION['user_id']);
    unset ($_SESSION['user_nickname']);
    session_destroy();
    header('Location: user_login.php');
}
?>