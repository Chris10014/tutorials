
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
    $sql = "DELETE FROM profile WHERE profile_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':id' => $_GET['profile_id']));

    $_SESSION["success"] = "Record deleted";
    header("Location: index.php");
    return;
}

// Check data to make sure that profile_id is present and valid
if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header("Location: index.php");
    return;
}

$sql = "SELECT * FROM profile WHERE (user_id = :uid && profile_id = :id)";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
    ":uid" =>$_SESSION['user_id'], 
    ":id" => $_GET['profile_id'
    ]));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row == false) {
    $_SESSION['error'] = "Bad value for user_id or profile_id";
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
        <h1>Deleting Profile from Database</h1>
    </header>
    <main>
        <p>First Name: <?= htmlentities($row['first_name']) ?></p>
        <p>Last Name: <?= htmlentities($row['last_name']) ?></p>
        <form method="POST">
            <input type="hidden" name="profile_id" id="" value="<?= $_GET['profile_id'] ?>">
            <input type="submit" name="delete" id="" value="Delete">
            <input type="submit" name="cancel" id="" value="Cancel">
        </form>
    </main>

</body>

</html>