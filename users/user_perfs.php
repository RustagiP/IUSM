<?php

include_once('../constants.php');

session_start();

// If not this page is accessed via registration page block access..
// (e.g: by directly trying to access this page via typing in the URL)
if (!isset($_SESSION['userId']) || isset($_SESSION['userName'])) {
    if (!isset($_SESSION['userName'])) {
        $home = '../index.php';
        header('Location: ' . $home);
    }
}

include "../DBConnect.php";

$result = array();

if (isset($_POST['submitted'])) {

    $event_types = $_POST['event_types'];

    $error = "Unable to complete the registration at this time. Please try later..";
    $errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left", "../error.php");

    $dbh = getConnection($errorParams);

    $dbh->beginTransaction();
    $isUpdate = $_POST['update'];
    //echo $isUpdate;

    try {
        if($isUpdate)
        {
            $stmt = $dbh->prepare("DELETE FROM USER_PREF WHERE USER_ID = '".$_SESSION['userId']."'");
            $stmt->execute();
        }
        foreach ($event_types as $category_id) {
            /*$stmt = $dbh->prepare("SELECT C.CATEGORY_ID FROM CATEGORY C WHERE C.NAME=? AND C.CATEGORY_TYPE='EVENT'");
            $stmt->bindParam(1, $etype);
            $stmt->execute();

            $category_id = $stmt->fetchAll(PDO::FETCH_COLUMN, 0)[0];*/

            $stmt = $dbh->prepare("INSERT INTO USER_PREF (USER_ID, CATEGORY_ID) VALUES (?, ?)");
            $stmt->bindParam(1, $_SESSION['userId']);
            $stmt->bindParam(2, $category_id);
            $stmt->execute();

        }

        $dbh->commit();

    } catch (Exception $e) {
        $dbh->rollBack();
        errorHandler($errorParams);
    }

    $regSuccess = "regsuccess.php";
    header('Location: ' . $regSuccess);

} else {

    $error = "Unable to complete the registration at this time. Please try later..";
    $errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left");

    $dbh = getConnection($errorParams);

    try {
        $stmt = $dbh->prepare("SELECT C.NAME, C.CATEGORY_ID FROM CATEGORY C WHERE C.CATEGORY_TYPE='EVENT'");

        $stmt->execute();
        $results = $stmt->fetchAll();
        $i=0;
        foreach($results as $row)
        {
            //echo $row[0];
            $result[$i] = $row[0];
            $ids[$i] = $row[1];
            $i++;

        }
        // var_dump($result);
        $stmt = $dbh->prepare("SELECT C.NAME FROM USER_PREF U, CATEGORY C WHERE U.CATEGORY_ID = C.CATEGORY_ID AND U.USER_ID = '".$_SESSION['userId']."'");
        $stmt->execute();
        $existingPrefs = $stmt->fetchAll(PDO::FETCH_COLUMN,0);
        //echo sizeof($ids);


    } catch (Exception $e) {
        errorHandler($errorParams);
    }
}

$title = "Event Preferences";
$level = "sub";
include_once('../header.php');

?>

    <div id="content">
        <div class="row">
            <div class="col-md-10">
                <form id="userPreferencesForm" class="form-horizontal" role="form" action="user_perfs.php" method="post">
                    <fieldset>
                        <legend> Preferences</legend>
                        Tell us what kind of events you are interested in..
                        <div class="form-group">

                            <?php
                            $count = 0;
                            if(sizeof($existingPrefs)>0)
                            {
                                echo "<input type = 'hidden' name='update' value=1/>";
                            }
                            else
                            {
                                echo "<input type = 'hidden' name='update' value=0/>";

                            }
                            foreach ($result as $event_type) {
                                ?>

                                <div class="checkbox col-sm-10 col-sm-offset-0">
                                    <label>
                                        <input name="event_types[]" type="checkbox" value="<?php echo $ids[$count]; ?>" <?php if(in_array($event_type, $existingPrefs)){echo "checked"; } ?> >
                                        <?php echo $event_type; ?>
                                    </label>
                                </div>
                                <?php
                                $count = $count + 1;

                            }
                            ?>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-0">
                                <input type="hidden" name="submitted" value="submitted"/>
                                <button type="submit" class="btn">Finish Registration</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>

    </div>

<?php
include_once('../footer.php');
?>
