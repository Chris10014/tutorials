<?php
session_start();
require_once "pdo.php";

if (isset($_POST['cancel'])) {
    header('Location: index.php');
    return;
}
if (!isset($_SESSION['name'])) {
    die('ACCCES DENIED');
}
$name = $_SESSION["name"];
$error = false;

// Input validation
if (isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage'])) {    
    // Data validation
    if(strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1){        
        $_SESSION["error"] =  "All fields are required";
        header("Location: add.php");
        return;
    }
    if (!is_numeric($_POST['year'])) {
        $_SESSION["error"] =  "Year must be numeric";
        header("Location: add.php");
        return;
    }
    if (!is_numeric($_POST['mileage'])) {
        echo "3";
        $_SESSION["error"] =  "Mileage must be numeric";
        header("Location: add.php");
        return;
    } 
    // Do database processing
    $sql = "INSERT INTO autos (make, model, year, mileage) VALUES (:mk, :mod, :yr, :mi)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':mk' => $_POST['make'],
        ':mod' => $_POST['model'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage']    ));

    $_SESSION["success"] = "Record added.";
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Christoph Lansche</title>
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
        <?php
        if(isset($error)) {
            echo "<p style='color:#ff0000;'>" . $error . "</p>\n";
            unset($_SESSION['error']);
        }
        ?>
    </header>
    <main>
        <form method="post">
            <p>Make:
                <input type="text" name="make" size="60" value="" />
            </p>
            <p>Model:
                <input type="text" name="model" size="60" value="" />
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