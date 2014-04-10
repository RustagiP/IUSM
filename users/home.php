<?php

include "../DbConnect.php";

include_once('../constants.php');
$page_type = PAGE_TYPE_REGISTERED_ONLY;
$level = "sub";
include_once('../session_check.php');

$title = "Home Page";
include_once('../header.php');

$result = array();

$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'June', 'August', 'September', 'October',
    'November', 'December');

if (isset($_SESSION['loggedIn'])) {
    $error = "Error occurred while getting data. Please try later..";
    $errorParams = array($error, "Home", "index.php", "icon-home", "../error.php");

    $userId = $_SESSION['userId'];

    $dbh = getConnection($errorParams);
    try {
//        $sql_string = "SELECT *
//                       FROM EVENT E
//                       WHERE E.EVENT_ID
//                       IN (
//                        SELECT E1.EVENT_ID
//                        FROM EVENT_CATEGORY E1
//                        WHERE E1.CATEGORY_ID IN (
//                          SELECT U.CATEGORY_ID
//                          FROM USER_PREF U
//                          WHERE U.USER_ID=?
//                          )
//                        )";
        // Indexes on USER_ID in USER_PREF EVENT_ID in EVENT_CATEGORY and EVENT
        // Gets the future events according user preferences to which the user is not already registered
        $sql_string_0 = "SELECT E.EVENT_ID AS EVENT_ID, E.TITLE AS TITLE, E.DESCRIPTION AS DESCRIPTION, E.VENUE AS VENUE,
                       MONTH(E.START_DATE) AS E_MONTH, DAYOFMONTH(E.START_DATE) AS E_DATE, HOUR(E.START_DATE) AS E_HOUR,
                       MINUTE(E.START_DATE) AS E_MINUTE, E.CAPACITY AS CAPACITY, E.AVAIL_CAPACITY AS AVAIL_CAPACITY
                       FROM
                        EVENT E
                        INNER JOIN EVENT_CATEGORY E1
                        ON E1.EVENT_ID=E.EVENT_ID
                        INNER JOIN USER_PREF U
                        ON E1.CATEGORY_ID=U.CATEGORY_ID
                        LEFT JOIN
                        (SELECT E.EVENT_ID AS EVENT_ID
                         FROM REGISTER R, EVENT E
                         WHERE R.USER_ID=? AND R.EVENT_ID=E.EVENT_ID AND E.START_DATE > now())  REG
                         ON E.EVENT_ID=REG.EVENT_ID
                       WHERE U.USER_ID=? AND E.START_DATE>now() AND REG.EVENT_ID IS NULL";

        // Gets the future events that the user is registered
        $sql_string_1 = "SELECT E.EVENT_ID AS EVENT_ID, E.TITLE AS TITLE, E.DESCRIPTION AS DESCRIPTION, E.VENUE AS VENUE,
                         MONTH(E.START_DATE) AS E_MONTH, DAYOFMONTH(E.START_DATE) AS E_DATE, HOUR(E.START_DATE) AS E_HOUR,
                         MINUTE(E.START_DATE) AS E_MINUTE
                         FROM REGISTER R
                          INNER JOIN EVENT E
                          ON R.EVENT_ID=E.EVENT_ID
                         WHERE R.USER_ID=? AND E.END_DATE > now() AND E.END_DATE IS NOT NULL";

        // Gets the past events that the user has registered
        $sql_string_2 = "SELECT E.TITLE AS TITLE, E.DESCRIPTION AS DESCRIPTION, E.VENUE AS VENUE,
                         MONTH(E.START_DATE) AS E_MONTH, DAYOFMONTH(E.START_DATE) AS E_DATE, HOUR(E.START_DATE) AS E_HOUR,
                         MINUTE(E.START_DATE) AS E_MINUTE
                         FROM REGISTER R
                          INNER JOIN EVENT E
                          ON R.EVENT_ID=E.EVENT_ID
                         WHERE R.USER_ID=? AND E.END_DATE < now()";

        // Gets user donations
        $sql_string_3 = "SELECT I.NAME AS ITEM_NAME, I.DESCRIPTION AS DESCRIPTION, MONTH(I.ARRIVE_DATE) AS ARRIVE_MONTH,
                         DAYOFMONTH(I.ARRIVE_DATE) AS ARRIVE_DATE, E.TITLE AS TITLE
                         FROM COLLECT_ITEMS_EVENT C
                          INNER JOIN ITEM I
                          ON C.ITEM_ID=I.ITEM_ID
                          INNER JOIN EVENT E
                          ON C.EVENT_ID=E.EVENT_ID
                         WHERE C.PERSON_ID=?";

        $stmt = $dbh->prepare($sql_string_0);
        $stmt->bindParam(1, $userId);
        $stmt->bindParam(2, $userId);
        $stmt->execute();
        $result_0 = $stmt->fetchAll();

        $stmt = $dbh->prepare($sql_string_1);
        $stmt->bindParam(1, $userId);
        $stmt->execute();
        $result_1 = $stmt->fetchAll();

        $stmt = $dbh->prepare($sql_string_2);
        $stmt->bindParam(1, $userId);
        $stmt->execute();
        $result_2 = $stmt->fetchAll();

        $stmt = $dbh->prepare($sql_string_3);
        $stmt->bindParam(1, $userId);
        $stmt->execute();
        $result_3 = $stmt->fetchAll();

    } catch (Exception $e) {
        errorHandler($errorParams);
    }
}

