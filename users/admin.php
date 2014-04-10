<?php

include "../DBConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_ADMIN_ONLY;
$level = "sub";
include_once('../session_check.php');

$error = "Error occurred while getting data. Please try later..";
$errorParams = array($error, "Home", "eventadmin.php", "icon-home", "../error.php");

$dbh = getConnection($errorParams);

try {
    $sql_string = "SELECT LOGIN_NAME, FIRST_NAME, MIDDLE_NAME, LAST_NAME, EMAIL_ID, PHONE_NUMBER
                   FROM PEOPLE
                   WHERE USER_TYPE = 'ADMIN' AND (EFFECTIVE_END IS NULL OR EFFECTIVE_END > now())";

    $stmt = $dbh->prepare($sql_string);
    $stmt->execute();
    $result = $stmt->fetchAll();

} catch (Exception $e) {
    errorHandler($errorParams);
}


$title = "Admin Page";
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
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Site Administrators</h4></div>
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    $counter = 0;
                    $string="";
                    foreach ($result as $user) {
                        $counter++;
                        $string.="<tr>
                            <td>$counter</td>
                            <td>".$user['FIRST_NAME']."</td>
                            <td>".$user['MIDDLE_NAME']."</td>
                            <td>".$user['LAST_NAME']."</td>
                            <td>".$user['EMAIL_ID']."</td>
                            <td>".$user['PHONE_NUMBER']."</td>
                            <td>
                                <button id=\"$counter\" type=\"button\" class=\"btn btn-xs btn-danger\"
                                        onclick=\"window.location.replace('admindelete.php?usr=".$user['LOGIN_NAME']."')\">
                                    <span class=\"glyphicon glyphicon-trash\"></span>
                                    Remove
                                </button>
                            </td>
                        </tr>";
                    }
                    echo $string;

                    if ($counter == 1) {
                       echo "<script>
                            document.getElementById('1').disabled = true;
                        </script>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
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
