<?php

include "../DBConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_ADMIN_ONLY;
$level = "sub";
include_once('../session_check.php');

$userExist = false;
$email = '';
$phone = '';
$username = '';
$password = '';
$fname = '';
$mname = '';
$lname = '';
$address_1 = '';
$city = '';
$zipCode = '';
$nationality = '';

if (isset($_POST['submitted'])) {

    $email = $_POST['inputEmail'];
    $phone = $_POST['inputPhone'];
    $username = $_POST['inputUsername'];
    $password = crypt($_POST['inputPassword'], "123abc");
    $fname = $_POST['inputFname'];
    $mname = $_POST['inputMname'];
    $lname = $_POST['inputLname'];
    $address_1 = $_POST['inputAddress1'];
    $city = $_POST['inputCity'];
    $state = $_POST['inputState'];
    $zipCode = $_POST['inputZipcode'];
    $nationality = $_POST['inputNationality'];
    $date = date('Y-m-d');
    $userId = uniqid();
    $userType = 'ADMIN';

    $error = "Error occurred while getting data. Please try later..";
    $errorParams = array($error, "Home", "eventadmin.php", "icon-home", "../error.php");

    $dbh = getConnection($errorParams);

    try {
        $dbh->beginTransaction();

        $stmt = $dbh->prepare("INSERT INTO PEOPLE (PERSON_ID, EMAIL_ID, PHONE_NUMBER, FIRST_NAME, MIDDLE_NAME, LAST_NAME,
                                   NATIONALITY, ADDRESS, ADDR_CITY, ZIPCODE, ADDR_STATE, CREATE_DATE, EFFECTIVE_START,
                                   LOGIN_NAME, PASSWORD, USER_TYPE)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $userId);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $phone);
        $stmt->bindParam(4, $fname);
        $stmt->bindParam(5, $mname);
        $stmt->bindParam(6, $lname);
        $stmt->bindParam(7, $nationality);
        $stmt->bindParam(8, $address_1);
        $stmt->bindParam(9, $city);
        $stmt->bindParam(10, $zipCode);
        $stmt->bindParam(11, $state);
        $stmt->bindParam(12, $date);
        $stmt->bindParam(13, $date);
        $stmt->bindParam(14, $username);
        $stmt->bindParam(15, $password);
        $stmt->bindParam(16, $userType);
        $stmt->execute();

        $dbh->commit();

        $regSuccess = "admin.php";
        header('Location: ' . $regSuccess);

    } catch (Exception $e) {
        $dbh->rollBack();

        if (preg_match("/LOGIN_NAME/", $e->getMessage())) {
            $userExist = true;
        } else {
            errorHandler($errorParams);
        }
    }
}

