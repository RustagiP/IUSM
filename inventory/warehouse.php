<?php
include "../DbConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_ADMIN_ONLY;
$level = "sub";
include_once('../session_check.php');

if (isset($_POST['submitted'])) {
    $name = $_POST['inputName'];
    $address = $_POST['inputAddress'];
    $city = $_POST['inputCity'];
    $state = $_POST['inputState'];
    $zipCode = $_POST['inputZipcode'];

    $error = "Unable to complete the registration at this time. Please try later..";
    $errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left", "../error.php");

    $dbh = getConnection($errorParams);

    try {
        $dbh->beginTransaction();
        $sql = "SELECT * FROM WAREHOUSE where upper(name) = upper(:wname)";
        //$sql =  "SELECT * FROM WAREHOUSE where upper(name) = upper(:wname)";
        $count = 0;
        //echo $count;  
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':wname' => $name));

        $a = $sth->fetchAll();
        if (count($a) > 0) {
            $regSuccess = "regsuccess.php";
            header('Location: ' . $regSuccess);
        } else {
            $stmt = $dbh->prepare("INSERT INTO WAREHOUSE(NAME,ADDRESS,ZIPCODE,ADDR_CITY,ADDR_STATE) VALUES (?,?,?,?,?)");
            $stmt->bindParam(1, $name);
            $stmt->bindParam(2, $address);
            $stmt->bindParam(3, $zipCode);
            $stmt->bindParam(4, $city);
            $stmt->bindParam(5, $state);

            //$stmt->bindParam(3, $city);
            // $stmt->bindParam(3, $state);


            $stmt->execute();
            $dbh->commit();

            $home = "update_warehouse.php";
            header('Location: ' . $home);
        }
    } catch (Exception $e) {
        $dbh->rollBack();

        errorHandler($errorParams);
    }
}

$title = "Warehouse Maintenance";
include_once('../header.php');

?>

<div id="content">

    <nav class="navbar navbar-left" role="navigation">
        <ul class="nav navbar-inverse">
            <li class="active"><a href="warehouse.php">Create New</a></li>
            <li><a href="update_warehouse.php">Update Existing</a></li>
        </ul>
    </nav>

    <div class="row">
        <div class="col-md-10">
            <form id="createWarehouse" class="form-horizontal" role="form" action="warehouse.php" method="post">
                <fieldset>
                    <legend>New Warehouse</legend>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputName">Name</label>

                        <div class="col-sm-8">
                            <input type="text" size="30" id="inputName" name="inputName" placeholder="Warehouse Name"
                                   required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputAddress">Address</label>

                        <div class="col-sm-8">
                            <input type="textbox" size="30" id="inputAddress" name="inputAddress"
                                   placeholder="Address Lines" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="inputCity">City</label>

                        <div class="col-sm-8">
                            <input type="textbox" size="30" id="inputCity" name="inputCity" placeholder="City"
                                   required/>
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
                        <label class="col-sm-2 control-label" for="inputZipcode">Zip Code</label>

                        <div class="col-sm-10">
                            <input type="textbox" size="30" id="inputZipcode" name="inputZipcode" placeholder="Zipcode"
                                   required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="hidden" name="submitted" value="submitted"/>
                            <button class="btn btn-primary" type="submit" class="btn">Submit</button>
                        </div>
                    </div>

            </form>


        </div>
    </div>
</div>

<script>
    $( document ).ready(function() {
        $( "#navWarehouse" ).addClass("active");
    });
</script>

<?php
include_once('../footer.php');
?>
