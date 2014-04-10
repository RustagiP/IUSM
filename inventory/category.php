<?php
include "../DbConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_ADMIN_ONLY;
$level = "sub";
include_once('../session_check.php');

if (isset($_POST['submitted'])) {
    
    $name = $_POST['inputName'];
    $des = $_POST['inputDes'];
    $type = $_POST['inputType'];
    $parent = $_POST['inputParent'];
    $level = $_POST['inputLevel'];
    $error = "Unable to complete the registration at this time. Please try later..";
    $errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left");

    $dbh = getConnection($errorParams);

    try {
        $dbh->beginTransaction();
        $stmt = $dbh->prepare("INSERT INTO CATEGORY(NAME,DESCRIPTION,CATEGORY_TYPE,LEVEL,PARENT) VALUES (?,?,?,?,?)");
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $des);
        $stmt->bindParam(3, $type);
        $stmt->bindParam(4, $level);
        $stmt->bindParam(5, $parent);

        $stmt->execute();
        $dbh->commit();
        // echo " 
    } catch (Exception $e) {
        $dbh->rollBack();

        errorHandler($errorParams);
    }
    header('Location: update_category.php');
}

$title = "Category Maintenance Page";
include_once('../header.php');

?>

   <div id="content" >

            <nav class="navbar navbar-left" role="navigation">
                <ul class="nav navbar-inverse">
                    <li ><a href="parent_category.php">New Parent Category</a></li>
                    <li><a href="update_parent_category.php">Maintain Parent Category</a></li>
                    <li class="active"><a href="category.php">New Sub Category</a></li>
                    <li><a href="update_category.php">Maintain Sub Category</a></li>
                </ul>
            </nav>

            <div class="row">
                <div class="col-md-8">
                    <form id="createCategory" class="form-horizontal" role="form" action="category.php" method="post">
                        <fieldset>
                            <legend>New Sub Category</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputName">Name</label>

                                <div class="col-sm-8">
                                    <input type="text" size="30" id="inputName" name="inputName" placeholder="Category Name" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputDes">Description</label>

                                <div class="col-sm-8">
                                    <input type="textbox" size="30" id="inputDes" name="inputDes" placeholder="Description"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputType">Type</label>

                                <div class="col-sm-8" >
                                    <select name="inputType">
                                        <option value="Inventory">Inventory</option>

                                    </select>
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
                                        
                                   <div class='col-sm-8' ><select name='inputParent'>"
                            ;

                            while ($row = $sth->fetch(PDO::FETCH_BOUND)) {
                                echo "<option value=$par_cat_id>" . $par_name . "</option>";
                            }
                            echo "</select></div></div>";
                            ?>

                            <div class="form-group">

                                <div class="col-sm-8">
                                    <input type="hidden" size="30" id="inputLevel" name="inputLevel" value="2"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <input type="hidden" name="submitted" value="submitted"/>
                                    <button class="btn btn-primary" type="submit" class="btn">Submit</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>

    <script>
        $(document).ready(function () {
            $("#navCatMgt").addClass("active");
        });
    </script>

<?php
include_once('../footer.php');
?>
