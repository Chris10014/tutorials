<?php
session_start();

require_once "pdo.php";

if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

$name = $_SESSION["name"];

// Retrieve the autos from the database
$sql = "SELECT make, year, mileage FROM autos";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        <h1>Tracking Autos for <?= htmlentities($name) ?></h1>
    </header>
    <main>
        <?php
        if(isset($_SESSION["success"])) {
        echo "<p style='color:green;'>" . htmlentities($_SESSION["success"]) . "</p>";
        unset($_SESSION['success']);
        }
        ?>
        <h2>Automobiles</h2>
        <ul>
            <?php
            if (count($cars) > 0) {
                foreach ($cars as $car) {
                    echo "<li>" . htmlentities($car['year']) . " " . htmlentities($car['make']) . " / " . htmlentities($car['mileage']) . "</li>";
                }
            }
            ?>
        </ul>
        <p>
            <a href="add.php">Add New</a> | 
            <a href="logout.php">Logout</a>
        </p>
    </main>
</body>

</html>