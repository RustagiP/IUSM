<?php

include_once('../constants.php');

session_start();

// If not this page is accessed via registration page block access..
// (e.g: by directly trying to access this page via typing in the URL or browser back button)
if (!isset($_SESSION['userId']) || isset($_SESSION['userName']) ) {
    if (isset($_SESSION['userName']) && $_SESSION['userType'] == ADMIN_USER_TYPE) {
        $home = 'admin.php';
        header('Location: ' . $home);
    } else if (isset($_SESSION['userName']) && $_SESSION['userType'] == REGISTERED_USER_TYPE) {
        $home = 'home.php';
        header('Location: ' . $home);
    } else {
        $home = '../index.php';
        header('Location: ' . $home);
    }
}

$title = "Registration Success";
$level = "sub";
include_once('../header.php');

?>

<div id="content">
    <div class="row hero-unit">
        <p>Registration successful. Please log in with new credentials.</p>
        <a href="../login.php" class="btn btn-small btn-info"><i class="icon-white"></i> Login </a>
    </div>
</div>

<?php
include_once('../footer.php');
?>
