<?php
session_start();

require_once "pdo.php";

if (isset($_POST['cancel'])) {
    header('Location: index.php');
    return;
}

if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

$name = $_SESSION["name"];

$error = false;
// Input validation
if (isset($_POST['make']) && strlen($_POST['make']) < 1) {
    $_SESSION["error"] =  "Make is required";

    header("Location: add.php");
    return;

} elseif (isset($_POST['year']) && !is_numeric($_POST['year'])) {
    $_SESSION["error"] =  "Mileage and year must be numeric";

    header("Location: add.php");
    return;
    
} elseif (isset($_POST['mileage']) && !is_numeric($_POST['mileage'])) {
    $_SESSION["error"] =  "Mileage and year must be numeric";

    header("Location: add.php");
    return;
    
} elseif (isset($_POST['make'])) {
    // Do database processing
    $sql = "INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':mk' => $_POST['make'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage']
    ));

    $_SESSION["success"] = "Record inserted";
    
    header("Location: view.php");
    return;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body>
    <header>
        <h1>Tracking Autos for <?= htmlentities($name) ?></h1>

        <?php
        $name = isset($_SESSION['name']) ? $_SESSION['name'] : false;
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : "";

        if ($name == false) {
            header("Location: login.php");
            return;
        }
        echo "<p style='color:#ff0000;'>" . htmlentities($error) . "</p>";
        unset($_SESSION['error']);
        ?>
    </header>
    <main>
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
            <input type="submit" name="cancel" value="Cancel">
        </form>

    </main>
</body>

</html>