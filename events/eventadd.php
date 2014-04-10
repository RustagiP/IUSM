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

$categories = array();

if (isset($_POST['submitted'])) {

    $eventTitle = $_POST['inputTitle'];
    $venue = $_POST['inputVenue'];
    $description = $_POST['inputDescription'];
    $start = $_POST['inputStart'];
    $end = $_POST['inputEnd'];
    $capacity = $_POST['inputCapacity'];

    $error = "Error occurred while getting data. Please try later..";
    $errorParams = array($error, "Home", "eventadmin.php", "icon-home", "../error.php");

    $dbh = getConnection($errorParams);

    try {
        $dbh->beginTransaction();

        $sql_string = "INSERT INTO EVENT (TITLE, VENUE, DESCRIPTION, START_DATE";

        if ($end != '') {
            $sql_string = $sql_string . ", END_DATE";
        }

        $sql_string = $sql_string . ", CAPACITY, AVAIL_CAPACITY)";

        $sql_string = $sql_string . " VALUES (?, ?, ?, ?";

        if ($end != '') {
            $sql_string = $sql_string . ", ?";
        }

        $sql_string = $sql_string . ", ?, ?)";

        $stmt = $dbh->prepare($sql_string);
        $stmt->bindParam(1, $eventTitle);
        $stmt->bindParam(2, $venue);
        $stmt->bindParam(3, $description);
        $stmt->bindParam(4, $start);

        $counter = 4;

        if ($end != '') {
            $counter++;
            $stmt->bindParam($counter, $end);
        }

        if ($capacity != '') {
            $counter++;
            $stmt->bindParam($counter, $capacity);

            $counter++;
            $stmt->bindParam($counter, $capacity);
        }

        $stmt->execute();

        $eventId = $dbh->lastInsertId();

        $stmt = $dbh->prepare("INSERT INTO EVENT_CATEGORY (EVENT_ID, CATEGORY_ID) VALUES (?,?)");
        $stmt->bindParam(1, $eventId);
        $stmt->bindParam(2, $_POST['categories']);

        $stmt->execute();
        $dbh->commit();

        $eventHome = "eventadmin.php";
        header('Location: ' . $eventHome);

    } catch (Exception $e) {
        errorHandler($errorParams);
    }
} else {

    $error = "Error occurred while getting data. Please try later..";
    $errorParams = array($error, "Home", "eventadmin.php", "icon-home", "../error.php");

    $dbh = getConnection($errorParams);
    try {

        $sql_string = "SELECT CATEGORY_ID, NAME FROM CATEGORY WHERE CATEGORY_TYPE='EVENT'";

        $stmt = $dbh->prepare($sql_string);
        $stmt->execute();
        $categories = $stmt->fetchAll();

    } catch (Exception $e) {
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
            <form id="userRegistrationForm" class="form-horizontal" role="form" action="eventadd.php"
                  method="post">
                <fieldset>
                    <legend> Add Event</legend>
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
                            <input type='text' name="inputStart" class="form-control" data-format="YYYY-MM-DD HH:mm" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputEnd">End Date</label>

                        <div class='col-sm-3 input-group date' id='datetimepicker2'>
                            <input type='text' name="inputEnd" class="form-control" data-format="YYYY-MM-DD HH:mm" />
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
                                   value="<?php echo $capacity; ?>" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputCategory">Category</label>

                        <div class="col-sm-10">
                            <select id="categories" name="categories">
                                <?php
                                foreach ($categories as $category) {
                                    $id = $category['CATEGORY_ID'];
                                    $name = $category['NAME'];
                                    $string ="<option value='$id'>$name</option>";
                                    echo $string;
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <br/>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="hidden" name="submitted" value="submitted"/>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

    <script>
        $( document ).ready(function() {
            $( "#navEvent" ).addClass("active");
        });
    </script>
 </div>