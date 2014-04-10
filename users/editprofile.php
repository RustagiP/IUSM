<?php

include "../DbConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_LOGGED_IN_ONLY;
$level = 'sub';
include_once('../session_check.php');


//$userExist = false;

$email = '';
$phone = '';
$username = $_SESSION['userName'];
$password = '';
$newPassword = '';
$fname = '';
$mname = '';
$lname = '';
$address_1 = '';
$city = '';
$zipCode = '';
$nationality = '';


if (isset($_POST['submitted'])) {



    $email = $_POST['inputEmail'];
    $phone = $_POST['inputPhoneNo'];
    //$username = $_POST['inputUsername'];
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
    $newPassword = crypt($_POST['Newpassword'], "123abc");


    $error = "Error occurred while getting data. Please try later..";
    $errorParams = array($error, "Back", "editprofile.php", "icon-home", "../error.php");
    $dbh = getConnection($errorParams);

    $stmt = $dbh->prepare("SELECT * from PEOPLE where LOGIN_NAME = '".$username."'");
    $stmt->execute();
    $CurrentPassword = $stmt->fetchColumn(16);
    if ($CurrentPassword != $password)
    {
        $error = "User name and password do not match..";
        $errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left",
            "../error.php");
        errorHandler($errorParams);
    }

    try {
        $dbh->beginTransaction();
        $stmt = $dbh->prepare("UPDATE PEOPLE SET EMAIL_ID=?,PHONE_NUMBER=?,FIRST_NAME=?,MIDDLE_NAME=?,LAST_NAME=?,NATIONALITY=?,
                                ADDRESS=?,ADDR_CITY=?,ZIPCODE=?,ADDR_STATE=?,CREATE_DATE=?,EFFECTIVE_START=?,PASSWORD=?,USER_TYPE=? WHERE LOGIN_NAME=?");



        $stmt->bindParam(1, $email);
        $stmt->bindParam(2, $phone);
        $stmt->bindParam(3, $fname);
        $stmt->bindParam(4, $mname);
        $stmt->bindParam(5, $lname);
        $stmt->bindParam(6, $nationality);
        $stmt->bindParam(7, $address_1);
        $stmt->bindParam(8, $city);
        $stmt->bindParam(9, $zipCode);
        $stmt->bindParam(10, $state);
        $stmt->bindParam(11, $date);
        $stmt->bindParam(12, $date);
        // $stmt->bindParam(14, $username);
        $stmt->bindParam(13, $newPassword);
        $stmt->bindParam(14, $userType);
        $stmt->bindparam(15, $username);

        $stmt->execute();
        $dbh->commit();

        $regSuccess = "user_perfs.php";
        header('Location: '.$regSuccess);


    } catch (Exception $e) {
        $dbh->rollBack();

        $_SESSION['errorMsg'] = "Error occurred";
        errorHandler($errorParams);
    }



}

$dbh = getConnection($errorParams);
$error = "Unable to edit the profile at this time. Please try later..";
$errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left", "../error.php");

//echo "Session user name ".$username;
$stmt = $dbh->prepare("SELECT * from PEOPLE where LOGIN_NAME = '".$username."'");
$stmt->execute();
$results = $stmt->fetchAll();
//$username = $stmt->fetchColumn(16);
$email  = '';
$firstName = '';
$middleName = '';
$phoneNumber ;
$lastName = '';
$nationality = '';
$address = '';
$addr_city = '';
$zipcode = '';
$addr_state = '' ;
$addr_country = '';
foreach($results as $result){
    $email  = $result[1];
    $firstname = $result[3];
//echo "First Name: ".$firstname;
    $middleName = $result[4];
    $phoneNumber = $result[2];
//echo $phoneNumber;
    $lastName = $result[5];
    $nationality = $result[6];
    $address = $result[7];
    $addr_city = $result[8];
    $zipcode = $result[9];
    $addr_state = $result[10];
//echo $addr_state;
    $addr_country = $result[11];
}

//$stmt->bindParam(1,$username);
//$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


$title = "Edit Profile Page";
include_once('../header.php');

