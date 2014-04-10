<?php

include "../DbConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_ALL;
$level = "sub";
include_once('../session_check.php');

$error = "Unable to complete the operation at this time. Please try later..";
$errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left", "../error.php");
$dbh = getConnection($errorParams);

try {
    $query = "Select ITEM_ID,NAME,DESCRIPTION,CLASSIFICATION,QUANTITY FROM ITEM T WHERE T.STATUS='PENDING' ";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();

    $query1 = "Select NAME,CATEGORY_ID FROM CATEGORY C WHERE C.CATEGORY_TYPE='Inventory' and LEVEL = 1 ORDER BY NAME ";
    $stmt1 = $dbh->prepare($query1);
    $stmt1->execute();
    $result1 = $stmt1->fetchAll();

} catch (Exception $e) {
    errorHandler($errorParams);
}

$title = "Inventory Admin Page";
include_once('../header.php');
?>

<div id="content">
    <div class="input-group">
        <h4 class="text-primary">Search Inventory</h4>
        <?php
        //    $query1 = "Select NAME,CATEGORY_ID FROM CATEGORY C WHERE C.CATEGORY_TYPE='Inventory' and LEVEL = 1 ORDER BY NAME ";
        //    $stmt1 = $dbh->prepare($query1);
        //    $stmt1->execute();
        //    $result1 = $stmt1->fetchAll();
        echo '<label class="inline"> Furniture Category &nbsp;</label>';
        foreach ($result1 as $rows) {
            echo '<label class="radio-inline">';
            echo '<input type="radio" name="parent" value="' . $rows['CATEGORY_ID'] . '">' . $rows['NAME'] . '</label> ';
        }
        ?>
        <div id="category-2">&nbsp;</div>
        <!--    </br><label class="inline"> START DATE &nbsp;</label>
            <input type="date" id="startdate" class="inline" name="starttime" placeholder="2013-01-01" />
            <label class="inline"> &nbsp; END DATE &nbsp;</label>
            <input type="date" id="enddate" class="inline" name="starttime" placeholder="2013-01-01" />-->

        <br>
        <button type="button" id="search" class="btn btn-default">Search</button>
    </div>
    <div id="search-results">&nbsp;</div>


    <h4 class="text-primary"> Pending Approvals </h4>
    <table class="table table-striped  table-condensed table-responsive">
        <thead>
        <tr>
            <th>ITEM ID</th>
            <th>ITEM Name</th>
            <th>DESCRIPTION</th>
            <th>QUANTITY</th>
            <th>ACTION</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($result as $rows) {
            echo '<tr>';
            echo '<td>' . $rows['ITEM_ID'] . '</td>' . '<td>' . $rows['NAME'] . '</td>' . '<td>' . $rows['DESCRIPTION'] . '</td>' . '<td>' . $rows['QUANTITY'] . '</td>';
            echo '<td><button type="submit" class="accept" >Accept <span class="glyphicon glyphicon-ok"></span></button>';
            echo '<button type="submit" class="remove">Reject <span class="glyphicon glyphicon-remove"></span></button></td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(function () {
        $('.datepicker').datepicker({
            format: 'mm-dd-yyyy'
        });
    });
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

    $('.accept').click(function () {
        var itemID = $(this).closest('tr').find("td:first").html();
        var url = "invent_submit.php";
        var ajax = InitAjax();
        ajax.open("POST", url, true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send("id=" + itemID + "&action= Accept");
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                // alert(itemID);
                alert(ajax.responseText);
            }
        }
        $(this).closest('tr').remove();
    });
    $('.remove').click(function () {
        var itemID = $(this).closest('tr').find("td:first").html();
        var url = "invent_submit.php";
        var ajax = InitAjax();
        ajax.open("POST", url, true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send("id=" + itemID + "&action= Reject");
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                //alert(itemID);
                alert(ajax.responseText);
            }
        }
        $(this).closest('tr').remove();
    });

    function update() {
        var allVals = [];
        $("input:checked").each(function () {
            allVals.push($(this).val());

        });

        return(allVals);
    }

    $("#search").click(function () {
        var val = update();
        var url = "invent_submit.php";
        var ajax = InitAjax();
        var startDate = $("#startdate").val();
        var endDate = $("#enddate").val();
        //alert(startDate);
        //alert(endDate);

        var sres = document.getElementById("search-results");
        ajax.open("POST", url, true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send("search_val=" + val + "&startDate=" + startDate + "&endDate=" + endDate + "&action= Search");
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4 && ajax.status == 200) {
                // alert(itemID);
                sres.innerHTML = ajax.responseText;
                // alert(ajax.responseText);
            }
        }

        $('.update').click(function () {
            //alert(1);
            var arriveDate = $(this).closest('tr').find('td:eq(4)').find('.arriveDate').val();
            var warehouse = $(this).closest('tr').find('td:eq(3)').find('.warehouse').val();
            var quantity = $(this).closest('tr').find('td:eq(5)').find('.quantity').val();
            var itemID = $(this).closest('tr').find('td:eq(0)').html();
            ajax.open("POST", url, true);
            ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            ajax.send("itemID=" + itemID + "&arriveDate=" + arriveDate + "&warehouse=" + warehouse + "&quantity=" + quantity + "&action= update");
            ajax.onreadystatechange = function () {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    alert(ajax.responseText);
                }
            }
            // window.location.assign("./updateItems.php");
        });
    });

    $('input:radio[name="parent"]').change(
        function () {

            if ($(this).is(':checked')) {
                var val = $(this).val();
                var url = "invent_submit.php";
                var ajax = InitAjax();
                var sres = document.getElementById("category-2")
                ajax.open("POST", url, true);
                ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                ajax.send("category_id=" + val + "&action= populate");
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4 && ajax.status == 200) {
                        sres.innerHTML = ajax.responseText;
                    }
                }
            }
        });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $("#navInventory").addClass("active");
    });
</script>

<?php
include_once('../footer.php');
?>
