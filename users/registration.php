<?php

include "../DBConnect.php";

include_once('../constants.php');
$level = "sub";
$page_type = PAGE_TYPE_ANON_ONLY;
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
    $userType = 'REGISTERED';

    $error = "Unable to complete the registration at this time. Please try later..";
    $errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left", "../error.php");

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

        $_SESSION['userId'] = $userId;
        $regSuccess = "user_perfs.php";
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

$title = "Registration Page";
include_once('../header.php');

?>

<div class="jumbotron">
    <div class="row">
        <div class="col-md-10">
            <?php
            if ($userExist == true) {
                ?>
                <div class="alert alert-danger">Username taken. Please try a different username.</div>
            <?php
            }
            ?>
            <form id="userRegistrationForm" class="form-horizontal" role="form" action="registration.php"
                  method="post" name="RegistrationForm">
                <fieldset>
                    <legend> Registration</legend>
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
                                   data-format="+1 (ddd) ddd-dddd" placeholder="Phone" value="<?php echo $phone; ?>"
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
                            <input type="password" id="inputPassword" name="inputPassword" placeholder="Password"
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
                                   placeholder="Street No, Apt No, Suite" value="<?php echo $address_1; ?>" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputCity">City</label>

                        <div class="col-sm-10">
                            <input type="text" id="inputCity" name="inputCity" value="<?php echo $city; ?>" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputZipcode">Zip Code</label>

                        <div class="col-sm-10">
                            <input type="text" id="inputZipcode" name="inputZipcode" value="<?php echo $zipCode; ?>"
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
                            <button type="button" class="btn btn-primary" onclick="doSubmit()">Submit</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<script type = "text/javascript">
    function doSubmit()
    {

        var errorMsg = '';
        var emailID = document.RegistrationForm.inputEmail.value;
        var atpos = emailID.indexOf("@");
        var dotpos = emailID.lastIndexOf(".");

        if (atpos<1 || (dotpos - atpos < 2))
            errorMsg = errorMsg + "Email ID is not Valid"+'\n';
        var zip = document.getElementById('inputZipcode');
        if(zip.value.length != 5)
            errorMsg = errorMsg + "Zipcode length should be 5 characters."+'\n';

        var pno = document.RegistrationForm.inputPhone.value;
        var regex = /^\+1 \(\d{3}\) \d{3}\-\d{4}$/g;

        if (!regex.test(pno)) {
            errorMsg = errorMsg + " Phone number should be in format +1 (xxx) xxx-xxxx."+'\n';
        }

        if(zip.value.length != 5)
            errorMsg = errorMsg + "Zipcode length should be 5 characters."+'\n';
        var newp = document.RegistrationForm.inputPassword.value;
        if(newp.length < 8 || newp.length > 32)
            errorMsg = errorMsg + "Password should be atleast 8 characters and atmost 32 characters"+'\n';

        if(errorMsg == '')
            document.RegistrationForm.submit();
        else
            alert("Error - "+errorMsg);
    }

</script>

<?php
include_once('../footer.php');
?>
