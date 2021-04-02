<?php
session_start();
require_once "pdo.php";
require_once "util.php";

$name = isset($_SESSION["name"]) ? $_SESSION["name"] : false;

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
        <h1>Resume Registry</h1>
    </header>
    <main>
        <?php
        // Retrieve the users from the database
        $sql = "SELECT profile_id, first_name, last_name, headline FROM profile";
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // print_r($stmt->fetch(PDO::FETCH_ASSOC));
        if (count($rows) != 0) {
            // If entries in the db
            
            if ($name === false) {
                // If user is not logged in
                echo
                "<p><a href='login.php'>Please log in</a></p><br>";
                echo "
                <table border='1'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Headline</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>";                
                foreach ($rows as $row) {
                    echo "                
                    <tr>
                        <td><a href='view.php?profile_id=" . $row['profile_id'] . "'>" . htmlentities($row['first_name']) . " " . htmlentities($row['last_name']) . "</a></td>
                        <td>" . htmlentities($row['headline']) . "</td>
                        <td></td>
                    </tr>";
                   
                }
                echo "
                    </tbody>
                </table>";
            } else {
                // If user is logged in
                flashMessage();
                echo "
                <table border='1'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Headline</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>";

                foreach ($rows as $row) {
                    echo "                
                    <tr>
                        <td><a href='view.php?profile_id=" . $row['profile_id'] . "'>" . htmlentities($row['first_name']) . " " . htmlentities($row['last_name']) . "</a></td>
                        <td>" . htmlentities($row['headline']) . "</td>
                        <td><a href='edit.php?profile_id=" . $row['profile_id'] . "'>Edit</a> / <a href='delete.php?profile_id=" . $row['profile_id'] . "'>Delete</a></td>
                    </tr>";
                }

                echo "               
                    </tbody>
                </table>
                <p><a href='add.php'>Add New Entry</a><br>
                <a href='logout.php'>Logout</a></p>";
            }
        } else {
            // If no entries found in the db
            echo "<p>No row found</p>";
            if ($name === false) {
                // If user us not logged in
                echo
                "<p><a href='login.php'>Please log in</a></p><br>";
            } else {
                // If user is logged in
                echo "<p><a href='add.php'>Add New Entry</a><br><a href='logout.php'>Logout</a></p>";
            }
        }
        echo "<p>
            Attempt to go to
            <a href='edit.php'>edit.php</a> without logging in - it should fail with an error message.
        </p>
        <p>
            Attempt to go to
            <a href='add.php'>add.php</a> without logging in - it should fail with an error message.
        </p>";
        ?>

    </main>
    <footer></footer>


</body>

</html>