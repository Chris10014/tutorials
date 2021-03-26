<?php
session_start();
// Model and Controller

if (isset($_POST['cancel'])) {
    header('Location: index.php');
    return;
}

$error = false;

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

if (isset($_POST["email"]) && isset($_POST["pass"])) {
    $email = $_POST["email"];
    $password = $_POST["pass"];
    if (strlen($_POST["email"]) < 1 || strlen($_POST["pass"]) < 1) {
        $_SESSION["error"] = "User name and password are required";
    } elseif (strlen($_POST['email']) >= 1 && strpos($_POST['email'], '@') < 1) {
        $_SESSION["error"] = "Email must have an at-sign (@)";
    } elseif (strlen($_POST["email"]) > 0 || strlen($_POST["pass"]) > 0) {
        $check = hash('md5', $salt . $password);
        if ($check == $stored_hash) {           
            $_SESSION['name'] = $email;
            error_log("Login success " . $_POST['email']);
            // Redirect to view.php
            header("Location: index.php");
            return;
        } else {
            error_log("Login fail " . $_POST['email'] . " " . $check);
            $_SESSION["error"] = "Incorrect password";
        }
    }
    header("Location: login.php");
    return;
}

?>
<!-- View -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <title>Christoph Lansche - Login Page</title>
</head>

<body>

    <?php
    $name = isset($_SESSION['name']) ? $_SESSION['name'] : false;
    ?>

    <header>
        <h1>
            Please Log In
        </h1>
    </header>
    <main>
        <?php 
        if(isset($_SESSION["error"])) {     
            echo "<p style='color:red;'>" . htmlentities($_SESSION['error']) . "</p>";
            unset($_SESSION['error']); 
        }           
        ?>
        <p>
        <form method="POST">
            <label for="email"><strong>Email</strong></label>
            <input type="text" name="email" id="email"><br>
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