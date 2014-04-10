<?php

include "../DbConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_ALL;
$level = "sub";
include_once('../session_check.php');

$error = "Unable to retrieve the event data at this time. Please try later..";
$errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left","../error.php");

$dbh = getConnection($errorParams);
try {
    $string = "";
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $stmt = $dbh->prepare("SELECT * FROM EVENT WHERE EVENT_ID = ?");
        $stmt->bindParam(1,$id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if(sizeof($result)<=0) $string = "<legent>This event may be deleted or does not exist. Please try other events.</legent>";
        else{
            //Only take the first result
            $row = $result[0];
            $title = $row['TITLE'];
            $des = $row['DESCRIPTION'];
            $cap = $row['CAPACITY'];
            //$rep = $row['LAST_EVENT']==$row['EVENT_ID']?"Yes":"No";
            $ven = $row['VENUE'];
            $st = $row['START_DATE'];
            $et = $row['END_DATE'];
            $eid = $row['EVENT_ID'];
            $ac = $row['AVAIL_CAPACITY'];
            $string = "<legend>$title</legend>
                        <div class='controls'><label>Title: $title</label></div>
                        <div class='controls'><label>Description: $des</label></div>
                        <div class='controls'><label>Capacity: $cap</label></div>
                        <div class='controls'><label>Available Capacity: $ac</label></div>
                        <div class='controls'><label>Venue: $ven</label></div>
                        <div class='controls'><label>Start Date: $st</label></div>
                        <div class='controls'><label>End Date: $et</label></div>
                        ";
            if(strtotime(date("y-m-d h:i:s"))<strtotime($et) && !empty($_SESSION['userId']) &&$_SESSION['userType']==REGISTERED_USER_TYPE){
                $stmt = $dbh->prepare("SELECT * FROM REGISTER WHERE EVENT_ID=? AND USER_ID =?");
                $stmt->execute(array($id,$_SESSION['userId']));
                $result=$stmt->fetchAll();
                if(empty($result))
                    $string = $string . "<form action='eventreg.php' method='post'><input type='hidden' name='eventid' value='$eid'/><input type='submit' value='Register!'/></form>";
                else{
                    $string = $string . "<legend>You have registered for the event. </legend><form action='eventdereg.php' method='post'><input type='hidden' name='eventid' value='$eid'/><input type='submit' value='Deregister?'/></form>";
                }
            }

            if($_SESSION['userType']==ADMIN_USER_TYPE){
                $stmt = $dbh->prepare("SELECT * FROM REGISTER WHERE EVENT_ID=?");
                $stmt->execute(array($id));
                $result = $stmt->fetchAll();

                $string .= "<hr/><hr/><div class=\"row jumbotron\"><table width='95%'><fieldset><tr><td>User</td><td>Registration Id</td><td>Registration Date</td><td>Action</td></tr>";
                foreach($result as $row){
                    $title = $row['USER_ID'];
                    $vn = $row['REGISTRATION_ID'];
                    $st = $row['REGISTRATION_TIME'];
                    $string = $string . "<tr><td>$title</td><td> $vn</td><td>$st</td><td>Possible Actions</td></tr>";
                }
                $string = $string."</fieldset></table></div>";
            }
        }
    }else
        $string = "<legend>Event details not avaialble..</legend>";
} catch (Exception $e) {
    errorHandler($errorParams);
}

$title = "Event Details";
include_once('../header.php');

?>
    <!--From here Main Content starts (code Above should remain common through out the webpage)  -->
    <?php echo $string ?>


