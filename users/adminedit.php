<?php

include "../DBConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_ADMIN_ONLY;
$level = "sub";
include_once('../session_check.php');

if (!isset($_GET['usr'])) {
    $home = "../events/eventadmin.php";
    header('Location: ' . $home);
}

$error = "Error occurred while getting data. Please try later..";
$errorParams = array($error, "Home", "eventadmin.php", "icon-home", "../error.php");

$dbh = getConnection($errorParams);

try {
    $sql_string = "SELECT LOGIN_NAME, FIRST_NAME, MIDDLE_NAME, LAST_NAME, EMAIL_ID, PHONE_NUMBER
                   FROM PEOPLE
                   WHERE USER_TYPE = 'ADMIN' AND LOGIN_NAME=?";

    $stmt = $dbh->prepare($sql_string);
    $stmt->bindParam(1, $_GET['usr']);
    $stmt->execute();
    $result = $stmt->fetchAll();

} catch (Exception $e) {
    errorHandler($errorParams);
}


$title = "Edit Admin Information";
include_once('../header.php');
?>

<div id="content">
    <nav class="navbar navbar-left" role="navigation">
        <ul class="nav navbar-inverse">
            <li class="active"><a href="warehouse.php">Manage Admin Users</a></li>
            <li><a href="update_warehouse.php">Add Admin User</a></li>
        </ul>
    </nav>

    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Edit Information</h4></div>
            </div>
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"
                               href="#collapseOne">
                                Change Password
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad
                            squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck
                            quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it
                            squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica,
                            craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur
                            butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth
                            nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
