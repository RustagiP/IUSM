<?php

include "../DbConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_LOGGED_IN_ONLY;
$level = "sub";
include_once('../session_check.php');

$error = "Unable to complete the deregistration at this time. Please try later..";
$errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left","../error.php");

$dbh = getConnection($errorParams);
$dbh->beginTransaction();
try {
    $string = "";
    if(!empty($_POST['eventid']) && !empty($_SESSION["userId"])){
        $id = $_POST['eventid'];
        $uid = $_SESSION['userId'];
        try{
            $stmt=$dbh->prepare("DELETE FROM REGISTER WHERE EVENT_ID = ? AND USER_ID = ?");
            $result=$stmt->execute(array($id,$uid));
            if($result){
                $stmt=$dbh->prepare("UPDATE EVENT SET EVENT.AVAIL_CAPACITY = EVENT.AVAIL_CAPACITY+1 WHERE EVENT.EVENT_ID=?");
                $stmt->execute(array($id));
                $string = "<legend>You have successfully unregistered from the event!</legend>
                   <a href=\"../users/home.php\" class=\"btn btn-small btn-info\">
                   <i class=\"icon-chevron-left icon-white\"></i> Home </a>";
            }else
                $string = "<legend>Failed to deregister the event.</legend>
                   <a href=\"../users/home.php\" class=\"btn btn-small btn-info\">
                   <i class=\"icon-chevron-left icon-white\"></i> Home </a>";
        }catch (Exception $e){
            $string = $stmt->errorInfo()['2'];
           // $string = "<legend>Failed to register due to database error:$string</legend>";
        }
    }else
        $string = "<legend>Unable to get the parameters.</legend>";
} catch (Exception $e) {
    errorHandler($errorParams);
}
$dbh->commit();
$title = "Event Registration";
include_once('../header.php');

?>
<!--From here Main Content starts (code Above should remain common through out the webpage)  -->
<?php echo $string ?>


