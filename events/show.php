<?php

include "../DbConnect.php";

include_once('../constants.php');

session_start();

$error = "Unable to complete the registration at this time. Please try later..";
$errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left","../error.php");

try {
    $dbh = getConnection($errorParams);
    
    $title = $_POST['title'];
    $starttime = $_POST['starttime'];
    $endtime = $_POST['endtime'];
    $venue = $_POST['venue'];
    $query = "";
    if(!empty($title)) $query=$query."TITLE like '%".$title."%'";
    if(!empty($starttime)) {
        if(!empty($query)) $query=$query." AND ";
        $query=$query."START_DATE > "."'".$starttime."'";
    }
    if(!empty($endtime)) {
        if(!empty($query)) $query=$query." AND ";
        $query=$query."END_DATE < "."'".$endtime."'";
    }
    if(!empty($venue)) {
        if(!empty($query)) $query=$query." AND ";
        $query=$query."VENUE like '%".$venue."%'";
    }
    $options = "";
    $status="";
    foreach($_POST as $k=>$v){
        if(strstr($k,"category_")){
            if(!empty($options)) $options.=" OR ";
            $options .= "CATEGORY_ID = " . $v ;
        }
        if(strstr($k,"status_")){
            if(!empty($status)) $status.=" OR ";
            $status .= "STATUS = '" . $v."'" ;
        }
    }

    if(empty($query)) $query="TRUE";
    if(!empty($status)) $query.= " AND ( ".$status.")";
    else if(!isset($_SESSION['userType']) || $_SESSION['userType']==REGISTERED_USER_TYPE) $query .= " AND STATUS='APPROVED'";

    if(empty ($options))
        $query="SELECT * FROM EVENT WHERE ".$query;
    else
        $query="SELECT EVENT.* FROM EVENT,EVENT_CATEGORY WHERE EVENT.EVENT_ID=EVENT_CATEGORY.EVENT_ID AND ".$query." AND ( ".$options.")";
    // echo $query;

    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $string = "";
    if(sizeof($result)<=0)
        echo "<legend>No results found</legend>";
    else{
        if (isset($_SESSION['userType'])) {
            if ($_SESSION['userType'] == ADMIN_USER_TYPE) {
                $string = "<table class='table table-striped'><tbody><tr><th>#</th><th>Event</th><th>Venue</th><th>Start Date</th>
                            <th>End Date</th><th>Capacity</th><th>Available Capacity</th><th>Actions</th></tr>";

                $counter = 1;
                foreach($result as $row){
                    $id = $row['EVENT_ID'];
                    $title = $row['TITLE'];
                    $vn = $row['VENUE'];
                    $st = $row['START_DATE'];
                    $et = $row['END_DATE'];
                    $c = $row['CAPACITY'];
                    $ac = $row['AVAIL_CAPACITY'];
                    $string = $string . "<tr><td>$counter</td><td><a href='eventdetal.php?id=$id'>$title</a></td>";
                    $string = $string . "<td> $vn</td><td>$st</td><td>$et</td><td>$c</td><td>$ac</td>
                                        <td>
                                            <button type=\"submit\" class=\"btn btn-xs btn-primary\"
                                            onclick=\"window.location.replace('eventedit.php?id=$id')\">
                                                <span class=\"glyphicon glyphicon-pencil\"></span>
                                                    Edit
                                                </button>
                                        </td>
                                        </tr>";
                    $counter++;
                }
            } else {
                $string = "<table class='table table-striped'><tbody><tr><th>#</th><th>Event</th><th>Venue</th><th>Start Date</th><th>End Date</th><th>Available Capacity</th></tr>";

                $counter = 1;
                foreach($result as $row){
                    $id = $row['EVENT_ID'];
                    $title = $row['TITLE'];
                    $vn = $row['VENUE'];
                    $st = $row['START_DATE'];
                    $et = $row['END_DATE'];
                    $ac = $row['AVAIL_CAPACITY'];
                    $string = $string . "<tr><td>$counter</td><td><a href='eventdetal.php?id=$id'>$title</a></td>";
                    $string = $string . "<td> $vn</td><td>$st</td><td>$et</td><td>$ac</td></tr>";
                    $counter++;
                }
            }
        } else {
            $string = "<table class='table table-striped'><tbody><tr><th>#</th><th>Event</th><th>Venue</th><th>Start Date</th><th>End Date</th><th>Available Capacity</th></tr>";

            $counter = 1;
            foreach($result as $row){
                $id = $row['EVENT_ID'];
                $title = $row['TITLE'];
                $vn = $row['VENUE'];
                $st = $row['START_DATE'];
                $et = $row['END_DATE'];
                $ac = $row['AVAIL_CAPACITY'];
                $string = $string . "<tr><td>$counter</td><td><a href='eventdetal.php?id='".$id."'>".$title."</a></td>";
                $string = $string . "<td> $vn</td><td>$st</td><td>$et</td><td>$ac</td></tr>";
                $counter++;
            }

        }
        $string = $string."</tbody></table>";
        echo $string;
    }
//    $stmt = $dbh->prepare("SELECT * FROM EVENT WHERE EVENT_ID = ?");
 //   $stmt->bindParam(1, $_GET['id']);
 //   $stmt->execute();
 //   echo "title".$_POST['title']." starttime".$_POST['starttime']." endtime".$_POST['endtime']." venue".$_POST['venue'];
} catch (Exception $e) {
    errorHandler($errorParams);
}
?>
