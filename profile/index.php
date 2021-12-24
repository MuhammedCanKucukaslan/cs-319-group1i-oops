<?php
// Why do we try to connect database before user is logged in? (talking specifically for this page)
require_once("../config.php");
require_once(rootDirectory() . "/util/class.pdf2text.php");
require_once(rootDirectory() . "/util/UserFactory.php");
require_once(rootDirectory() . "/util/Vaccine.php");
require_once(rootDirectory() . "/util/VaccineFactory.php");
require_once(rootDirectory() . "/util/VaccineManager.php");
require_once(rootDirectory() . "/util/Test.php");
require_once(rootDirectory() . "/util/NavBar.php");

$conn = getDatabaseConnection();
startDefaultSessionWith();

if (!isset($_SESSION['id']) || !isset($conn)) {
    header("location: ../login");

}
$usertype = $_SESSION['usertype'] ?? Student::TABLE_NAME;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Page</title>
    <link rel="stylesheet" href="../styles.css">

    <meta name="author" content="Muhammed Can Küçükaslan">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
<header>
    <?php
    $navbar = new NavBar($usertype);
    echo $navbar->draw();
    ?>
</header>
<div class="container">
    <?php
    $uf = new UserFactory();
    $std = $uf->makeUserById($conn, $usertype, $_SESSION['id']);

    $m = new Mustache_Engine(array(
        'loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),
    ));

    if (isset($conn) && $_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['newName'])) {
            $newFName = $_POST['newName'];
            $std->setFirstname($newFName);
            $std = $uf->makeUserById($conn, $usertype, $_SESSION['id']);
            $std->updateToDatabase();
            echo $newFName;
        }
        if (isset($_POST['newsName'])) {

            $newSName = $_POST['newsName'];
            $std->setLastname($newSName);
            $std = $uf->makeUserById($conn, $usertype, $_SESSION['id']);
        }
        if (isset($_POST['newEmail'])) {

            $newEmail = $_POST['newEmail'];
            $std->setEmail($newEmail);
            $std = $uf->makeUserById($conn, $usertype, $_SESSION['id']);
        }
    }
    echo $m->render('profilePage', [
        "name" => $std->getFirstName(),
        "email" => $std->getEmail(),
        "id" => $std->getId(),
    ]);
    ?>
</div>

</body>
</html>