?>

    <div id="content">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">You May Be Interested In</h3>
            </div>
            <div class="body">
                <?php
                $string ="";
                foreach ($result_0 as $event) {
                    $string .= "<div class='thumbnail'>
                        <div class=\"caption\">
                            <h4>".$event['TITLE']."</h4>";

                            $eventId = $event['EVENT_ID'];
                            $month = $months[$event['E_MONTH']];
                            $hour = $event['E_HOUR'];

                            $isPM = false;
                            if ($hour > 12) {
                                $isPM = true;
                                $hour = $hour - 12;
                            }

                            if (strlen((string) $hour) == 1) {
                                $hour ="0".$hour;
                            }

                            $minute = $event['E_MINUTE'];

                            if (strlen((string) $minute) == 1) {
                                $minute ="0".$minute;
                            }

                            $time = $hour . ':' . $minute;
                            if ($isPM == true) {
                                $time = $time." PM";
                            } else {
                                $time = $time." AM";
                            }

                            $capacity = $event['CAPACITY'];
                            $availableCapacity = $event['AVAIL_CAPACITY'];
                            $eventFull = false;
                            $percentage = '';

                            if ($availableCapacity <=    0) {
                                $eventFull = true;
                            }
                            $string .="<p>
                                <em>Date:</em>". $month . ' ' . $event['E_DATE']."<br>
                                <em>Time:</em> $time<br>
                                <em>Venue:</em>".$event['VENUE'].
                                $event['DESCRIPTION']."<br>";
                                if ($eventFull == true) {
                                    $string.="<em class=\"red\">Registration Status: </em> <span class=\"red\">This event is full</span><br><br>";
                                } else {
                                if ($availableCapacity < 10) {
                                    $string.="<em class=\"red\">Registration Status:</em>
                                    <span class=\"red\">".$availableCapacity . " seats available.</span>";
                                } else {
                                    $string.="<em>Registration
                                        Status: </em>".$availableCapacity . " seats available.";
                                }

                            $string.="<br>
                            <form action=\"../events/eventreg.php\" method=\"post\">
                                <input type=\"hidden\" name=\"submitted\" value=\"submitted\"/>
                                <input type=\"hidden\" name=\"eventid\" value=\" $eventId \"/>
                                <button type=\"submit\" class=\"btn btn-primary\">Register</button>
                            </form>
                            </p>";
                        }
                    $string.="</p>
                        </div>
                    </div>";
                }
                echo $string;
                ?>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Attending</h3>
            </div>
            <div class="body">
                <?php
                $string="";
                foreach ($result_1 as $event) {
                    $string.="<div class=\"thumbnail\">
                        <div class=\"caption\">
                            <h4>". $event['TITLE']."</h4>";
                            $eventId = $event['EVENT_ID'];
                            $month = $months[$event['E_MONTH']];
                            $hour = $event['E_HOUR'];

                            $isPM = false;
                            if ($hour > 12) {
                                $isPM = true;
                                $hour = $hour - 12;
                            }

                            if (strlen((string) $hour) == 1) {
                                $hour ="0".$hour;
                            }

                            $minute = $event['E_MINUTE'];

                            if (strlen((string) $minute) == 1) {
                                $minute ="0".$minute;
                            }

                            $time = $hour . ':' . $minute;
                            if ($isPM == true) {
                                $time = $time." PM";
                            } else {
                                $time = $time." AM";
                            }

                            $string.="<p>
                                <em>Date:</em>".$month . ' ' . $event['E_DATE']."<br>
                                <em>Time:</em>$time<br>
                                <em>Venue:</em>".$event['VENUE']."<br><br>".
                                $event['DESCRIPTION']."<br>
                            </p>

                            <br>
                            <form action=\"../events/eventdereg.php\" method=\"post\">
                                <input type=\"hidden\" name=\"submitted\" value=\"submitted\"/>
                                <input type=\"hidden\" name=\"eventid\" value=\"$eventId\"/>
                                <button type=\"submit\" class=\"btn btn-primary\">Unregister</button>
                            </form>
                        </div>
                    </div>";
                }
                echo $string;
                ?>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Past Events</h3>
            </div>
            <div class="body">
                <?php
                $string = "";
                foreach ($result_2 as $event) {
                    $string.="<div class=\"thumbnail\">
                        <div class=\"caption\">
                            <h4>".$event['TITLE']."</h4>";
                            $month = $months[$event['E_MONTH']];
                            $hour = $event['E_HOUR'];

                            $isPM = false;
                            if ($hour > 12) {
                                $isPM = true;
                                $hour = $hour - 12;
                            }

                            if (strlen((string) $hour) == 1) {
                                $hour ="0".$hour;
                            }

                            $minute = $event['E_MINUTE'];

                            if (strlen((string) $minute) == 1) {
                                $minute ="0".$minute;
                            }

                            $time = $hour . ':' . $minute;
                            if ($isPM == true) {
                                $time = $time." PM";
                            } else {
                                $time = $time." AM";
                            }


                            $string.="<p>
                                <em>Date:</em>".$month . ' ' . $event['E_DATE']."<br>
                                <em>Time:</em> $time<br>
                                <em>Venue:</em>".$event['VENUE']."<br>
                            </p>

                        </div>
                    </div>";
                }
                echo $string;
                ?>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Donations</h3>
            </div>
            <div class="body">
                Here is your good karma..<br>
            </div>
            <table class="table">
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Event</th>
                </tr>
                <?php
                $counter = 1;
                $string="";
                foreach ($result_3 as $event) {
                    $string.="<tr>
                        <td>".$counter++."</td>
                        <td>".$event['ITEM_NAME']."</td>
                        <td>".$event['DESCRIPTION']."</td>
                        <td>".$months[$event['ARRIVE_MONTH']] . ' ' . $event['ARRIVE_DATE']."</td>
                        <td>".$event['TITLE']."</td>
                    </tr>";
                }
                echo $string;
                ?>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $("#navHome").addClass("active");
        });
    </script>

<?php
include_once('../footer.php');
?>