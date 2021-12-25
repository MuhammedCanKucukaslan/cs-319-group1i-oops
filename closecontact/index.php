<?php
require_once(__DIR__ . "/../config.php");
require_once(rootDirectory() . "/util/NavBar.php");
require_once(rootDirectory() . "/util/UserFactory.php");
startDefaultSessionWith();
$pagename = '/closecontact';
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Close Contacts</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
<div class="container">

    <?php
    require_once rootDirectory() . '/vendor/autoload.php';

    $conn = getDatabaseConnection();

    if (!isset($_SESSION['id'])) {
        // echo "dasdsad";
        echo "<div class='centerwrapper'> <div class = 'centerdiv'>"
            . "You haven't logged in";
        echo "<form method='get' action=\"..\"><div class=\"form-group\">
                        <input type=\"submit\" class=\"button button_submit\" value=\"Go to Login Page\">
                    </div> </form>";
        echo "</div> </div>";
        exit();
    } else {
        echo $_SESSION["id"];

        $usertype = $_SESSION['usertype'] ?? Student::TABLE_NAME;
        $uf = new UserFactory();
        $user = $uf->makeUserById($conn, $usertype, $_SESSION["id"]);

        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
        ));

        $navbar = new NavBar($usertype, $pagename);
        echo $navbar->draw(); ?>

        <?php if (isset($_SESSION['cerr'])): ?>

            <div class="notification is-danger is-light">
                <?= htmlspecialchars($_SESSION['cerr'], ENT_HTML5 | ENT_QUOTES); ?>
            </div>

        <?php endif; ?>
        
        <?php

        $imgSource = "../srcs/default_profile_pic.jpg";

        $buttonNames = [];
        $contact_list = [];

        $event_lecture = $user->getEventsIParticipate(CourseEvent::TABLE_NAME);
        $event_sports = $user->getEventsIParticipate(SportsEvent::TABLE_NAME);

        // mock data
        /*$events[] = new Event($conn, 10, "example title", new DateTime("now"), true,
            "example place", 12);*/

        // contacts is array of user objects
        $contacts = $user->getCloseContacts();
        // print_r($events);
        // print_r($contacts);

        // add close contacts to contact list
        $i = 0;
        foreach ($contacts as $contact) {
            $contact_list[] = ["imgsource" => $imgSource, "name" => $contact->getFirstName() . " " . $contact->getLastName(),
                "id" => $contact->getId()];

            $i++;
        }
        // print_r($contact_list);


        // get contents of the events I participate

        $event_list = [];
        foreach ($event_sports as $event) {
            $event_list[] = ["firstEl" => $event->getTitle(), "secondEl" => $event->getPlace() . " " . $event->getStartDate()->format("d") . "-" . $event->getStartDate()->format('M')
                . " " . $event->getStartDate()->format('h') . ":" . $event->getStartDate()->format('i') . "-"
                . $event->getEndDate()->format('h') . ":" . $event->getEndDate()->format('i'),
                "eventId" => $event->getEventID()];
        }

        foreach ($event_lecture as $event) {
            $event_list[] = ["firstEl" => $event->getTitle(), "secondEl" => $event->getPlace(), "eventId" => $event->getEventID()];
        }


        // RENDERING HTMLs
        // render close contacts
        echo $m->render("contactlist", ["person" => $contact_list]);

        // render lectures
        echo $m->render('listWith3ColumnsAndButton',
            ['row' => $event_list
                , "title" => "Your Events", "column1" => "Name", "column2" => "Info", "column3" => "Participants"]);


        // add close contact component
        echo $m->render("addclosecontact");


        // add to close contact
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["closeContact"])) {
            if (is_numeric($_POST['closeContact'])) {
                $_SESSION["closeContact"] = $_POST["closeContact"];
                echo "inside post if " . $_POST["closeContact"];
                unset($_POST);
                unset($_SESSION['cerr']);
                header("Refresh:0");
            } else {
                $_SESSION['cerr'] = $_POST["closeContact"] . ' is not a valid id';
                echo "Not valid ID CODE";
            }
        } else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_SESSION["closeContact"])) {
            $userIdToAdd = $_SESSION["closeContact"];
            unset($_SESSION["closeContact"]);
            if ($user->addCloseContact($userIdToAdd, 1)) {
                unset($_SESSION['cerr']);
                echo "added USER WITH ID: " . $userIdToAdd;
            } else {
                $_SESSION['cerr'] = $userIdToAdd . ' was not added';
                echo "DID NOT MANAGE TO add " . $userIdToAdd;
            }

            header("Refresh:0");
        }


        // implementation of deleting user from the close contact list
        // check if a button is pressed for any user in the table
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["removeCloseContact"])) {
            $_SESSION["removeCloseContact"] = $_POST["removeCloseContact"];
            echo "inside post if remove" . $_POST["removeCloseContact"];
            unset($_POST);

            header("Refresh:0");
        } else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_SESSION["removeCloseContact"])) {
            $userIdToDelete = $_SESSION["removeCloseContact"];
            unset($_SESSION["removeCloseContact"]);

            if ($user->deleteCloseContact($userIdToDelete)) {
                unset($_SESSION['cerr']);
                echo "DELETED USER WITH ID: " . $userIdToDelete;
            } else {
                $_SESSION['cerr'] = $userIdToDelete . ' was not deleted.';
                echo "DID NOT MANAGE TO DELETE " . $userIdToDelete;
            }

            header("Refresh:0");
        }


        // go to the see event page if see button is pressed
        if (isset($_POST["goEvent"])) {
            $_SESSION["eventToDisplay"] = $_POST["goEvent"];
            echo $_POST["goEvent"];
            header("Location: ../../closecontact/see");
        }

    }
    ?>

    <!--
    <div class = "component">
        <h2>Add Close Contact by ID</h2>
        <div>
            <form method="post">
                <input type="text" name="HESCode" class="input" id="HESCode" min="1" required>
                <input type="submit" class="button" value="Add">
            </form>
        </div>
    </div>
    !-->
    <!--
    <div class="centerwrapper">
        <div class="centerdiv">
            <br><br>
            <h2>Close Contact Page</h2>
            <br>
            <form action="see"><div class="form-group">
                    <input type="submit" class="button button_submit" value="See Event Details">
                    <input type="hidden" name="eventid" id="eventid" value="CS101ab12as">
                </div>
            </form>
        </div>
    </div>
    !-->

</div>


</body>
</html>