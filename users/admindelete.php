<?php

include "../DBConnect.php";

include_once('../constants.php');
$level = "sub";
$page_type = PAGE_TYPE_ADMIN_ONLY;
include_once('../session_check.php');

if (!isset($_GET['usr'])) {
    $home = "../events/eventadmin.php";
    header('Location: ' . $home);
}

if (isset($_GET['cancel'])) {
    $home = "admin.php";
    header('Location: ' . $home);
}

if (isset($_POST['submitted'])) {
    $error = "Error occurred while getting data. Please try later..";
    $errorParams = array($error, "Home", "eventadmin.php", "icon-home", "../error.php");

    $dbh = getConnection($errorParams);

    try {
        $date = date('Y-m-d');
        $sql_string = "UPDATE PEOPLE P SET P.EFFECTIVE_END = now() WHERE P.LOGIN_NAME = ?;";

        $stmt = $dbh->prepare($sql_string);
        $stmt->bindparam(1, $_POST['user']);
        $stmt->execute();

    } catch (Exception $e) {
        errorHandler($errorParams);
    }

    $location = "admin.php";
    header('Location: ' . $location);
}

$title = "Delete Administrator";
include_once('../header.php');

?>

<div id="content">

    <nav class="navbar navbar-left" role="navigation">
        <ul class="nav navbar-inverse">
            <li class="active"><a href="admin.php">Manage Admin Users</a></li>
            <li><a href="adminadd.php">Add Admin User</a></li>
        </ul>
    </nav>

    <div class="row">
        <div class="col-md-10">
            <div class="alert alert-danger">
                You are going to remove an administrator account. Are you sure?
            </div>

            <p>

            <form id="adminDeletionForm" class="form-horizontal" role="form" action="admindelete.php"
                  method="post">
                <input type="hidden" name="submitted" value="submitted"/>
                <input type="hidden" name="user" value="<?php echo $_GET['usr']; ?>"/>
                <button type="submit" class="btn btn-primary">Yes</button>
                <button class="btn btn-default" onclick="window.location.replace('admindelete.php')">Cancel</button>
            </form>
            </p>
        </div>
    </div>
</div>

<script>
    $( document ).ready(function() {
        $( "#navUserMgt" ).addClass("active");
    });
</script>

<?php
include_once('../footer.php');
?>



