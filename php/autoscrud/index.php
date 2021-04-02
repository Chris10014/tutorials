<?php
session_start();
require_once "pdo.php";

$name = isset($_SESSION["name"]) ? $_SESSION["name"] : false;
$success = isset($_SESSION["success"]) ? $_SESSION["success"] : "";
$error = isset($_SESSION["error"]) ? $_SESSION["error"] : "";

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
        <h1>Christoph's Resume Registry</h1>
    </header>
    <main>
        <?php
        if ($name === false) {
            echo
            "<p><a href='login.php'>Please log in</a></p>
        <p>
            Attempt to go to
            <a href='edit.php'>edit.php</a> without logging in - it should fail with an error message.
        </p>
        <p>
            Attempt to go to
            <a href='add.php'>add.php</a> without logging in - it should fail with an error message.
        </p>";
        } else {
            // $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (isset($success)) {
                echo "<p style='color:green'>" . $success . "</p>\n";
                unset($_SESSION['success']);
            }
            if (isset($error)) {
                echo "<p style='color:red'>" . $error . "</p>\n";
                unset($_SESSION['error']);
            }

            // Retrieve the autos from the database
            $sql = "SELECT autos_id, make, model, year, mileage FROM autos";
            $stmt = $pdo->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // print_r($stmt->fetch(PDO::FETCH_ASSOC));
            if (count($rows) != 0) {
                echo "
                <table border='1'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Headline</th>
                        </tr>
                    </thead>
                    <tbody>";
                foreach ($rows as $row) {
                    echo "                
                    <tr>
                        <td>" . htmlentities($row['make']) . "</td>
                        <td>" . htmlentities($row['model']) . "</td>

                        <td><a href='edit.php?autos_id=" . $row['autos_id'] . "'>Edit</a> / <a href='delete.php?autos_id=" . $row['autos_id'] . "'>Delete</a></td>
                    </tr>";
                }
                echo "
                    </tbody>
                </table>";
            } else {
                echo "<p>No row found</p>";
            }
            echo "<p><a href='add.php'>Add New Entry</a><br>
            <a href='logout.php'>Logout</a></p>";
        }
        ?>

    </main>
    <footer></footer>


</body>

</html>