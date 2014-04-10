<?php
include "../DbConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_ADMIN_ONLY;
$level = "sub";
include_once('../session_check.php');

if (isset($_POST['submitted'])) {
    $warehouse_id = $_POST['inputWarehouseid'];
    $name = $_POST['inputName'];
    $address = $_POST['inputAddress'];
    $city = $_POST['inputCity'];
    $state = $_POST['inputState'];
    $zipCode = $_POST['inputZipcode'];

    $error = "Unable to complete the registration at this time. Please try later..";
    $errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left");

    $dbh = getConnection($errorParams);
    echo $name;

    try {
        $stmt = $dbh->prepare("UPDATE WAREHOUSE SET NAME = ?,ADDRESS=?,ADDR_CITY=?,ADDR_STATE=?,ZIPCODE =?"
            . "                              WHERE WAREHOUSE_ID= $warehouse_id");
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $address);

        $stmt->bindParam(3, $city);
        $stmt->bindParam(4, $state);
        $stmt->bindParam(5, $zipCode);

        //$stmt->bindParam(3, $city);
        // $stmt->bindParam(3, $state);


        $stmt->execute();
        $dbh->commit();
    } catch (Exception $ex) {

    }
    header('Location: update_warehouse.php');
}


if (isset($_POST['discard'])) {

    header('Location: update_warehouse.php');
}

//require "DBConnect.php";
$warehouse_id = $_GET['warehouse_id'];
//$error = "Unable to complete the registration at this time. Please try later..";
//$errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left");

$dbh = getConnection($errorParams);
try {
    $dbh->beginTransaction();
    $sql = "SELECT WAREHOUSE_ID,NAME,ADDRESS,ADDR_CITY,ADDR_STATE,ZIPCODE FROM WAREHOUSE where WAREHOUSE_ID = $warehouse_id ";

    $sth = $dbh->prepare($sql);
    $sth->execute();

    $result = $sth->fetch(PDO::FETCH_BOTH);

    $name = $result['NAME'];
    $address = $result['ADDRESS'];
    $addr_city = $result['ADDR_CITY'];
    $addr_state = $result['ADDR_STATE'];
    $zipcode = $result['ZIPCODE'];
} catch (Exception $ex) {

}

$title = "Update Warehouse Information";
include_once('../header.php');

