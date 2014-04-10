<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "../DbConnect.php";


$error = "Unable to complete the server at this time. Please try later..";
$errorParams = array($error, "Go Back", "./inventory/".basename($_SERVER['PHP_SELF']), "icon-chevron-left", "../error.php");

$dbh = getConnection($errorParams);


$action = $_POST['action'];
$action = str_replace("'", "", $action);
$action = trim($action);
$date = date('Y-m-d');
if ($action == 'Accept') {
    $id = $_POST['id'];
    echo 'Accept';
    $query = "UPDATE ITEM SET STATUS ='ACCEPTED',ARRIVE_DATE = ? WHERE ITEM_ID = ? ";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(1, $date);
    $stmt->bindParam(2, $id);
    $stmt->execute();
}
if ($action == 'Reject') {
    $id = $_POST['id'];
    echo 'Reject';
    $query = "DELETE FROM ITEM WHERE ITEM_ID = ? ";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(1, $id);
    $stmt->execute();
}
if ($action == 'populate') {
    $parent = $_POST['category_id'];
    $parent = (int) trim($parent);
    // echo gettype($parent).$parent;
    $query = "Select NAME,CATEGORY_ID FROM CATEGORY C WHERE C.CATEGORY_TYPE='Inventory' and LEVEL = 2 and PARENT =" . $parent . " ORDER BY NAME ";
    $stmt = $dbh->prepare($query);
    // $stmt->bindParam(1, $parent);
    $stmt->execute();
    $result = $stmt->fetchAll();
    echo '<label class="inline"> Sub Category &nbsp;</label>';
    foreach ($result as $rows) {
        echo '<label class="checkbox-inline">';
        echo '<input type="checkbox" class="sub-category" value="' . $rows['CATEGORY_ID'] . '">' . $rows['NAME'] . '</label> ';
    };
}
if ($action == 'update') {
    $itemID = trim($_POST['itemID']);
    $arriveDate = trim($_POST['arriveDate']);
    $tempDate = strtotime($arriveDate);
    $inputdate = date('Y-m-d', $tempDate);
    //echo $inputdate;
    $warehouse_id = trim($_POST['warehouse']);
    $quantity = trim($_POST['quantity']);
    try {
        $query1 = "UPDATE ITEM SET STORE_IN = ?, ARRIVE_DATE = ?,QUANTITY = ?  WHERE ITEM_ID = ? ";
        $stmt1 = $dbh->prepare($query1);
        $stmt1->bindParam(1, $warehouse_id);
        $stmt1->bindParam(2, $inputdate);
        $stmt1->bindParam(3, $quantity);
        $stmt1->bindParam(4, $itemID);
        $stmt1->execute();
        //$dbh->commit();
        echo 'Information Updated';
    } catch (Exception $e) {
        print_r($e . 'Errors');
    }
}
if ($action == 'Search') {
    $items = array();
    $search_keys = $_POST['search_val'];
    $keys = explode(",", $search_keys);
//    $startDate = trim($_POST['startDate']);
//    $temp = strtotime($startDate);
//    $startDate = date('Y-m-d', $temp);
//    $endDate = trim($_POST['endDate']);
//    $temp = strtotime($endDate);
//    $endDate = date('Y-m-d', $temp);
    $query = "Select WAREHOUSE_ID, NAME FROM WAREHOUSE";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $option_rows = $stmt->fetchAll();
    foreach ($keys as $key => $value) {
        $value = trim($value);
        $items[$key] = $value;
    }
    $count = 10;
    //print_r($startDate);
    //print_r($endDate);
    $parent_cat = $items[0];
    array_shift($items);
    //print_r($subCat);
    //print_r($items);
    echo '<h4 class="text-primary"> Search Results </h4>
            <table class="table table-striped  table-condensed table-responsive">
            <thead>
            <tr>
            <th>ITEM ID</th>
            <th>ITEM Name</th>
            <th>DESCRIPTION</th>
            <th>WAREHOUSE</th>
            <th>ARRIVAL_DATE</th>
            <th>QUANTITY</th>
            <th>UPDATE</th>
            </tr>
            </thead>
            <tbody> ';
    $output = '';
    foreach ($items as $key => $value) {
        $query = "SELECT ITEM_ID, NAME, DESCRIPTION,STORE_IN,ARRIVE_DATE,QUANTITY FROM ITEM WHERE STATUS='ACCEPTED' and  ";
//        if ($startDate != '') {
//            $query = $query . ' ARRIVE_DATE >= ' . $startDate . ' and ';
//        }
//        if ($endDate != '') {
//            $query = $query . ' ARRIVE_DATE <= ' . $endDate . ' and ';
//        }
        $query = $query . ' CLASSIFICATION = ?';
        $stmt3 = $dbh->prepare($query);
        $stmt3->bindParam(1, $value);
        $stmt3->execute();
        $res = $stmt3->fetchAll();
        foreach ($res as $rows) {
            $options = "";
            foreach ($option_rows as $option => $values) {
                
                if ($values['WAREHOUSE_ID'] == $rows['STORE_IN']) {
                    $options.="<option  value=\"" . $values['WAREHOUSE_ID'] . "\" selected >" . $values['NAME'] . "</options>";
                } else {
                    $options.="<option value=\"" . $values['WAREHOUSE_ID'] . "\">" . $values['NAME'] . "</options>";
                }
            }
            echo '<tr>' . '<td>' . $rows['ITEM_ID'] .
            '</td>' . '<td>' . $rows['NAME'] .
            '</td>' . '<td>' . $rows['DESCRIPTION'] .
            '</td>' . '<td><select class="form-control warehouse" name="warehouse">' . $options .
            '</select></td><td id="arrivedate"><input type="date" class="arriveDate" name="ArriveDate" value="' . $rows['ARRIVE_DATE'] . '"/></td>' .
            '<td><input type="number" class="quantity" name="quantity" value="' . $rows['QUANTITY'] . '"/></td>' .
            '<td><button type="submit" class="update">Update</button> </td>' . '</tr>';
        }
    }
}
?>
