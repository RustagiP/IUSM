<?php

include "../DbConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_ADMIN_ONLY;
$level = "sub";
include_once('../session_check.php');

$error = "Error occurred while getting data. Please try later..";
$errorParams = array($error, "Home", "eventadmin.php", "icon-home", "../error.php");

$dbh = getConnection($errorParams);

try {
    $sql_string = "SELECT EVENT_ID, TITLE, DESCRIPTION, VENUE, START_DATE, END_DATE, CAPACITY
                   FROM EVENT
                   WHERE STATUS='PENDING'";

    $stmt = $dbh->prepare($sql_string);
    $stmt->execute();
    $result = $stmt->fetchAll();

} catch (Exception $e) {
    errorHandler($errorParams);
}

$title = "Event Administration Page";
include_once('../header.php');

?>

<div id="content">

    <nav class="navbar navbar-left" role="navigation">
        <ul class="nav navbar-inverse">
            <li class="active"><a href="eventadmin.php">Approvals</a></li>
            <li><a href="eventmgt.php">Event Management</a></li>
            <li><a href="eventadd.php">Add Event</a></li>
        </ul>
    </nav>

    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Pending Event Approvals</h4></div>
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Venue</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    $counter = 0;
                    foreach ($result as $event) {
                        $counter++;
                        ?>
                        <tr>
                            <td><?php echo "$counter"; ?></td>
                            <td><?php echo $event['TITLE']; ?></td>
                            <td><?php echo $event['DESCRIPTION']; ?></td>
                            <td><?php echo $event['VENUE']; ?></td>
                            <td><?php echo $event['START_DATE']; ?></td>
                            <td><?php echo $event['END_DATE']; ?></td>
                            <td><?php echo $event['CAPACITY']; ?></td>
                            <td>
                                <button id="<?php echo $counter; ?>" type="submit" class="btn btn-xs btn-primary"
                                        onclick="window.location.replace('eventapproval.php?decision=true&id=<?php echo $event['EVENT_ID'] ?>')">
                                    <span class="glyphicon glyphicon-ok-circle"></span>
                                    Approve
                                </button>
                                <button id="<?php echo $counter; ?>" type="submit" class="btn btn-xs btn-danger"
                                        onclick="window.location.replace('eventapproval.php?decision=false&id=<?php echo $event['EVENT_ID'] ?>')">
                                    <span class="glyphicon glyphicon-remove-circle"></span>
                                    Reject
                                </button>
                            </td>
                        </tr>
                    <?php
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
        $( "#navEvent" ).addClass("active");
    });
</script>

<?php
include_once('../footer.php');
?>



