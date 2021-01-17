<?php

require_once "pdo.php";

if (isset($_POST['logout'])) {
    header('Location: index.php');
}
if (!isset($_GET['name']) || strlen($_GET['name']) < 1) {
    die("Name parameter missing");
}

$message = false;
// Input validation
if (isset($_POST['make']) && strlen($_POST['make']) < 1) {
    $message =  "Make is required";
} elseif (isset($_POST['year']) && !is_numeric($_POST['year'])) {
    $message =  "Mileage and year must be numeric";
} elseif (isset($_POST['mileage']) && !is_numeric($_POST['mileage'])) {
    $message =  "Mileage and year must be numeric";
} elseif (isset($_POST['make'])) {
    // Do database processing
    $sql = "INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':mk' => $_POST['make'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage']
    ));

    $message = "Record inserted";
} else {
}

// Retrieve the autos from the database
$sql = "SELECT make, year, mileage FROM autos";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Christoph Lansche 58c41cd7</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>

<body>
    <div class="container">
        <h1>Tracking Autos for <?= htmlentities($_GET['name']) ?></h1>
        <?php
        if ($message != false && $message != "Record inserted") {
            echo "<p style='color: red'>" . $message . "</p>";
        } else {
            echo "<p style='color: green'>" . $message . "</p>";
        }

        ?>
        <form method="post">
            <p>Make:
                <input type="text" name="make" size="60" value="" />
            </p>
            <p>Year:
                <input type="text" name="year" value="" />
            </p>
            <p>Mileage:
                <input type="text" name="mileage" value="" />
            </p>
            <input type="submit" value="Add">
            <input type="submit" name="logout" value="Logout">
        </form>

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
    </div>
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
</body>

</html>