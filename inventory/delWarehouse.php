<?php
include "../DbConnect.php";
 
        
    $error = "Unable to complete the registration at this time. Please try later..";
    $errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left");

    $dbh = getConnection($errorParams);
try {
       $dbh->beginTransaction();
       $warehouse_id= $_POST['warehouse_id'];
        
       $sql = "DELETE FROM WAREHOUSE WHERE WAREHOUSE_ID = $warehouse_id";
       $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $sth->execute();
       $dbh->commit();
    
} catch (Exception $ex) {

}
    
header('Location: update_warehouse.php' );
?>
