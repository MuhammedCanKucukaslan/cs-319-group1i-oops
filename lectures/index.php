<?php 
    include_once("../config.php");
    startDefaultSessionWith();
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lectures <?php echo isset($_SESSION['firstname']) ? " of ".$_SESSION['firstname'] : "" ?></title>

    <link rel="stylesheet" href="../styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
<div class="container">
    <?php
    require_once rootDirectory().'/vendor/autoload.php';

    $conn = getDatabaseConnection();

    if (!isset($_SESSION['id'])) {
        echo "<div class='centerwrapper'> <div class = 'centerdiv'>"
            . "You haven't logged in";
        echo "<form method='get' action=\"..\"><div class=\"form-group\">
                        <input type=\"submit\" class=\"button button_submit\" value=\"Go to Login Page\">
                    </div> </form>";
        echo "</div> </div>";
        exit();
    } else {
        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/../templates'),
        ));
        echo $m->render('navbar', ['links' => [
                ['href' => '../', 'title' => 'Main Menu'],
                ['href' => '../administration', 'title' => 'Administration'],
                ['href' => '../lectures', 'title' => 'Lectures'],
                ['href' => '../reservations', 'title' => 'Reservations'],
                ['href' => '../events', 'title' => 'Events'],
                ['href' => '../closecontact', 'title' => 'Close Contact'],
                ['href' => '../profile', 'title' => 'Profile'],
                ['href' => '../logout.php', 'title' => 'Logout', 'id' => 'logout']]]
        );

        
    }

    ?>
    <div class="centerwrapper">
        <div class="centerdiv">
            <br><br>
            <h2>Lectures Page</h2>
            <?php
            echo "<h3> <abbr title='Your Majesties, Your Excellencies, Your Highnesses'>Hey</abbr> " . $_SESSION['firstname'] . " </h3>";
            echo "<i> Welcome to the <abbr title='arguably'>smallest</abbr> ... University Contact Tracing Service, <abbr title='of course by us'> <b>ever</b></abbr>!</i>";
            ?>
            <br>
            <form action="manage"><div class="form-group">
                    <input type="submit" class="button button_submit" value="See Course Details">
                    <input type="hidden" name="courseid" id="courseid" value="CS101">
                </div>
            </form>
        </div>
    </div>


</div>


</body>
</html>