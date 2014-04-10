<?php
include "DbConnect.php";
session_start();
$error = "Unable to complete the registration at this time. Please try later..";
$errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left");
$dbh = getConnection($errorParams);
$var = NULL;
$date = date('Y-m-d');
$emailid = $_POST['emailid'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$address = $_POST['address'];
$city = $_POST['city'];
$state = $_POST['state'];
$pickUp = $_POST['pickup'];
$zipCode = $_POST['zipcode'];
$itemName = $_POST['itemName'];
$category = $_POST['category'];
$quantity = $_POST['quantity'];
$description = $_POST['description'];

// getting Event Id for current Event
$query = "SELECT EVENT_ID FROM EVENT WHERE TITLE='Furniture Giveaway' and START_DATE <NOW() and END_DATE>NOW()";
$stmt = $dbh->prepare($query);
$stmt->execute();
$result = $stmt->fetch();
$event_id = $result['EVENT_ID'];

// Getting the PERSON_ID of the person who wants to donate furniture
$query = "SELECT PERSON_ID FROM PEOPLE WHERE EMAIL_ID = ? ;";
$stmt = $dbh->prepare($query);
$stmt->bindParam(1, $emailid);
$stmt->execute();
$result = $stmt->fetch();
$pid = $result['PERSON_ID'];

// if person is not registered store information
if ($pid == null) {
    $cn = 'USA';
    $userType = 'UNREGISTERED';
    $dbh->beginTransaction();
    $stmt = $dbh->prepare("INSERT INTO PEOPLE (PERSON_ID, EMAIL_ID, PHONE_NUMBER, FIRST_NAME, MIDDLE_NAME, LAST_NAME,
                                   NATIONALITY, ADDRESS, ADDR_CITY, ZIPCODE, ADDR_STATE, CREATE_DATE, EFFECTIVE_START,
                                   LOGIN_NAME, PASSWORD, USER_TYPE)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $pid = hash('md4', $emailid);
    $stmt->bindParam(1, $pid);
    $stmt->bindParam(2, $emailid);
    $stmt->bindParam(3, $var);
    $stmt->bindParam(4, $fname);
    $stmt->bindParam(5, $var);
    $stmt->bindParam(6, $lname);
    $stmt->bindParam(7, $cn);
    $stmt->bindParam(8, $address);
    $stmt->bindParam(9, $city);
    $stmt->bindParam(10, $zipCode);
    $stmt->bindParam(11, $state);
    $stmt->bindParam(12, $date);
    $stmt->bindParam(13, $date);
    $stmt->bindParam(14, $var);
    $stmt->bindParam(15, $var);
    $stmt->bindParam(16, $userType);
    $stmt->execute();
    $dbh->commit();
}
$i = 0;
// Create Entry in the items table
$query = "INSERT INTO ITEM (ITEM_ID, NAME, DESCRIPTION, STATUS, STORE_IN, ARRIVE_DATE, CLASSIFICATION,QUANTITY) VALUES(?,?,?,?,?,?,?,?)";
foreach ($itemName as $key => $value) {
    try {
        $status = 'PENDING';
        $dbh->beginTransaction();
        $stmt = $dbh->prepare($query);
        $itemID = $key . rand(0, 1000000);
        $stmt->bindParam(1, $itemID);
        $stmt->bindParam(2, $itemName[$key]);
        $stmt->bindParam(3, $description[$key]);
        $stmt->bindParam(4, $status);
        $stmt->bindParam(5, $var);
        $stmt->bindParam(6, $date);
        $stmt->bindParam(7, $category[$key]);
        $stmt->bindParam(8, $quantity[$key]);
        $stmt->execute();
        $dbh->commit();

        // create entry in the COLLECT_ITEMS_EVENT Table
        $query = "INSERT INTO COLLECT_ITEMS_EVENT (EVENT_ID, PERSON_ID, ITEM_ID, PICKUP_TIME, COMMENT) VALUES(?,?,?,?,?)";
        $dbh->beginTransaction();
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(1, $event_id);
        $stmt->bindParam(2, $pid);
        $stmt->bindParam(3, $itemID);
        $stmt->bindParam(4, $date.$pickUp);
        $stmt->bindParam(5, $var);
        $stmt->execute();
        $dbh->commit();
        //$result = $stmt->fetchAll();
    } catch (Exception $e) {

        $dbh->rollBack();
        print("Sorry about that!!. Please Enter Details again ");
    }

}
if($e == "") {
    print("Your data has been submitted. We will notify you with pickup details");
}
?>

