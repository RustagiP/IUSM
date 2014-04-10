<?php
include "../DbConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_ALL;
$level = "sub";
include_once('../session_check.php');
$error = "Unable to complete the operation at this time. Please try later..";
$errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left", "../error.php");
$dbh = getConnection($errorParams);
$query = " Select NAME,CATEGORY_ID FROM CATEGORY C WHERE C.CATEGORY_TYPE='Inventory' and LEVEL = 2 ORDER BY NAME";
$stmt = $dbh->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll();
$data = array();
foreach ($result as $key => $value) {
    $data[$value['CATEGORY_ID']] = $value['NAME'];
}
$json_data = json_encode($data);
$file = 'category.json';
file_put_contents($file, $json_data);
$title = "Collect Event Registration Page";
include_once('../header.php');
?>


            <form class="form-horizontal" role="form" action="CE_SUBMIT.php" method="post">
                <div class="form-group">
                    <label for="emailid" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-5">
                        <input type="email" class="form-control" name="emailid" placeholder="Email">
                    </div>
                    <div class="col-sm-5"></div>
                </div>
                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label">First Name</label>
                    <div class="col-sm-5">
                        <input type="text" size="30" class="form-control" name="fname" placeholder="First Name">
                    </div>
                    <div class="col-sm-5"></div>
                </div>
                <div class="form-group">
                    <label for="lname" class="col-sm-2 control-label">Last Name</label>
                    <div class="col-sm-5">
                        <input type="text" size="30" class="form-control" name="lname" placeholder="Last Name">
                    </div>
                    <div class="col-sm-5"></div>
                </div>
                <div class="form-group">
                    <label for="address" class="col-sm-2 control-label">Address</label>
                    <div class="col-sm-5">
                        <textarea  rows="2" class="form-control" name="address"></textarea>
                    </div>
                    <div class="col-sm-5"></div>
                </div>
                <div class="form-group">
                    <label for="city" class="col-sm-2 control-label">City</label>
                    <div class="col-sm-5">
                        <input type="text" size="150" class="form-control" name="city" placeholder="City">
                    </div>
                    <div class="col-sm-5"></div>
                </div>
                <div class="form-group">
                    <label for="State" class="col-sm-2 control-label">State</label>
                    <div class="col-sm-5">
                        <select class="form-control" name="state">
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

                    <div class="col-sm-5"></div>
                </div>
                <div class="form-group">
                    <label for="zipcode" class="col-sm-2 control-label">Zip Code</label>
                    <div class="col-sm-5">
                        <input type="text" size="150" class="form-control" name="zipcode" placeholder="11111-111" value="" pattern="(\d{5}([\-]\d{4})?)" >
                    </div>
                    <div class="col-sm-5"></div>
                </div>
                <div class="form-group">
                    <label for="pick-up-time" class="col-sm-2 control-label">Pick Up Time</label>
                    <div class="col-sm-5">
                        <select class="form-control" name="pickup">
                            <option value="12-2">between 12pm to 2pm</option>
                            <option value="2-4">between 2pm to 4pm </option>
                            <option value="4-6">between 4pm to 6pm</option>
                        </select>
                    </div>

                    <div class="col-sm-5"></div>
                </div>
                <fieldset id="itemdata">
                    <div class="form-group">
                        <label for="itemNo" class="col-sm-offset-2 col-sm-1 label label-default">ITEM</label>
                        <label for="itemName" class="col-sm-2 label label-default">NAME</label>
                        <label for="category" class="col-sm-1 label label-default">CATEGORY</label>
                        <label for ="quantity" class="col-sm-1 label label-default">Quantity</label>
                        <label for ="descripton" class="col-sm-2 label label-default">Descripton</label>
                        <label for ="delete" class="col-sm-1 label label-default">Remove</label>
                    </div>
                </fieldset>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-1">
                        <button id="add" type="button" >+</button>           
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Submit</button>
                    </div>
                </div>
            </form>
            <!--Below content is part of footer  ( code below should remain common through out the website)  -->
            <footer>
                <p>&copy; Team 16- Advance Database Concepts 2013</p>
            </footer>
        </div>
        <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="js/project.js"></script>

    </body>
</html>
