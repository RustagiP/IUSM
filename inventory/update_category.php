<?php
include "../DbConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_ADMIN_ONLY;
$level = "sub";
include_once('../session_check.php');

$title = "Update Category";
include_once('../header.php');
?>

    <div id="content">
        <nav class="navbar navbar-left" role="navigation">
            <ul class="nav navbar-inverse">
                <li><a href="parent_category.php">New Parent Category</a></li>
                <li><a href="update_parent_category.php">Maintain Parent Category</a></li>
                <li><a href="category.php">New Sub Category</a></li>
                <li class="active"><a href="update_category.php">Maintain Sub Category</a></li>
            </ul>
        </nav>

        <div class="row">
            <div class="col-md-8">
                <?php

                $error = "Unable to complete the operation at this time. Please try later..";
                $errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left", "../error.php");

                $dbh = getConnection($errorParams);

                $dbh->beginTransaction();
                $sql = "SELECT C.CATEGORY_ID,C.CATEGORY_TYPE,C.NAME,C.DESCRIPTION,P.NAME,C.LEVEL
                        FROM CATEGORY C, CATEGORY P where C.level = 2 and C.parent = P.category_id
                        ORDER BY P.NAME, C.NAME";
                $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $sth->execute();
                $sth->bindColumn(1, $category_id);
                $sth->bindColumn(2, $category_type);
                $sth->bindColumn(3, $name);
                $sth->bindColumn(4, $desc);
                $sth->bindColumn(5, $parent);
                $sth->bindColumn(7, $level);

                echo "<table class='table-hover' border=2 cellpadding=7>
                  <tr>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Parent Category</th>
                   </tr>";

                while ($row = $sth->fetch(PDO::FETCH_BOUND)) {
                    echo "<tr>"
                        . "<td>$name</td>
                       <td>$desc</td>
                       <td>$parent</td>
                      
                       <td>
                       <form name='editCategory' action='editCategory.php' method='POST'>
                         <input type='hidden' name='category_id' value=$category_id />"
                        . "<input class='btn btn-warning' type='submit' name='editCategory' value ='Edit'/> </form></td>"
                        . " <td>
                       <form name='delCategory' action='delCategory.php' method='POST'>
                         <input type='hidden' name='category_id' value=$category_id />
                      <input class='btn btn-danger' type='submit' name='delCategory' value ='Delete'/> </form></td></tr>";
                }
                echo "</table>"

                ?>

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
