<?php
include "../DbConnect.php";

include_once('../constants.php');
$level = "sub";
$page_type = PAGE_TYPE_ADMIN_ONLY;
include_once('../session_check.php');

$decision = '';
if (!isset($_GET['decision'])) {
    $home = "../events/eventadmin.php";
    header('Location: ' . $home);
} else {
    $decision = $_GET['decision'];
}

$eventId = '';
if(!isset($_GET['id'])) {
    $home = "../events/eventadmin.php";
    header('Location: ' . $home);
} else {
    $eventId = $_GET['id'];
}

$error = "Error occurred while getting data. Please try later..";
$errorParams = array($error, "Home", "eventadmin.php", "icon-home", "../error.php");

$dbh = getConnection($errorParams);

try {
    $sql_string = '';

    if ($decision == "true") {
        $sql_string = "UPDATE EVENT E SET E.STATUS = 'ONGOING' WHERE E.EVENT_ID = ?";
    } else {
        $sql_string = "UPDATE EVENT E SET E.STATUS = 'REJECTED' WHERE E.EVENT_ID = ?";
    }

    $stmt = $dbh->prepare($sql_string);
    $stmt->bindParam(1, $eventId);
    $stmt->execute();

} catch (Exception $e) {
    errorHandler($errorParams);
}

$location = 'eventadmin.php';
header('Location: ' . $location);
