<?php

require "DbConnect.php";
include_once('constants.php');
$level = "root";
$page_type = PAGE_TYPE_ALL;
require_once('session_check.php');
$title = "About us Page";
include_once('header.php');

?>



<div id="content">

    <div class="row hero-unit">



        <legend> <span style="text-decoration: underline;">Contact us </legend>

<span>


	You can contact any of the IUSM team members using the below email addresses:</span>
        </br>
        </br>

        <p> 1. Buddhika Kahawitage - budkahaw@indiana.edu  </p>
        <p> 2. Cheng Sao - chenshao@indiana.edu </p>
        <p> 3. Krupa Tadepalli - krtadepa@indiana.edu </p>
        <p> 4. Prerna Rustagi - rustagip@indiana.edu </p>
        <p> 5. Sindhu Kacharaju - sikachar@indiana.edu</p>

        </br>


        <p> Please find the road map of the IUSM campus below. You may drop by at our office anytime between 9 AM- 6 PM, Monday through Saturday incase of any questions.
        </p>


        </br>
        </br>



        <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=lindley+hall+indiana+university&amp;ie=UTF8&amp;hq=&amp;hnear=Lindley+Hall,+Bloomington,+Indiana+47405&amp;t=m&amp;z=14&amp;ll=39.165333,-86.523585&amp;output=embed"></iframe><br /><small><a href="https://maps.google.com/maps?q=lindley+hall+indiana+university&amp;ie=UTF8&amp;hq=&amp;hnear=Lindley+Hall,+Bloomington,+Indiana+47405&amp;t=m&amp;z=14&amp;ll=39.165333,-86.523585&amp;source=embed" style="color:#0000FF;text-align:left">View Larger Map</a></small>

    </div>
</div>


<?php
include_once('footer.php');
?>
