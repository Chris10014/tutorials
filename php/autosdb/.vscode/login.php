<?php
// Model and Controller

if (isset($_POST['cancel'])) {
    // Redirect to the index.php
    header("Location: index.php");
    exit();
}

$message = false;

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

if (isset($_POST["who"]) && isset($_POST["pass"])) {
    if (strlen($_POST["who"]) < 1 || strlen($_POST["pass"]) < 1) {
        $message = "User name and password are required";
    } else {
        $check = hash('md5', $salt . $_POST['pass']);
        if ($check == $stored_hash) {
            // Redirect to game.php
            header('Location: autos.php?name=' . urlencode($_POST['who']));
            exit();
        } else {
            $message = "Incorrect password";
        }
    }
}

?>
<!-- View -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "bootstrap.php"; ?>
    <title>Christoph Lansche - Login Page</title>
</head>

<body>
    <header>
        <h1>
            Please Log In
        </h1>
    </header>
    <main>
        <?php
        if ($message !== false) {
            echo "<p style='color:#ff0000;'>" . htmlentities($message) . "</p>";
        }

        ?>
        <p>
        <form method="POST">
            <label for="name"><strong>User Name</strong></label>
            <input type="text" name="who" id="name"><br>
            <label for="password"><strong>Password</strong></label>
            <input type="password" name="pass" id="password"><br>
            <input type="submit" value="Log In">
            <input type="submit" name="cancel" value="Cancel">
        </form>
        </p>
        <p>
            For a password hint, view source and find a password hint in the HTML comments.
            <!-- Hint: The password is the world's best three character programming language followed by 123. -->
        </p>


    </main>
    <footer>

    </footer>
</body>

</html>