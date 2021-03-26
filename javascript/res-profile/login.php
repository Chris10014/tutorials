<?php
session_start();
require_once "pdo.php";
require_once "util.php";
// Model and Controller

if (isset($_POST['cancel'])) {
    header('Location: index.php');
    return;
}

$error = false;

$salt = 'XyZzy12*_';

if (isset($_POST["email"]) && isset($_POST["pass"])) {
    $email = $_POST["email"];
    $password = $_POST["pass"];
    if (strlen($_POST["email"]) < 1 || strlen($_POST["pass"]) < 1) {
        $_SESSION["error"] = "User name and password are required";
    } elseif (strlen($_POST['email']) >= 1 && strpos($_POST['email'], '@') < 1) {
        $_SESSION["error"] = "Email must have an at-sign (@)";
    } elseif (strlen($_POST["email"]) > 0 || strlen($_POST["pass"]) > 0) {
        $check = hash('md5', $salt . $password);

        // Compare email and password with db
        $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
        $stmt->execute(array(':em' => $email, ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row !== false) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            error_log("Login success " . $_SESSION['user_id'] . ": " . $_POST['email']);
            // Redirect to view.php
            header("Location: index.php");
            return;
        } else {
            error_log("Login fail " . $_POST['email'] . " " . $check);
            $_SESSION["error"] = "Incorrect login data";
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
    <script>
        function doValidate() {

            console.log('Validating...');
            try {
                var pw = document.getElementById('pass').value;
                var email = document.getElementById('email').value;
                console.log("Validating email=" + email + "\nValidating pw=" + pw);
                if (email == null || email == "") {
                    alert("E-Mail field must be filled out");
                    return false;
                }
                if (pw == null || pw == "") {
                    alert("Password field must be filled out");
                    return false;
                }
                if (email != null && email.includes('@') == false) {
                    alert("Invalid email address");
                    return false;
                }
                return true;
            } catch (e) {
                return false;
            }
            return false;
        }
    </script>
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
        flashMessage();
        ?>
        <p>
        <form method="POST">
            <label for="email"><strong>Email</strong></label>
            <input type="text" name="email" id="email"><br>
            <label for="password"><strong>Password</strong></label>
            <input type="password" name="pass" id="pass"><br>
            <input type="submit" onclick="return doValidate();" value="Log In">
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