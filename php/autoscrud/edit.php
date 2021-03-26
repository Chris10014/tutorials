<?php
session_start();
require_once "pdo.php";

if (isset($_POST['cancel'])) {
    header('Location: index.php');
    return;
}

if (!isset($_SESSION['name'])) {
    die('ACCESS DENIED');
}
if (!isset($_GET['autos_id'])) {
    $_SESSION['error'] = "Missing autos_id";
    header("Loction: index.php");
}
// Input validation
if (isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage'])) {
    // Data validation
    if (strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1) {
        $_SESSION["error"] =  "All fields are required";
        header("Location: edit.php?autos_id=" . $_GET['autos_id']);
        return;
    }
    if (!is_numeric($_POST['year'])) {
        $_SESSION["error"] =  "Year must be an integer";
        header("Location: edit.php?autos_id=" . $_GET['autos_id']);
        return;
    }
    if (!is_numeric($_POST['mileage'])) {
        $_SESSION["error"] =  "Mileage must be an integer";
        header("Location: edit.php?autos_id=" . $_GET['autos_id']);
        return;
    }

    // Update validated data
    $sql = "UPDATE autos SET make = :mk, model = :md, year = :yr, mileage = :ml WHERE autos_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':mk' => $_POST['make'],
        ':md' => $_POST['model'],
        ':yr' => $_POST['year'],
        ':ml' => $_POST['mileage'],
        ':id' => $_POST['autos_id']
    ));

    $_SESSION['success'] = "Record edited";
    header("Location: index.php");
    return;
}

// Retrieve the auto from the database
$autos_id = $_GET['autos_id'];
$sql = "SELECT * FROM autos WHERE autos_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(":id" => $autos_id));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row === false) {
    $_SESSION['error'] = 'Bad value for id';
    header('Location: index.php');
    return;
}

$make = htmlentities($row['make']);
$model = htmlentities($row['model']);
$year = htmlentities($row['year']);
$mileage = htmlentities($row['mileage']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Chistoph Lansche</title>
</head>

<body>
    <header>

        <?php
        $name = isset($_SESSION['name']) ? $_SESSION['name'] : false;
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : "";

        if ($name == false) {
            header("Location: login.php");
            return;
        }
        ?>
        <h1>Tracking Autos for <?= htmlentities($name) ?></h1>
    </header>
    <main>
        <?php
        if (isset($error)) {
            echo "<p style='color:#ff0000;'>" . $error . "</p>\n";
            unset($_SESSION['error']);
        }
        ?>
        <form method="post">
            <p>Make:
                <input type="text" name="make" size="60" value="<?= $make ?>" />
            </p>
            <p>Model:
                <input type="text" name="model" size="60" value="<?= $model ?>" />
            </p>
            <p>Year:
                <input type="text" name="year" value="<?= $year ?>" />
            </p>
            <p>Mileage:
                <input type="text" name="mileage" value="<?= $mileage ?>" />
            </p>

            <input type="hidden" name="autos_id" id="" value="<?= $_GET['autos_id'] ?>">

            <input type="submit" value="Save">
            <input type="submit" name="cancel" value="Cancel">
        </form>

    </main>
</body>

</html>