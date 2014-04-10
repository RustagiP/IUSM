<?php

include "../DbConnect.php";

include_once('../constants.php');
$level = "sub";
$page_type = PAGE_TYPE_ALL;
include_once('../session_check.php');

$error = "Unable to retrieve the event data at this time. Please try later..";
$errorParams = array($error, "Go Back", "./events/eventadmin.php", "icon-chevron-left", "../error.php");

$dbh = getConnection($errorParams);

try {
    $sql_string = "SELECT EVENT_ID, TITLE, VENUE, START_DATE, END_DATE, CAPACITY, AVAIL_CAPACITY
                   FROM EVENT
                   ORDER BY START_DATE DESC";

    $stmt = $dbh->prepare($sql_string);
    $stmt->execute();
    $result = $stmt->fetchAll();

} catch (Exception $e) {
    errorHandler($errorParams);
}

$title = "Event Administration Page";
include_once('../header.php');

?>

<script type="text/javascript">
    function onload() {
        document.getElementId('navEvent').className = "active";
    }

    window.onload = onload();
    //    $( document ).ready(function() {
    //        $( "navEvent").addClass('active');
    //    });
</script>

<script type="text/javascript">
    function InitAjax() {
        var ajax = false;
        try {
            ajax = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajax = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (E) {
                ajax = false;
            }
        }
        if (!ajax && typeof XMLHttpRequest != 'undefined') {
            ajax = new XMLHttpRequest();
        }
        return ajax;
    }

    function getEvents() {
        var url = "show.php";
        var show = document.getElementById("events");
        var ajax = InitAjax();

        var title = document.getElementById("title").value;
        var starttime = document.getElementById("starttime").value;
        var endtime = document.getElementById("endtime").value;
        var venue = document.getElementById("venue").value;

        ajax.open("POST", url, true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send("title=" + title + "&starttime=" + starttime + "&endtime=" + endtime + "&venue=" + venue);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                show.innerHTML = ajax.responseText;
            }
        }
    }

</script>

<div id="content">

    <div class="row">
        <div class="col-md-2">
            <nav class="navbar navbar-left" role="navigation">
                <ul class="nav navbar-inverse">
                    <li><a href="eventadmin.php">Approvals</a></li>
                    <li class="active"><a href="eventmgt.php">Event Management</a></li>
                    <li><a href="eventadd.php">Add Event</a></li>
                </ul>
            </nav>
        </div>
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h4>Event Search</h4></div>

                        <form class="form-horizontal" role="form" action="#" method="post">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="title">Title</label>

                                <div class="col-sm-5">
                                    <input type="text" id="title" name="title" placeholder=" "/>
                                </div>
                                <div class="col-sm-5"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="starttime">Start After</label>

                                <div class='col-sm-3 input-group date' id='datetimepicker1'>
                                    <input id="starttime" type='text' name="starttime" placeholder="2013-01-01" class="form-control" data-format="YYYY-MM-DD" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>

<!--                                <div class="col-sm-5">-->
<!--                                    <input type="date" id="starttime" name="starttime" placeholder="2013-01-01"/>-->
<!--                                </div>-->
<!--                                <div class="col-sm-5"></div>-->
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="endtime">End Before</label>

                                <div class='col-sm-3 input-group date' id='datetimepicker2'>
                                    <input id="endtime" type='text' name="endtime" placeholder="2015-01-01" class="form-control" data-format="YYYY-MM-DD" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>

                                <script type="text/javascript">
                                    $(function () {
                                        $('#datetimepicker1').datetimepicker({
                                            pickTime: false
                                        });
                                        $('#datetimepicker2').datetimepicker({
                                            pickTime: false
                                        });
                                        $("#datetimepicker1").on("change.dp",function (e) {
                                            $('#datetimepicker2').data("DateTimePicker").setStartDate(e.date);
                                        });
                                        $("#datetimepicker2").on("change.dp",function (e) {
                                            $('#datetimepicker1').data("DateTimePicker").setEndDate(e.date);
                                        });
                                    });
                                </script>

                                <!--                                <div class="col-sm-5">-->
<!--                                    <input type="date" id="endtime" name="endtime" placeholder="2015-01-01"/>-->
<!--                                </div>-->
<!--                                <div class="col-sm-5"></div>-->
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="venue">Venue</label>

                                <div class="col-sm-5">
                                    <input type="text" id="venue" name="venue" placeholder="somewhere"/>
                                </div>
                                <div class="col-sm-5"></div>
                            </div>
                            <div class="form-group">
                                <div class="controls col-sm-offset-2">
                                    <input type="button" value="Search" onclick="getEvents()"/>
                                    <input type="reset" value="Reset"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="events" class="panel panel-default">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Venue</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Capacity</th>
                                <th>Available Capacity</th>
                                <th>Actions</th>
                            </tr>
                            <?php
                            $counter = 0;
                            foreach ($result as $user) {
                                $counter++;
                                ?>
                                <tr>
                                    <td><?php echo "$counter"; ?></td>
                                    <td><a href='eventdetal.php?id=<?php echo $user['EVENT_ID']; ?>'><?php echo $user['TITLE']; ?></a></td>
                                    <td><?php echo $user['VENUE']; ?></td>
                                    <td><?php echo $user['START_DATE']; ?></td>
                                    <td><?php echo $user['END_DATE']; ?></td>
                                    <td><?php echo $user['CAPACITY']; ?></td>
                                    <td><?php echo $user['AVAIL_CAPACITY']; ?></td>
                                    <td>
                                        <button type="submit" class="btn btn-xs btn-primary"
                                        onclick="window.location.replace('eventedit.php?id=<?php echo $user['EVENT_ID']; ?>')">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                            Edit
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
