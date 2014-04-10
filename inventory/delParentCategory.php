<?php
include "../DbConnect.php";
 
        
    $error = "Unable to complete the operation at this time. Please try later..";
    $errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left", "../error.php");

    $dbh = getConnection($errorParams);
try {
       $dbh->beginTransaction();
       $category_id= $_POST['category_id'];
        
       $sql = "DELETE FROM CATEGORY WHERE CATEGORY_ID = $category_id";
       $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $sth->execute();
       $dbh->commit();
    
} catch (Exception $ex) {
       errorHandler($errorParams);
}
    
header('Location: update_parent_category.php' );
?>
