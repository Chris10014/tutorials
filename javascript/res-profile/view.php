<?php
session_start();
require_once "pdo.php";

if (isset($_SESSION['name'])) {
    $name = $_SESSION["name"];
}

// Retrieve the profile from the database
$sql = "SELECT * FROM profile WHERE profile_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':id' => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row === false) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header('Location: index.php');
    return;
}

$first_name = htmlentities($row['first_name']);
$last_name = htmlentities($row['last_name']);
$email = htmlentities($row['email']);
$headline = htmlentities($row['headline']);
$summary = htmlentities($row['summary']);

$sql = "SELECT * FROM position WHERE profile_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':id' => $_GET['profile_id']));
$positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retrieve the education from the database
$sql = "SELECT * FROM education JOIN institution ON education.institution_id = institution.institution_id WHERE profile_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(":id" => $_GET['profile_id']));
$educations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Christoph Lansche 58c41cd7</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>

<body>
    <header>
        <h1>Profile information</h1>
    </header>
    <main>
        <p>First Name: <?= $first_name ?></p>
        <p>Last Name: <?= $last_name ?></p>
        <p>Email: <?= $email ?></p>
        <p>Headline: <?= $headline ?></p>
        <p>Summary: <?= $summary ?></p>


        <?php
        if (count($educations) != 0) {
            echo "<p>Education<ul>";
            foreach ($educations as $education) {
                echo "<li>" . htmlentities($education['year']) . ": " . htmlentities($education["name"]) . "</li>";
            }
        }
        echo "</ul></p>";

        if (count($positions) != 0) {
            echo "<p>Positions<ul>";
            foreach ($positions as $position) {
                echo "<li>" . htmlentities($position['year']) . ": " . htmlentities($position['description']) . "</li>";
            }
        }
        echo "</ul></p>";
        ?>

        <p><a href='index.php'>Done</a></p>
    </main>
</body>

</html>