?>

    <nav class="navbar navbar-left" role="navigation">
        <ul class="nav navbar-inverse">
            <li class="active"><a href="warehouse.php">Create New</a></li>
            <li><a href="update_warehouse.php">Update Existing</a></li>


        </ul>

    </nav>

    <div id="content">
        <div class="row">
            <div class="col-md-10">
                <form id="createWarehouse" class="form-horizontal" role="form" action="editWarehouse.php" method="post">
                    <fieldset>
                        <legend>Update Warehouse</legend>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputName">Name</label>

                            <div class="col-sm-8">
                                <input type="text" size="30" id="inputName" name="inputName" value="<?php echo $name ?>"
                                       required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputAddress">Address</label>

                            <div class="col-sm-8">
                                <input type="textbox" size="30" id="inputAddress" name="inputAddress"
                                       value="<?php echo $address ?>" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputCity">City</label>

                            <div class="col-sm-8">
                                <input type="textbox" size="30" id="inputCity" name="inputCity"
                                       value="<?php echo $addr_city ?>"
                                       required/>
                            </div>
                        </div>

                        <input type='hidden' value='<?php echo $warehouse_id ?>' name='inputWarehouseid'/>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputState">State</label>

                            <div class="col-sm-10">
                                <select name="inputState">
                                    <option value="AL" <?php echo $addr_state=='AL'?'selected=""':""; ?> >Alabama</option>
                                    <option value="AK" <?php echo $addr_state=='AK'?'selected=""':""; ?> >Alaska</option>
                                    <option value="AZ" <?php echo $addr_state=='AZ'?'selected=""':""; ?> >Arizona</option>
                                    <option value="AR" <?php echo $addr_state=='AR'?'selected=""':""; ?> >Arkansas</option>
                                    <option value="CA" <?php echo $addr_state=='CA'?'selected=""':""; ?> >California</option>
                                    <option value="CO" <?php echo $addr_state=='CO'?'selected=""':""; ?> >Colorado</option>
                                    <option value="CT" <?php echo $addr_state=='CT'?'selected=""':""; ?> >Connecticut</option>
                                    <option value="DE" <?php echo $addr_state=='DE'?'selected=""':""; ?> >Delaware</option>
                                    <option value="DC" <?php echo $addr_state=='DC'?'selected=""':""; ?> >District Of Columbia</option>
                                    <option value="FL" <?php echo $addr_state=='FL'?'selected=""':""; ?> >Florida</option>
                                    <option value="GA" <?php echo $addr_state=='GA'?'selected=""':""; ?> >Georgia</option>
                                    <option value="HI" <?php echo $addr_state=='HI'?'selected=""':""; ?> >Hawaii</option>
                                    <option value="ID" <?php echo $addr_state=='ID'?'selected=""':""; ?> >Idaho</option>
                                    <option value="IL" <?php echo $addr_state=='IL'?'selected=""':""; ?> >Illinois</option>
                                    <option value="IN" <?php echo $addr_state=='IN'?'selected=""':""; ?> >Indiana</option>
                                    <option value="IA" <?php echo $addr_state=='IA'?'selected=""':""; ?> >Iowa</option>
                                    <option value="KS" <?php echo $addr_state=='KS'?'selected=""':""; ?> >Kansas</option>
                                    <option value="KY" <?php echo $addr_state=='KY'?'selected=""':""; ?> >Kentucky</option>
                                    <option value="LA" <?php echo $addr_state=='LA'?'selected=""':""; ?> >Louisiana</option>
                                    <option value="ME" <?php echo $addr_state=='ME'?'selected=""':""; ?> >Maine</option>
                                    <option value="MD" <?php echo $addr_state=='MD'?'selected=""':""; ?> >Maryland</option>
                                    <option value="MA" <?php echo $addr_state=='MA'?'selected=""':""; ?> >Massachusetts</option>
                                    <option value="MI" <?php echo $addr_state=='MI'?'selected=""':""; ?> >Michigan</option>
                                    <option value="MN" <?php echo $addr_state=='MN'?'selected=""':""; ?> >Minnesota</option>
                                    <option value="MS" <?php echo $addr_state=='MS'?'selected=""':""; ?> >Mississippi</option>
                                    <option value="MO" <?php echo $addr_state=='MO'?'selected=""':""; ?> >Missouri</option>
                                    <option value="MT" <?php echo $addr_state=='MT'?'selected=""':""; ?> >Montana</option>
                                    <option value="NE" <?php echo $addr_state=='NE'?'selected=""':""; ?> >Nebraska</option>
                                    <option value="NV" <?php echo $addr_state=='NE'?'selected=""':""; ?> >Nevada</option>
                                    <option value="NH" <?php echo $addr_state=='NH'?'selected=""':""; ?> >New Hampshire</option>
                                    <option value="NJ" <?php echo $addr_state=='NJ'?'selected=""':""; ?> >New Jersey</option>
                                    <option value="NM" <?php echo $addr_state=='NM'?'selected=""':""; ?> >New Mexico</option>
                                    <option value="NY" <?php echo $addr_state=='NY'?'selected=""':""; ?> >New York</option>
                                    <option value="NC" <?php echo $addr_state=='NC'?'selected=""':""; ?> >North Carolina</option>
                                    <option value="ND" <?php echo $addr_state=='ND'?'selected=""':""; ?> >North Dakota</option>
                                    <option value="OH" <?php echo $addr_state=='OH'?'selected=""':""; ?> >Ohio</option>
                                    <option value="OK" <?php echo $addr_state=='OK'?'selected=""':""; ?> >Oklahoma</option>
                                    <option value="OR" <?php echo $addr_state=='OR'?'selected=""':""; ?> >Oregon</option>
                                    <option value="PA" <?php echo $addr_state=='PA'?'selected=""':""; ?> >Pennsylvania</option>
                                    <option value="RI" <?php echo $addr_state=='RI'?'selected=""':""; ?> >Rhode Island</option>
                                    <option value="SC" <?php echo $addr_state=='SC'?'selected=""':""; ?> >South Carolina</option>
                                    <option value="SD" <?php echo $addr_state=='SD'?'selected=""':""; ?> >South Dakota</option>
                                    <option value="TN" <?php echo $addr_state=='TN'?'selected=""':""; ?> >Tennessee</option>
                                    <option value="TX" <?php echo $addr_state=='TX'?'selected=""':""; ?> >Texas</option>
                                    <option value="UT" <?php echo $addr_state=='UT'?'selected=""':""; ?> >Utah</option>
                                    <option value="VT" <?php echo $addr_state=='VT'?'selected=""':""; ?> >Vermont</option>
                                    <option value="VA" <?php echo $addr_state=='VA'?'selected=""':""; ?> >Virginia</option>
                                    <option value="WA" <?php echo $addr_state=='WA'?'selected=""':""; ?> >Washington</option>
                                    <option value="WV" <?php echo $addr_state=='WV'?'selected=""':""; ?> >West Virginia</option>
                                    <option value="WI" <?php echo $addr_state=='WI'?'selected=""':""; ?> >Wisconsin</option>
                                    <option value="WY" <?php echo $addr_state=='WY'?'selected=""':""; ?> >Wyoming</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputZipcode">Zip Code</label>

                            <div class="col-sm-10">
                                <input type="textbox" size="30" id="inputZipcode" name="inputZipcode"
                                       value='<?php echo $zipcode ?>' required/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <input type="hidden" name="submitted" value="submitted"/>
                                <button class="btn btn-success" type="submit" class="btn">Save Changes</button>
                                &nbsp;&nbsp;&nbsp;
                                <input type="hidden" name="discard" value="discard"/>
                                <button class="btn btn-danger" type="submit" class="btn">Discard Changes</button>
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
