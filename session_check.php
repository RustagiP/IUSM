<?php

include_once('constants.php');

if (session_id() == "") {
    session_start();
}

// Block unauthorized access to pages according to user type
if (isset($_SESSION['loggedIn'])) {

    if ($_SESSION['userType'] == REGISTERED_USER_TYPE) {
        if ($page_type == PAGE_TYPE_ADMIN_ONLY || $page_type == PAGE_TYPE_ANON_ONLY) {
            if ($level == "root") {
                $home = './users/home.php';
                header('Location: ' . $home);
            } else {
                $home = '../users/home.php';
                header('Location: ' . $home);
            }
        }
    } else if ($_SESSION['userType'] == ADMIN_USER_TYPE) {
        if ($page_type == PAGE_TYPE_REGISTERED_ONLY || $page_type == PAGE_TYPE_ANON_ONLY) {
            if ($level == "root") {
                $home = './users/admin.php';
                header('Location: ' . $home);
            } else {
                $home = '../users/admin.php';
                header('Location: ' . $home);
            }
        }
    }
} else {

    if ($page_type == PAGE_TYPE_LOGGED_IN_ONLY) {
        if ($level == "root") {
            $home = 'login.php';
            header('Location: ' . $home);
        } else {
            $home = '../login.php';
            header('Location: ' . $home);
        }
    }

    if ($page_type != PAGE_TYPE_ALL && $page_type != PAGE_TYPE_ANON_ONLY) {
        if ($level == "root") {
            $home = 'index.php';
            header('Location: ' . $home);
        } else {
            $home = '../index.php';
            header('Location: ' . $home);
        }
    }

}