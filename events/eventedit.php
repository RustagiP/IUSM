<?php

include "../DbConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_ADMIN_ONLY;
$level = "sub";
include_once('../session_check.php');

$eventTitle = '';
$venue = '';
$description = '';
$start = '';
$end = '';
$capacity = '';
$eventId = '';
$registered = '';

$eventExists = true;
$capacityExceeded = false;

if (isset($_GET['id'])) {
    $eventId = $_GET['id'];

    $error = "Error occurred while getting data. Please try later..";
    $errorParams = array($error, "Home", "eventmgt.php", "icon-home", "../error.php");

    $dbh = getConnection($errorParams);

    try {
        $sql_string = "SELECT TITLE, VENUE, DESCRIPTION, START_DATE, END_DATE, CAPACITY
                       FROM EVENT
                       WHERE EVENT_ID=?";
        $stmt = $dbh->prepare($sql_string);
        $stmt->bindParam(1, $eventId);
        $stmt->execute();

        $result = $stmt->fetchAll();

        if (count($result) == 0) {
            $eventExists = false;
        } else {
            $event = $result[0];
            $eventTitle = $event['TITLE'];
            $venue = $event['VENUE'];
            $description = $event['DESCRIPTION'];
            $start = $event['START_DATE'];
            $end = $event['END_DATE'];
            $capacity = $event['CAPACITY'];
        }
    } catch (Exception $e) {
        errorHandler($errorParams);
    }

}

if (isset($_POST['submitted'])) {

    $eventTitle = $_POST['inputTitle'];
    $venue = $_POST['inputVenue'];
    $description = $_POST['inputDescription'];
    $start = $_POST['inputStart'];
    $end = $_POST['inputEnd'];
    $capacity = $_POST['inputCapacity'];
    $eventId = $_POST['eventId'];

    $error = "Error occurred while getting data. Please try later..";
    $errorParams = array($error, "Back", "./events/eventmgt.php", "icon-home", "../error.php");

    $dbh = getConnection($errorParams);

    try {
        $dbh->beginTransaction();

        $sql_string = "SELECT END_DATE, CAPACITY, AVAIL_CAPACITY
                       FROM EVENT
                       WHERE EVENT_ID=?";

        $stmt = $dbh->prepare($sql_string);
        $stmt->bindParam(1, $eventId);
        $stmt->execute();

        $result = $stmt->fetchAll();
        $event = $result[0];

        $originalCapacity = $event['CAPACITY'];
        $availableCapacity = $event['AVAIL_CAPACITY'];
        $originalEndDate = $event['END_DATE'];

        // Logic to accommodate changes in event capacity taking in to account already registered users. We disallow
        // lowering the event capacity to less than already registered users. Capacity can be increased of course.
        $registered = $originalCapacity - $availableCapacity;

        if ($capacity < $registered) {
            $capacityExceeded = true;
        } else {
            $availableCapacity = $capacity - $registered;
        }

        if (!($capacityExceeded == true)) {

            $sql_string = "UPDATE EVENT SET TITLE=?, VENUE=?, DESCRIPTION=?, START_DATE=?";

            if ($end == '') {
                if ($originalEndDate != '') {
                    $sql_string = $sql_string . ", END_DATE=NULL";
                }
            } else {
                $sql_string = $sql_string . ", END_DATE=?";
            }

            $sql_string = $sql_string . ", CAPACITY=?, AVAIL_CAPACITY=? ";

            $sql_string = $sql_string . " WHERE EVENT_ID=?";

            $stmt = $dbh->prepare($sql_string);

            // echo "SQL String : ". $sql_string;

            $stmt->bindParam(1, $eventTitle);
            $stmt->bindParam(2, $venue);
            $stmt->bindParam(3, $description);
            $stmt->bindParam(4, $start);

            $counter = 4;

            if ($end != '') {
                $counter++;
                $stmt->bindParam($counter, $end);
            }

            $counter++;
            $stmt->bindParam($counter, $capacity);

            $counter++;
            $stmt->bindParam($counter, $availableCapacity);

            $counter++;
            $stmt->bindParam($counter, $eventId);

            //echo "COUNTER : ". $counter;

            $stmt->execute();

            $dbh->commit();

            $eventHome = "eventadmin.php";
            header('Location: ' . $eventHome);
        }

    } catch (Exception $e) {
        echo "Error : ". $e->getMessage();
        errorHandler($errorParams);
    }
}

$title = "Event Administration Page";
include_once('../header.php');

?>

<div id="content">

    <nav class="navbar navbar-left" role="navigation">
        <ul class="nav navbar-inverse">
            <li><a href="eventadmin.php">Approvals</a></li>
            <li><a href="eventmgt.php">Event Management</a></li>
            <li><a href="eventadd.php">Add Event</a></li>
        </ul>
    </nav>

    <div class="row">
        <div class="col-md-10">
            <?php
            if ($capacityExceeded == true) {
                ?>
                <div class="alert alert-danger">Cannot reduce capacity of the event below <?php echo $registered; ?>.
                    There are users <?php echo $registered; ?> already registered.
                </div>
            <?php
            }
            ?>
            <form id="userRegistrationForm" class="form-horizontal" role="form" action="eventedit.php"
                  method="post">
                <fieldset>
                    <legend> Edit Event</legend>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputTitle">Title</label>

                        <div class="col-sm-10">
                            <input type="text" id="inputTitle" name="inputTitle"
                                   value="<?php echo $eventTitle; ?>" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputPhone">Venue</label>

                        <div class="col-sm-10">
                            <input type="text" id="inputVenue" name="inputVenue" value="<?php echo $venue; ?>"
                                   required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputDescription">Description</label>

                        <div class="col-sm-10">
                            <textarea rows="10" cols="80" id="inputDescription"
                                      name="inputDescription"><?php echo $description; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputStart">Start Date</label>

                        <div class='col-sm-3 input-group date' id='datetimepicker1'>
                            <input type='text' name="inputStart" value="<?php echo $start; ?>"  class="form-control" data-format="YYYY-MM-DD HH:mm" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputEnd">End Date</label>

                        <div class='col-sm-3 input-group date' id='datetimepicker2'>
                            <input type='text' name="inputEnd" value="<?php echo $end; ?>" class="form-control" data-format="YYYY-MM-DD HH:mm" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                        </div>

                        <script type="text/javascript">
                            $(function () {
                                $('#datetimepicker1').datetimepicker();
                                $('#datetimepicker2').datetimepicker();
                                $("#datetimepicker1").on("change.dp",function (e) {
                                    $('#datetimepicker2').data("DateTimePicker").setStartDate(e.date);
                                });
                                $("#datetimepicker2").on("change.dp",function (e) {
                                    $('#datetimepicker1').data("DateTimePicker").setEndDate(e.date);
                                });
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputCapacity">Capacity</label>

                        <div class="col-sm-10">
                            <input type="text" id="inputCapacity" name="inputCapacity"
                                   value="<?php echo $capacity; ?>"/>
                        </div>
                    </div>
                    <br/>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="hidden" name="eventId" value="<?php echo $eventId; ?>"/>
                            <input type="hidden" name="submitted" value="submitted"/>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
