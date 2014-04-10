<?php
include "../DbConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_ADMIN_ONLY;
$level = "sub";
include_once('../session_check.php');

$error = "Unable to complete the operation at this time. Please try later..";
$errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left");

//print_r($_POST);
if (isset($_POST['submitted'])) {
    $category_id = $_POST['inputCategoryid'];
    $name = $_POST['inputName'];
    $desc = $_POST['inputDes'];
    $type = $_POST['inputType'];
    $parent=$_POST['inputParent'];

    $dbh = getConnection($errorParams);
    try {
        $stmt = $dbh->prepare("UPDATE CATEGORY SET NAME = ?,DESCRIPTION=?,CATEGORY_TYPE=?, PARENT=?"
            . " WHERE category_ID= $category_id");
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $desc);

        $stmt->bindParam(3, $type);
        $stmt->bindParam(4, $parent);
        $stmt->execute();
        $dbh->commit();
    } catch (Exception $ex) {
        echo"\n<br\>";
        print_r($dbh->errorInfo());
    }
    header('Location: update_category.php');
}else if (isset($_POST['discard'])) {

    header('Location: update_category.php');
}else{

$category_id = $_POST['category_id'];

$dbh = getConnection($errorParams);
try {
    $dbh->beginTransaction();
    $sql = "SELECT CATEGORY_ID,CATEGORY_TYPE,NAME,DESCRIPTION,PARENT,LEVEL FROM CATEGORY where CATEGORY_ID = $category_id ";

    $sth = $dbh->prepare($sql);
    $sth->execute();

    $result = $sth->fetch(PDO::FETCH_BOTH);

    $cat_type = $result['CATEGORY_TYPE'];
    $name = $result['NAME'];
    $desc = $result['DESCRIPTION'];
    $parent = $result['PARENT'];
    $level = $result['LEVEL'];
} catch (Exception $ex) {
    print_r($stmt->errorInfo());
  //  errorHandler($errorParams);
}
}
$title = "Edit Category";
include_once('../header.php');

?>

    <div id="content">

        <nav class="navbar navbar-left" role="navigation">
            <ul class="nav navbar-inverse">
                <li><a href="parent_category.php">New Parent Category</a></li>
                <li class="active"><a href="update_parent_category.php">Maintain Parent Category</a></li>
                <li><a href="category.php">New Sub Category</a></li>
                <li><a href="update_category.php">Maintain Sub Category</a></li>
            </ul>
        </nav>

        <div class="row">
            <div class="col-md-8">
                <form id="createWarehouse" class="form-horizontal" role="form" action="editCategory.php"
                      method="post">
                    <fieldset>
                        <legend>View & Update Sub Category</legend>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputName">Name</label>

                            <div class="col-sm-8">
                                <input type="text" size="30" id="inputName" name="inputName" value="<?php echo $name ?>"
                                       required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputDes">Description</label>

                            <div class="col-sm-8">
                                <input type="textbox" size="30" id="inputDes" name="inputDes"
                                       value="<?php echo $desc ?>"
                                       required/>
                            </div>
                        </div>


                        <?php
                        $error = "Unable to complete the registration at this time. Please try later..";
                        $errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left");

                        $dbh = getConnection($errorParams);
                        $dbh->beginTransaction();
                        $sql = "SELECT NAME,CATEGORY_ID FROM CATEGORY WHERE LEVEL=1 and CATEGORY_TYPE='Inventory'";
                        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                        $sth->execute();
                        $sth->bindColumn(1, $par_name);
                        $sth->bindColumn(2, $par_cat_id);
                        echo "<div class='form-group'>
                                <label class='col-sm-2 control-label' for='inputParent'>Parent Category</label>
                                        
                                   <div class='col-sm-10' ><select name='inputParent'>";

                        while ($row = $sth->fetch(PDO::FETCH_BOUND)) {
                            echo "<option value=$par_cat_id";
                                if ($parent==$par_cat_id) echo " selected='' ";
                            echo ">" . $par_name . "</option>";
                        }
                        echo "</select></div></div>";
                        ?>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputType">Type</label>

                            <div class="col-sm-10">
                                <select name="inputType">
                                    <option value="Inventory">Inventory</option>

                                </select>
                            </div>
                        </div>
                        <input type='hidden' value='<?php echo $category_id ?>' name='inputCategoryid'/>

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
        $(document).ready(function () {
            $("#navCatMgt").addClass("active");
        });
    </script>

<?php
require_once('../footer.php');
?>
