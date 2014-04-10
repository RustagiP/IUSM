<?php

include_once('constants.php');
$page_type = PAGE_TYPE_ALL;
$level = "root";
require_once('session_check.php');

$title = "Home Page";
include_once('header.php');

?>

<div class="row slideshow">
    <div id="SlideShow" class="carousel slide" data-ride="carousel">
        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item active">
                <img src="img/apple_pickup.jpg" alt="Apple_Picking">

                <div class="carousel-caption">
                    <h2>Apple Picking Event</h2>
                </div>
            </div>
            <div class="item"><img src="img/english_conversation.jpg" alt="english Conversation">

                <div class="carousel-caption">
                    <h2> English Conversation Class</h2>
                </div>
            </div>
            <div class="item"><img src="img/furniture_give_away.jpg" alt="Furniture Give Away">

                <div class="carousel-caption">
                    <h2> Furniture Give Away</h2>
                </div>
            </div>
            <div class="item"><img src="img/hiking_trip.jpg" alt="Hiking Trip">

                <div class="carousel-caption">
                    <h2> Hiking Trip </h2>
                </div>
            </div>
            <div class="item"><img src="img/walmart_trip.jpg" alt="Walmart Shopping Trip">

                <div class="carousel-caption">
                    <h2> Walmart Shopping Trip</h2>
                </div>
            </div>
        </div>

        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#SlideShow" data-slide-to="0" class="active"></li>
            <li data-target="#SlideShow" data-slide-to="1"></li>
            <li data-target="#SlideShow" data-slide-to="2"></li>
            <li data-target="#SlideShow" data-slide-to="3"></li>
            <li data-target="#SlideShow" data-slide-to="4"></li>
        </ol>
        <!-- Controls -->
        <a class="left carousel-control" href="#SlideShow" data-slide="prev"><span
                class="glyphicon glyphicon-chevron-left"></span></a>
        <a class="right carousel-control" href="#SlideShow" data-slide="next"><span
                class="glyphicon glyphicon-chevron-right"></span></a>
    </div>

</div>
<div class="row">
    <div class="col-md-4">
        <h4 class=" text-center">About US</h4>

        <p>We are a partnership of student organizations and local churches to help internationals of all backgrounds
            develop cross-cultural friendships. Check our events page to see various events organized by us.</p>

        <p><a class="btn btn-default" href="aboutus.php" role="button">View details &raquo;</a></p>
    </div>
    <div class="col-md-4">
        <h4 class="text-center">Get Involved</h4>

        <p>Lend a helping hand to internationals by being a part of this amazing drive. Its simple, register with us and
            explore the various upcoming events and register to be a part of it. All the events are free.</p>

        <p><a class="btn btn-default" href="getinvolved.php" role="button">View details &raquo;</a></p>
    </div>
    <div class="col-md-4">
        <h4 class="text-center">Donate Furniture</h4>

        <p>Donate your usable furniture, household, electronic and kitchen items to be given away to international
            students coming to Indiana University this year through our <b>Furniture Give Away Program</p>

        <p><a class="btn btn-default" href="events/Collect_Event.php" role="button">View details &raquo;</a></p>
    </div>
</div>

<?php
    require_once('footer.php');
?>
