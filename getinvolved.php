<?php

require "DbConnect.php";
include_once('constants.php');
$level = "root";
$page_type = PAGE_TYPE_ALL;
include_once('session_check.php');
$title = "About us Page";
include_once('header.php');

?>



    <div id="content">

        <div class="row hero-unit">



            <legend> <span style="text-decoration: underline;">Get Involved with IUSM </legend>


            <p> Indiana University Student Ministries(IUSM) invites you to get involved with us in all of our activities.
                We request all the interested students to register to our website in order to get enrolled for several events.
                Let us know if you would like to volunteer for any of the events.

                Not a member of the IUSM yet?
                Please register via the below link:

            <h4><a href="users/registration.php">Register here</a></h4>
            </p>

            <p></p>
            <p>You may contact us on iusm@indiana.edu in case of any queries.</p>


        </div>
    </div>


<?php
include_once('footer.php');
?>
