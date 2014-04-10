<?php
include "../DbConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_ADMIN_ONLY;
$level = "sub";
include_once('../session_check.php');

$error = "Unable to complete the operation at this time. Please try later..";
$errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left", "../error.php");

if (isset($_POST['submitted'])) {
    $name = $_POST['inputName'];
    $des = $_POST['inputDes'];
    $type = $_POST['inputType'];
    $level = $_POST['inputLevel'];

    $dbh = getConnection($errorParams);

    try {
        $dbh->beginTransaction();
        $sql = "SELECT * FROM CATEGORY where upper(name) = upper(:wname)";
        //$sql =  "SELECT * FROM WAREHOUSE where upper(name) = upper(:wname)";
        $count = 0;
        //echo $count;  
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':wname' => $name));

        $a = $sth->fetchAll();
      
            $stmt = $dbh->prepare("INSERT INTO CATEGORY(NAME,DESCRIPTION,CATEGORY_TYPE,LEVEL) VALUES (?,?,?,?)");
            $stmt->bindParam(1, $name);
            $stmt->bindParam(2, $des);
            $stmt->bindParam(3, $type);
            $stmt->bindParam(4, $level);

            $stmt->execute();
            $dbh->commit();
    } catch (Exception $e) {
        $dbh->rollBack();

        errorHandler($errorParams);
    }

    header('Location: update_parent_category.php');
    
}

$title = "Category Maintenance Page";
include_once('../header.php');

?>

        <div id="content" >
            <nav class="navbar navbar-left" role="navigation">
                <ul class="nav navbar-inverse">
                   <li class="active"><a href="parent_category.php">New Parent Category</a></li>
                    <li><a href="update_parent_category.php">Maintain Parent Category</a></li>
                     <li ><a href="category.php">New Sub Category</a></li>
                    <li><a href="update_category.php">Maintain Sub Category</a></li>
                </ul>
            </nav>

            <div class="row">
                <div class="col-md-8">
                    <form id="createParent" class="form-horizontal" role="form" action="parent_category.php" method="post">
                        <fieldset>
                            <legend>New Parent Category</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputName">Name</label>

                                <div class="col-sm-8">
                                    <input type="text" size="30" id="inputName" name="inputName" placeholder="Parent Category Name" required />
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

                                <div class="col-sm-10" >
                                    <select name="inputType">
                                        <option value="Inventory">Inventory</option>
                                        <option value="Event">Event</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="col-sm-8">
                                    <input type="hidden" size="30" id="inputLevel" name="inputLevel" value="1"  />
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
        $(document).ready(function () {
            $("#navCatMgt").addClass("active");
        });
    </script>

<?php
include_once('../footer.php');
?>