?>
    <html>

    <div class="jumbotron">
        <div class="row">
            <div class="col-md-10">

                <form id="editProfileID" class="form-horizontal" role="form" action="EditProfile.php" method="post" name="EditUserForm">
                    <fieldset>
                        <!--<div class="page-header">
                            <h2><span style="color: rgb(0, 64, 235);">Edit Profile</span></h2></div>  -->
                        <legend> Edit Profile</legend>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputUsername">Username</label>

                            <div class="col-sm-10">
                                <label class="col-sm-2"> <?php echo $username ?> </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword">Current Password</label>

                            <div class="col-sm-10">
                                <input type="password" id="inputPassword" name="inputPassword" required/>
                                <small><span style="font-size:10pt;">8-32 characters in length.</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword">New Password</label>

                            <div class="col-sm-10">
                                <input type="password" id="Newpassword" name="Newpassword" required/>
                                <small><span style="font-size:10pt;">8-32 characters in length.</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword">Confirm New Password</label>

                            <div class="col-sm-10">
                                <input type="password" id="ConfirmNewpassword" name="ConfirmNewpassword" required/>
                                <small><span style="font-size:10pt;">8-32 characters in length.</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputEmail">Email Address</label>

                            <div class="col-sm-10">
                                <input type="text" id="inputEmail" name="inputEmail" value="<?php echo $email ?>" required/>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPhoneNo">Phone Number</label>

                            <div class="col-sm-10">
                                <input type="text" id="inputPhoneNo" name="inputPhoneNo" class="form-control bfh-phone"
                                       data-format="+1 (ddd) ddd-dddd" value="<?php echo $phoneNumber ?>" required/>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputFname">First Name</label>

                            <div class="col-sm-10">
                                <input type="text" id="inputFname" name="inputFname" value="<?php echo $firstname ?>" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputMname">Middle Name</label>

                            <div class="col-sm-10">
                                <input type="text" id="inputMname" name="inputMname" value="<?php echo $middleName ?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputLname">Last Name</label>

                            <div class="col-sm-10">
                                <input type="text" id="inputLname" name="inputLname" value="<?php echo $lastName ?>" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputAddress1">Address</label>

                            <div class="col-sm-10">
                                <input type="text" id="inputAddress1" placeholder="Street No, Apt No, Suite"
                                       name="inputAddress1" value="<?php echo $address ?>" required/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputCity">City</label>

                            <div class="col-sm-10">
                                <input type="text" id="inputCity" name="inputCity" value="<?php echo $addr_city ?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputZipcode">Zip Code</label>

                            <div class="col-sm-10">
                                <input type="text" id="inputZipcode" name="inputZipcode" value="<?php echo $zipcode ?>">
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
                                <input type="text" id="inputNationality" name="inputNationality" value="<?php echo $nationality ?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <input type="hidden" name="submitted" value="submitted"/>
                                <button type="button" class="btn" onclick="doSubmit()">Confirm</button>
                                <button type="submit" class="btn">Cancel</button>
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
            var emailID = document.EditUserForm.inputEmail.value;
            var atpos = emailID.indexOf("@");
            var dotpos = emailID.lastIndexOf(".");

            if (atpos<1 || (dotpos - atpos < 2))
                errorMsg = errorMsg + "Email ID is not Valid"+'\n';
            var zip = document.getElementById('inputZipcode');
            if(zip.value.length != 5)
                errorMsg = errorMsg + "Zipcode length should be 5 characters."+'\n';

            var pno = document.EditUserForm.inputPhoneNo.value;
            var regex = /^\+1 \(\d{3}\) \d{3}\-\d{4}$/g;

            if (!regex.test(pno)) {
                errorMsg = errorMsg + " Phone number should be in format +1 (xxx) xxx-xxxx."+'\n';
            }

            var newp = document.EditUserForm.Newpassword.value;
            var comfnewp = document.EditUserForm.ConfirmNewpassword.value;
            if(newp.length < 8 || newp.length > 32)
                errorMsg = errorMsg + "Password should be atleast 8 characters and atmost 32 characters"+'\n';
            if(newp != comfnewp)
                errorMsg = errorMsg + "Passwords do not match"+'\n';

            if(errorMsg == '')
                document.EditUserForm.submit();
            else
                alert("Error - "+errorMsg);
        }

    </script>
    <script>
        //alert("In script");
        var states = document.EditUserForm.inputState;
        //alert(states.length);
        for(i=0;i<states.length;i++){
            //alert(states.options[i].value);
            if(states.options[i].value == '<?php echo $addr_state ?>')
            {
                states.selectedIndex = i;
                states.options[i].selected;
                //alert("selected");
            }
        }
    </script>

    </html>

<?php
include_once('../footer.php');
?>