$title = "Add Administrator";
include_once('../header.php');
?>

    <div id="content">
        <nav class="navbar navbar-left" role="navigation">
            <ul class="nav navbar-inverse">
                <li class="active"><a href="admin.php">Manage Admin Users</a></li>
                <li><a href="adminadd.php">Add Admin User</a></li>
            </ul>
        </nav>

            <div class="row">
                <div class="col-md-10">
                    <?php
                    if ($userExist == true) {
                        echo "<div class=\"alert alert-danger\">Username taken. Please try a different username.</div>";
                    }
                    ?>
                    <form id="userRegistrationForm" class="form-horizontal" role="form" action="adminadd.php"
                          method="post">
                        <fieldset>
                            <legend> Admin Registration</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputEmail">Email</label>

                                <div class="col-sm-10">
                                    <input type="text" id="inputEmail" name="inputEmail" placeholder="Email"
                                           value="<?php echo $email; ?>" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputPhone">Phone</label>

                                <div class="col-sm-10">
                                    <input type="text" id="inputPhone" name="inputPhone" class="form-control bfh-phone"
                                           data-format="+1 (ddd) ddd-dddd" placeholder="Phone"
                                           value="<?php echo $phone; ?>"
                                           required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputUsername">Username</label>

                                <div class="col-sm-10">
                                    <input type="text" id="inputUsername" name="inputUsername"
                                           placeholder="Username" value="<?php echo $username; ?>" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputPassword">Password</label>

                                <div class="col-sm-10">
                                    <input type="password" id="inputPassword" name="inputPassword"
                                           placeholder="Password"
                                           required>

                                    <p class="help-block">8-32 characters in length.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputFname">First Name</label>

                                <div class="col-sm-10">
                                    <input type="text" id="inputFname" name="inputFname" value="<?php echo $fname; ?>"
                                           required/>
                                    </input>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputMname">Middle Name</label>

                                <div class="col-sm-10">
                                    <input type="text" id="inputMname" name="inputMname" value="<?php echo $mname; ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputLname">Last Name</label>

                                <div class="col-sm-10">
                                    <input type="text" id="inputLname" name="inputLname" value="<?php echo $lname; ?>"
                                           required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputAddress1">Street Address</label>

                                <div class="col-sm-10">
                                    <input type="text" id="inputAddress1" name="inputAddress1"
                                           placeholder="Street No, Apt No, Suite" value="<?php echo $address_1; ?>"
                                           required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputCity">City</label>

                                <div class="col-sm-10">
                                    <input type="text" id="inputCity" name="inputCity" value="<?php echo $city; ?>"
                                           required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputZipcode">Zip Code</label>

                                <div class="col-sm-10">
                                    <input type="text" id="inputZipcode" name="inputZipcode"
                                           value="<?php echo $zipCode; ?>"
                                           required>
                                    </input>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputState">State</label>

                                <div class="col-sm-10">
                                    <select name="inputState">
                                        <option value="AL">Alabama</option>
                                        <option value="AK">Alaska</option>
                                        <option value="AZ">Arizona</option>
                                        <option value="AR">Arkansas</option>
                                        <option value="CA">California</option>
                                        <option value="CO">Colorado</option>
                                        <option value="CT">Connecticut</option>
                                        <option value="DE">Delaware</option>
                                        <option value="DC">District Of Columbia</option>
                                        <option value="FL">Florida</option>
                                        <option value="GA">Georgia</option>
                                        <option value="HI">Hawaii</option>
                                        <option value="ID">Idaho</option>
                                        <option value="IL">Illinois</option>
                                        <option value="IN">Indiana</option>
                                        <option value="IA">Iowa</option>
                                        <option value="KS">Kansas</option>
                                        <option value="KY">Kentucky</option>
                                        <option value="LA">Louisiana</option>
                                        <option value="ME">Maine</option>
                                        <option value="MD">Maryland</option>
                                        <option value="MA">Massachusetts</option>
                                        <option value="MI">Michigan</option>
                                        <option value="MN">Minnesota</option>
                                        <option value="MS">Mississippi</option>
                                        <option value="MO">Missouri</option>
                                        <option value="MT">Montana</option>
                                        <option value="NE">Nebraska</option>
                                        <option value="NV">Nevada</option>
                                        <option value="NH">New Hampshire</option>
                                        <option value="NJ">New Jersey</option>
                                        <option value="NM">New Mexico</option>
                                        <option value="NY">New York</option>
                                        <option value="NC">North Carolina</option>
                                        <option value="ND">North Dakota</option>
                                        <option value="OH">Ohio</option>
                                        <option value="OK">Oklahoma</option>
                                        <option value="OR">Oregon</option>
                                        <option value="PA">Pennsylvania</option>
                                        <option value="RI">Rhode Island</option>
                                        <option value="SC">South Carolina</option>
                                        <option value="SD">South Dakota</option>
                                        <option value="TN">Tennessee</option>
                                        <option value="TX">Texas</option>
                                        <option value="UT">Utah</option>
                                        <option value="VT">Vermont</option>
                                        <option value="VA">Virginia</option>
                                        <option value="WA">Washington</option>
                                        <option value="WV">West Virginia</option>
                                        <option value="WI">Wisconsin</option>
                                        <option value="WY">Wyoming</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputNationality">Nationality</label>

                                <div class="col-sm-10">
                                    <input type="text" id="inputNationality" name="inputNationality"
                                           value="<?php echo $nationality; ?>"/>
                                </div>
                            </div>
                            <br/>

                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <input type="hidden" name="submitted" value="submitted"/>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
    </div>

<script>
    $( document ).ready(function() {
        $( "#navUserMgt" ).addClass("active");
    });
</script>

<?php
include_once('../footer.php');
?>
