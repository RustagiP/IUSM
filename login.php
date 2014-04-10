<?php

include "DbConnect.php";

include_once('constants.php');
$page_type = PAGE_TYPE_ALL;
include_once('session_check.php');

$wrongLogin = false;
$loggedIn = false;
$expired = false;

if (isset($_SESSION['loggedIn'])) {
    $loggedIn = true;
}

// Following logout from top link
if (isset($_GET['logout'])) {
    session_destroy();

    $home = "index.php";

    header('Location: ' . $home);
}

if (isset($_POST['submitted'])) {

    // Following logout from logout page form
    if (isset($_POST['logout'])) {
        session_destroy();

        $home = "index.php";

        header('Location: ' . $home);
    }

    $username = $_POST['inputUsername'];
    $password = crypt($_POST['inputPassword'], "123abc");

    $error = "Unable to log in at this time. Please try later..";
    $errorParams = array($error, "Home", "index.php", "icon-home","error.php");

    $dbh = getConnection($errorParams);

    try {

        $stmt = $dbh->prepare("SELECT PERSON_ID, FIRST_NAME, LOGIN_NAME, USER_TYPE FROM PEOPLE P
                                WHERE P.LOGIN_NAME = ? AND P.PASSWORD = ?");
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $password);

        $stmt->execute();
        $result = $stmt->fetchAll();

        // User exists in the database with given password. Complete the login...
        if (count($result) == 1) {

            $user = $result[0];

            // Check if this admin account is expired..
            if ($user['USER_TYPE'] == ADMIN_USER_TYPE) {
                $stmt = $dbh->prepare("SELECT PERSON_ID FROM PEOPLE P WHERE P.LOGIN_NAME = ? AND
                                        (P.EFFECTIVE_END IS NULL OR P.EFFECTIVE_END > now())");
                $stmt->bindParam(1, $username);
                $stmt->execute();
                $result = $stmt->fetchColumn(0);

                // Admin account is valid..
                if ($result != null) {
                    $_SESSION['loggedIn'] = true;
                    $_SESSION['userName'] = $user['LOGIN_NAME'];
                    $_SESSION['userId'] = $user['PERSON_ID'];
                    $_SESSION['userType'] = $user['USER_TYPE'];
                    $_SESSION['firstName'] = $user['FIRST_NAME'];

                    $home = "./events/eventadmin.php";
                    header('Location: ' . $home);
                } else {
                    $expired = true;
                }
            } else if ($user['USER_TYPE'] == REGISTERED_USER_TYPE) {
                $_SESSION['loggedIn'] = true;
                $_SESSION['userName'] = $user['LOGIN_NAME'];
                $_SESSION['userId'] = $user['PERSON_ID'];
                $_SESSION['userType'] = $user['USER_TYPE'];
                $_SESSION['firstName'] = $user['FIRST_NAME'];

                $home = "./users/home.php";
                header('Location: ' . $home);
            }

        } else {
            $wrongLogin = true;
        }
    } catch (Exception $e) {
        // echo "Error occurred. ". $e->getMessage();
        errorHandler($errorParams);
    }
}

$title = "Login Page";
$level = "root";
include_once('header.php');

?>

    <div class="row">
        <div class="col-md-10">
            <?php
            if (isset($_POST['submitted']) && $wrongLogin == true) {
                ?>
                <div class="alert alert-warning">
                    <p>User name or password not correct. Do not have an account? <a href="./users/registration.php"> Sign
                            Up</a></p>
                </div>
            <?php
            }
            ?>
            <?php
            if (isset($_POST['submitted']) && $expired == true) {
                ?>
                <div class="alert alert-warning">
                    <p>Registration expired. Please contact administrators for renewal..</p>
                </div>
            <?php
            }
            ?>

            <?php
            if ($loggedIn == false) {
                ?>
                <h3> Login </h3>

                <form id="loginForm" class="form-horizontal" role="form" action="login.php" method="post">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputUsername">User Name</label>

                        <div class="controls">
                            <input type="text" id="inputUsername" name="inputUsername" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputPassword">Password</label>

                        <div class="controls">
                            <input type="password" id="inputPassword" name="inputPassword" placeholder="Password"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="hidden" name="submitted" value="submitted"/>
                            <button type="submit" class="btn">Sign in</button>
                            </br>
                            </br>
                            </br>
                            </br>
                            </br>
                            </br>
                            </br>
                            </br>
                            </br>
                            </br>
                            </br>
                        </div>
                    </div>
                </form>

            <?php
            } else {
                ?>

                You are logged in as <?php echo $_SESSION['userName'] ?>. Please logout first.

                <form id="logoutForm" class="form-horizontal" action="login.php" method="post">
                    <div class="control-group">
                        <div class="controls"><label class="checkbox">
                                <input type="hidden" name="submitted" value="submitted"/>
                                <input type="hidden" name="logout" value="submitted"/>
                                <button type="submit" class="btn">Logout</button>
                        </div>
                    </div>
                </form>

            <?php
            }
            ?>

        </div>
    </div>

</div>

<?php
    include_once('footer.php');
?>

