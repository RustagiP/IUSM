<?php

include "../DbConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_LOGGED_IN_ONLY;
$level = "sub";
include_once('../session_check.php');

$error = "Unable to complete the registration at this time. Please try later..";
$errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left","../error.php");

$dbh = getConnection($errorParams);

try {
    $string = "";
    if(!empty($_POST['eventid']) && !empty($_SESSION["userId"])){
        $id = $_POST['eventid'];
        $uid = $_SESSION['userId'];
        $dbh->beginTransaction();
        //Check availability
        //**Since we added the AVAIL_CAPACITY field, we can simply check if AVAIL_CAPACITY >0 for availability
        $stmt=$dbh->prepare("SELECT TITLE FROM  EVENT WHERE EVENT_ID=? AND AVAIL_CAPACITY > 0");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if(empty($result))
            $string = "<legend>Unable to register! The event has reached the capacity limit.</legend>";
        else{
            try{
                $stmt=$dbh->prepare("INSERT INTO REGISTER(EVENT_ID, USER_ID, REGISTRATION_ID, REGISTRATION_TIME) VALUES(?,?,?,now()) ");
                $result=$stmt->execute(array($id,$uid,crypt(time(),$uid)));
                if($result){
                    $stmt=$dbh->prepare("UPDATE EVENT SET EVENT.AVAIL_CAPACITY = EVENT.AVAIL_CAPACITY-1 WHERE EVENT.EVENT_ID=?");
                    $stmt->execute(array($id));
                    $string = "<legend>You have successfully registered to the event!</legend>
                               <a href=\"../users/home.php\" class=\"btn btn-small btn-info\">
                               <i class=\"icon-chevron-left icon-white\"></i> Home </a>";
                }
                else
                    $string = "<legend>Failed to register the event. Please try again later.</legend>
                               <a href=\"../users/home.php\" class=\"btn btn-small btn-info\">
                               <i class=\"icon-chevron-left icon-white\"></i> Home </a>";
            }catch (Exception $e){
                $string = $stmt->errorInfo()['2'];
               // $string = "<legend>Failed to register due to database error:$string</legend>";
            }

        }
        $dbh->commit();
    }else
        $string = "<legend>Unable to get the parameters.</legend>
                   <a href=\"../users/home.php\" class=\"btn btn-small btn-info\">
                   <i class=\"icon-chevron-left icon-white\"></i> Home </a>";
} catch (Exception $e) {
    echo "Error : ". $e->getMessage();
    errorHandler($errorParams);
}

$title = "Event Registration";
include_once('../header.php');

?>
<!--From here Main Content starts (code Above should remain common through out the webpage)  -->
<?php echo $string ?>


