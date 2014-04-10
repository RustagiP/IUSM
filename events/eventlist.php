<?php

include_once('../constants.php');
$page_type = PAGE_TYPE_ALL;
$level = "sub";
include_once('../session_check.php');

$title = "Events";
include_once('../header.php');

if (!isset($_SESSION['userId'])) echo "<div>Please log in to participate in the events!</div>";

include "../DbConnect.php";

$error = "Unable to get Categories. Please try later..";
$errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left");

try {
    $dbh = getConnection($errorParams);
    $stmt = $dbh->prepare("SELECT * FROM CATEGORY WHERE CATEGORY_TYPE = 'EVENT'");
    //Problem for the category system:
    //It's a tree structure, but it requires additional maintenance.
    //Can internal node be a search criteria? If something has a relationship with a leaf node, should it have relationship with all the parent node?
    //Because the complexity above, the categories are currently treated and lists rather than trees.
    $stmt->execute();
    $result = $stmt->fetchAll();
    $options = "";
    foreach ($result as $row) {
        $name = $row['NAME'];
        $cid = $row['CATEGORY_ID'];
        $options .= "<input type='checkbox' name='category_$cid' value='$cid' onclick='checkbox_click(\"category_$cid\",\"$cid\")'>$name</input>&nbsp;&nbsp;";
    }

    if (!empty($_SESSION['userType']) && $_SESSION['userType'] == ADMIN_USER_TYPE) {
        $options .= "<hr/>";
        $options .= "<input type='checkbox' onclick='checkbox_click(\"status_approve\",\"approved\")'>APPROVED</input>";
        $options .= "<input type='checkbox' onclick='checkbox_click(\"status_pending\",\"pending\")'>PENDING</input>";
        $options .= "<input type='checkbox' onclick='checkbox_click(\"status_rejected\",\"rejected\")'>REJECTED</input>";
        $options .= "<input type='checkbox' onclick='checkbox_click(\"status_rejected\",\"*\")'>OTHER</input>";
    }
} catch (Exception $e) {
    errorHandler($errorParams);
}
?>

<script type="text/javascript">
    var option = "";
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
        var show = document.getElementById("show_event");
        var ajax = InitAjax();

        var title = document.getElementById("title").value;
        var starttime = document.getElementById("starttime").value;
        var endtime = document.getElementById("endtime").value;
        var venue = document.getElementById("venue").value;

        ajax.open("POST", url, true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send("title=" + title + "&starttime=" + starttime + "&endtime=" + endtime + "&venue=" + venue + option);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                show.innerHTML = ajax.responseText;
            }
        }
    }

    function checkbox_click(key, value) {
        var index = option.indexOf("&" + key);
        if (index < 0) {
            option += "&" + key + "=" + value;
        } else {
            var idx = option.indexOf("&", index + 1);
            var rest = "";
            if (idx > 0) rest = option.substring(idx);
            option = option.substring(0, index) + rest;
        }
    }
</script>
<div id="content">
    <div class="row">
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h4>Event Search</h4></div>
			<div class="col"-sm-2">Nothing you like? <a href="eventpropose.php">Propose</a> one!</div>
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
                                    <input id="starttime" type='text' name="starttime" placeholder="2013-01-01"
                                           class="form-control" data-format="YYYY-MM-DD"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="endtime">End Before</label>

                                <div class='col-sm-3 input-group date' id='datetimepicker2'>
                                    <input id="endtime" type='text' name="endtime" placeholder="2015-01-01"
                                           class="form-control" data-format="YYYY-MM-DD"/>
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
                                        $("#datetimepicker1").on("change.dp", function (e) {
                                            $('#datetimepicker2').data("DateTimePicker").setStartDate(e.date);
                                        });
                                        $("#datetimepicker2").on("change.dp", function (e) {
                                            $('#datetimepicker1').data("DateTimePicker").setEndDate(e.date);
                                        });
                                    });
                                </script>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="venue">Venue</label>

                                <div class="col-sm-5">
                                    <input type="text" id="venue" name="venue" placeholder="somewhere"/>
                                </div>
                                <div class="col-sm-5"></div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="venue">Event Type</label>
                                <?php echo $options ?>
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
                    <div id="show_event" class="panel panel-default">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#navEvent").addClass("active");
    });
</script>

<?php
include_once('../footer.php');
?>
