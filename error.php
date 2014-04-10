<?php

include_once('constants.php');
$level = "root";
$page_type = PAGE_TYPE_ALL;
include_once('session_check.php');

$message = "";
if (isset($_SESSION['errorMsg'])) {
    $message = $_SESSION['errorMsg'];
    $btn = $_SESSION['btn'];
    $ico = $_SESSION['ico'];
    $back = $_SESSION['back'];
    unset($_SESSION['errorMsg']);
    unset($_SESSION['btn']);
    unset($_SESSION['ico']);
    unset($_SESSION['back']);
} else {
    header('Location: index.php');
}

$title = "Error Occurred";
require_once('header.php');

?>

    <div class="row hero-unit-green">
        <h2>Error</h2>
        <p><? echo $message ?> </p>
        <a href="<? echo $back; ?>" class="btn btn-small btn-info">
            <i class="<? echo $ico; ?> icon-white"></i> <? echo $btn; ?></a>
    </div>

<?php
    include_once('footer.php');
?>
