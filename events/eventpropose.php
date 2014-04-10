<?php

include "../DbConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_REGISTERED_ONLY;
$level = "sub";
include_once('../session_check.php');

$eventTitle = '';
$venue = '';
$description = '';
$start = '';
$end = '';
$capacity = '';
if (isset($_POST['submitted'])) {

    $eventTitle = $_POST['inputTitle'];
    $venue = $_POST['inputVenue'];
    $description = $_POST['inputDescription'];
    $start = $_POST['inputStart'];
    $end = $_POST['inputEnd'];
    $capacity = $_POST['inputCapacity'];

    $error = "Error occurred while getting data. Please try later..";
    $errorParams = array($error, "Back", "/iusm/events/eventmgt.php", "icon-home", "../error.php");

    $dbh = getConnection($errorParams);

    try {
        $dbh->beginTransaction();
        $stmt = $dbh->prepare("INSERT INTO EVENT(`TITLE`,`VENUE`,`DESCRIPTION`,`START_DATE`,`END_DATE`,`CAPACITY`,`AVAIL_CAPACITY`,`STATUS`) VALUES (?,?,?,?,?,?,?,'PENDING')");
        $result=$stmt->execute(array($eventTitle, $venue,$description,$start,$end,$capacity,$capacity));

        $dbh->commit();

    } catch (Exception $e) {
        echo "Error : ". $e->getMessage();
        errorHandler($errorParams);
    }
}

$title = "Event Propose Page";
include_once('../header.php');
if(!empty($result))
    echo "<div class='row'><div class='col-md-10'><legend>You have successfully proposed the event.</legend></div></div>";
?>

<div id="content">
    <div class="row">
        <div class="col-md-10">
            <form id="userRegistrationForm" class="form-horizontal" role="form" action="eventpropose.php"
                  method="post">
                <fieldset>
                    <legend> Propose Event</legend>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputTitle">Title</label>

                        <div class="col-sm-10">
                            <input type="text" id="inputTitle" name="inputTitle"
                                   value="" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputPhone">Venue</label>

                        <div class="col-sm-10">
                            <input type="text" id="inputVenue" name="inputVenue" value=""
                                   required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputDescription">Description</label>

                        <div class="col-sm-10">
                            <textarea rows="10" cols="80" id="inputDescription"
                                      name="inputDescription"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputStart">Start Date</label>

                        <div class='col-sm-3 input-group date' id='datetimepicker1'>
                            <input type='text' name="inputStart" value=""  class="form-control" data-format="YYYY-MM-DD HH:mm" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputEnd">End Date</label>

                        <div class='col-sm-3 input-group date' id='datetimepicker2'>
                            <input type='text' name="inputEnd" value="" class="form-control" data-format="YYYY-MM-DD HH:mm" />
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
                                   value=""/>
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
