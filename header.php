<?php

include_once('constants.php');

if (session_id() == "") {
    session_start();
}

$adminSession = false;
$userSession = false;
$firstName = '';

if (isset($_SESSION['userType'])) {
    if ($_SESSION['userType'] == ADMIN_USER_TYPE) {
        $adminSession = true;
    } else if ($_SESSION['userType'] == REGISTERED_USER_TYPE) {
        $userSession = true;
    }
    $firstName = $_SESSION['firstName'];
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>

        <?php
        // CSS Imports
        if ($level == 'root') {
            ?>
            <link href="css/bootstrap.min.css" rel="stylesheet" media="screen" type="text/css">
            <link href="css/bootstrap-formhelpers.min.css" rel="stylesheet" media="screen" type="text/css">
            <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen" type="text/css">
            <link rel="stylesheet" type="text/css" href="css/styles.css"/>
            <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
            <script type="text/javascript" src="js/bootstrap.min.js"></script>
            <script type="text/javascript" src="js/moment.min.js"></script>
            <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
            <script type="text/javascript" src="js/project.js"></script>
        <?php
        } else {
        ?>
            <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen" type="text/css">
            <link href="../css/bootstrap-formhelpers.min.css" rel="stylesheet" media="screen" type="text/css">
            <link href="../css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen" type="text/css">
            <link rel="stylesheet" type="text/css" href="../css/styles.css"/>
            <script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
            <script type="text/javascript" src="../js/bootstrap.min.js"></script>
            <script type="text/javascript" src="../js/moment.min.js"></script>
            <script type="text/javascript" src="../js/bootstrap-datetimepicker.min.js"></script>
            <script type="text/javascript" src="../js/bootstrap.js"></script>
            <script type="text/javascript" src="../js/project.js"></script>
        <?php
        }
        ?>
    </head>
<body>

<div class="container">
<?php
if ($adminSession == true) {
    if ($level == "root") {
        ?>
        <a class="Top_Link" href="./login.php?logout=true">Sign Out</a>
        <a class="Top_Link" href="./editprofile.php"><?php echo $firstName; ?></a>
        <header>
            <h1 class="logo"><a href="index.php">IUSM</a></h1>

            <h3 class="intro">Site Administration</h3>
        </header>
        <nav class="navbar navbar-inverse" role="navigation">
            <ul class="nav navbar-nav">
                <li id="navEvent"><a href="./events/eventadmin.php">Events</a></li>
                <li id="navInventory"><a href="./inventory/inventory_adm.php">Inventory</a></li>
                <li id="navWarehouse"><a href="./inventory/warehouse.php">Warehouse</a></li>
                <li id="navUserMgt"><a href="./users/admin.php">User Management</a></li>
                <li id="navCatMgt"><a href="./inventory/category.php">Categories</a></li>
            </ul>
        </nav>

    <?php
    } else {
        ?>
        <a class="Top_Link" href="../login.php?logout=true">Sign Out</a>
        <a class="Top_Link" href="../users/editprofile.php"><?php echo $firstName; ?></a>
        <header>
            <h1 class="logo"><a href="../index.php">IUSM</a></h1>

            <h3 class="intro">Site Administration</h3>
        </header>
        <nav class="navbar navbar-inverse" role="navigation">
            <ul class="nav navbar-nav">
                <li id="navEvent"><a href="../events/eventadmin.php">Events</a></li>
                <li id="navInventory"><a href="../inventory/inventory_adm.php">Inventory</a></li>
                <li id="navWarehouse" ><a href="../inventory/warehouse.php">Warehouse</a></li>
                <li id="navUserMgt"><a href="../users/admin.php">User Management</a></li>
                <li id="navCatMgt"><a href="../inventory/category.php">Categories</a></li>
            </ul>
        </nav>
    <?php
    }
} else if ($userSession == true) {
    if ($level == "root") {
        ?>
        <a class="Top_Link" href="./login.php?logout=true">Sign Out</a>
        <a class="Top_Link" href="./users/editprofile.php"><?php echo $firstName; ?></a>
        <header>
            <h1 class="logo"><a href="index.php">IUSM</a></h1>

            <h3 class="intro">Indiana University Student Ministries</h3>
        </header>
        <nav class="navbar navbar-default" role="navigation">
            <ul class="nav navbar-nav">
                <li id="navHome"><a href="./users/home.php">Home</a></li>
                <li id="navEvent"><a href="./events/eventlist.php">Events</a></li>
                <li id="navAbout"><a href="./aboutus.php">About</a></li>
                <li id="navGi"><a href="./getinvolved.php">Get Involved </a></li>
                <li id="navContact"><a href="./contact.php">Contact</a></li>
            </ul>
        </nav>

    <?php
    } else {
        ?>
        <a class="Top_Link" href="../login.php?logout=true">Sign Out</a>
        <a class="Top_Link" href="../users/editprofile.php"><?php echo $firstName; ?></a>
        <header>
            <h1 class="logo"><a href="../index.php">IUSM</a></h1>

            <h3 class="intro">Indiana University Student Ministries</h3>
        </header>
        <nav class="navbar navbar-default" role="navigation">
            <ul class="nav navbar-nav">
                <li id="navHome"><a href="../users/home.php">Home</a></li>
                <li id="navEvent"><a href="../events/eventlist.php">Events</a></li>
                <li id="navAbout"><a href="../aboutus.php">About</a></li>
                <li id="navGi"><a href="../getinvolved.php">Get Involved </a></li>
                <li id="navContact"><a href="../contact.php">Contact</a></li>
            </ul>
        </nav>
    <?php
    }
} else {

    if ($level == "root") {
        ?>

        <a class="Top_Link" href="./users/registration.php">Register</a> <a class="Top_Link" href="./login.php">Sign
            In</a>
        <header>
            <h1 class="logo"><a href="index.php">IUSM</a></h1>

            <h3 class="intro">Indiana University Student Ministries</h3>
        </header>
        <nav class="navbar navbar-default" role="navigation">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">Home</a></li>
                <li><a href="./events/eventlist.php">Events</a></li>
                <li><a href="aboutus.php">About</a></li>
                <li><a href="getinvolved.php">Get Involved </a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    <?php
    } else {
        ?>

        <a class="Top_Link" href="../users/registration.php">Register</a> <a class="Top_Link" href="../login.php">Sign
            In</a>
        <header>
            <h1 class="logo"><a href="../index.php">IUSM</a></h1>

            <h3 class="intro">Indiana University Student Ministries</h3>
        </header>
        <nav class="navbar navbar-default" role="navigation">
            <ul class="nav navbar-nav">
                <li class="active"><a href="../index.php">Home</a></li>
                <li><a href="../events/eventlist.php">Events</a></li>
                <li><a href="../aboutus.php">About</a></li>
                <li><a href="../getinvolved.php">Get Involved </a></li>
                <li><a href="../contact.php">Contact</a></li>
            </ul>
        </nav>
    <?php
    }
}
?>
