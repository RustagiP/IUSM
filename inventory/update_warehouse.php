<?php
include "../DbConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_ADMIN_ONLY;
$level = "sub";
include_once('../session_check.php');

$title = "Warehouse Maintenance";
include_once('../header.php');

?>
    <nav class="navbar navbar-left" role="navigation">
        <ul class="nav navbar-inverse">
            <li><a href="warehouse.php">Create New</a></li>
            <li class="active"><a href="update_warehouse.php">Update Existing</a></li
        </ul>
    </nav>

    <div id="content">
        <div class="row">

            <div class="col-md-1">
            </div>

            <?php
            $error = "Unable to complete the registration at this time. Please try later..";
            $errorParams = array($error, "Go Back", basename($_SERVER['PHP_SELF']), "icon-chevron-left");

            $dbh = getConnection($errorParams);

            $dbh->beginTransaction();
            $sql = "SELECT * FROM WAREHOUSE";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute();

            $sth->bindColumn(1, $warehouse_id);
            $sth->bindColumn(2, $name);
            $sth->bindColumn(3, $address);
            $sth->bindColumn(4, $addr_city);
            $sth->bindColumn(5, $addr_state);
            $sth->bindColumn(7, $zipcode);

            echo "<table class='table-hover' border=2 cellpadding=7>
                  <tr>
                  <th>Name</th>
                  <th>Address</th>
                  <th>City</th>
                  <th>State</th>
                  <th>ZipCode</th>
                  </tr>";

            while ($row = $sth->fetch(PDO::FETCH_BOUND)) {
                echo "<tr>"
                    . "<td>$name</td>
                       <td>$address</td>
                       <td>$addr_city</td>
                       <td>$addr_state</td>        
                       <td>$zipcode</td>
                       <td>
                       <form name='editWarehouse' action='editWarehouse.php' method='GET'>
                         <input type='hidden' name='warehouse_id' value=$warehouse_id />"
                    . "<input class='btn btn-warning' type='submit' name='editWarehouse' value ='Edit'/> </form></td>"
                    . " <td>
                       <form name='delWarehouse' action='delWarehouse.php' method='POST'>
                         <input type='hidden' name='warehouse_id' value=$warehouse_id />
                      <input class='btn btn-danger' type='submit' name='delWarehouse' value ='Delete'/> </form></td></tr>";

            }
            echo "</table>"
            //echo "<p>this is a new para";


            /* echo "<table class='table-hover' border=2>
              <tr>
              <th>Header 1</th>
              <th>Header 2</th>
              </tr>
              <tr>
              <td>row 1, cell 1</td>
              <td>row 1, cell 2</td>
              </tr>
              <tr>
              <td>row 2, cell 1</td>
              <td>row 2, cell 2</td>
              </tr>
              </table>";
             */
            ?>

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
