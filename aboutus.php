<?php

include "DbConnect.php";
include_once('constants.php');
$level = "root";
$page_type = PAGE_TYPE_ALL;
include_once('session_check.php');
$title = "About us Page";
include_once('header.php');

?>



<div id="content">

    <div class="row hero-unit">



        <legend> <span style="text-decoration: underline;">About IUSM </legend>


        <p> Indiana University Student Ministries(IUSM) is a partnership of IU student organizations and local churches which exists to help students of all backgrounds develop cross-cultural friendships and enjoy American culture. We desire to assist you in any way and hope your involvement with us will be a positive part of your experience in Bloomington.
        </p>

        <legend> <span style="text-decoration: underline;">
	What are our major goals? </legend>
        <p> Major goals of IUSM is to provide help to the students spread across America, specially Indiana.
            Events like furniture give away , skiing trips and communication classes are our highlights.
        </p>

        <p> </p>
        </br>
        <legend> <span style="text-decoration: underline;">

	Team IUSM </span> </legend>
        <p> 1. Buddhika Chamith  </p>
        <p> 2. Cheng Sao  </p>
        <p> 3. Krupa Tadepalli  </p>
        <p> 4. Prerna Rustagi  </p>
        <p> 5. Sindhu Kacharaju </p>
    </div>
</div>


<?php
include_once('footer.php');
?>
