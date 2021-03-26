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

if (isset($_POST['delete']) && isset($name)) {
    $sql = "DELETE FROM autos WHERE autos_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':id' => $_GET['autos_id']));

    $_SESSION["success"] = "Record deleted";
    header("Location: index.php");
    return;
}

// Check data tomake sure that autos_id is present and valid
if (!isset($_GET['autos_id'])) {
    $_SESSION['error'] = "Missing autos_id";
    header("Location: index.php");
    return;
}

$sql = "SELECT * FROM autos WHERE autos_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(":id" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row == false) {
    $_SESSION['error'] = "Bad value for autos_id";
    header("Location: index.php");
    return;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christoph Lansche</title>
</head>

<body>
    <header>
        <h1>Deleting Automobiles from Database</h1>
    </header>
    <main>
        <p>Confirm: Deleting <?= htmlentities($row['make']) ?> <?= htmlentities($row['model']) ?>?</p>
        <form method="POST">
            <input type="hidden" name="autos_id" id="" value="<?= $_GET['autos_id'] ?>">
            <input type="submit" name="delete" id="" value="Delete">
            <input type="submit" name="cancel" id="" value="Cancel">
        </form>
    </main>

</body>

</html>