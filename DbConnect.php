<?php

function errorHandler(&$errorParams)
{

    $_SESSION['errorMsg'] = $errorParams[0];
    $_SESSION['btn'] = $errorParams[1];
    $_SESSION['back'] = $errorParams[2];
    $_SESSION['ico'] = $errorParams[3];

    $errorPage = $errorParams[4];

    header('Location: ' . $errorPage);
    die();
}

function &getConnection(&$errorParams)
{
    $host = "localhost";
    $port = "8889";
    $dbname = "iusm_2";
    $user = "root";
    $pass = "root";
//  $host = "silo.cs.indiana.edu";$port="3306";$dbname="b561f13_sikachar";$user="b561f13_sikachar";$pass="ADB16";

    try {
        $dbh = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        echo "Error : ". $e;
        if ($errorParams != null) {
            errorHandler($errorParams);
        } else {
            $errorMsg = "Unable to complete the operation at this time. Please try later..";
            $errorParams = array($errorMsg, "Home", "index.php", "icon-home");
            errorHandler($errorParams);
        }
    }
    return $dbh;
}