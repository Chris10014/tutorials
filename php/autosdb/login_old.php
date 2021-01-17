<!DOCTYPE html>
<html lang="en">

<?php
require_once "pdo.php";

// p' OR '1' = '1

$salt = 'XyZzy12*_';
$message = false;

if (isset($_POST['who']) && isset($_POST['pass'])) {
    if (strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1) {
        $message = "Email and pass are required";
    } elseif (strlen($_POST['who']) >= 1 && strpos($_POST['who'], '@') < 1) {
        $message = "Email must have an at-sign(@)";
    } else {

        $sql = "SELECT password FROM users 
        WHERE email = :em"; /*AND password = :pw";*/


        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':em' => $_POST['who'],
            // ':pw' => $_POST['pass']
        ));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $check = hash('md5', $salt . $_POST['pass']);

        if ($row === FALSE) {
            $message = "Login incorrect";
        } elseif ($check != $row['password']) {
            error_log("Login fail " . $_POST['who'] . " " . $check);
            $message = "Incorrect password";
        } else {
            //echo "<p>Login success.</p>\n";
            error_log("Login success " . $_POST['who']);
            header('Location: autos.php?name=' . urlencode($_POST['who']));
        }
    }
}

?>



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christoph Lansche</title>
</head>

<body>
    <h1>Please Login</h1>

    <?php
    if ($message != false) {
        echo "<p style='color: red;'>" . $message . "</p>";
    }
    ?>
    <main>
        <form method="post">
            <p>Email:
                <input type="text" size="40" name="who" value="" />
            </p>
            <p>Password:
                <input type="text" size="40" name="pass">
            </p>
            <p><input type="submit" value="Log In" />
                <a href="<?php echo ($_SERVER['PHP_SELF']); ?>">Refresh</a>
            </p>
        </form>
    </main>
    <p>
        For a password hint, view source and find an account and password hint
        in the HTML comments.
        <!-- Hint:
The account is something@everything.
The password is the three character name of the
programming language used in this class (all lower case)
followed by 123. -->
    </p>

    <p>Check out this <a href="http://xkcd.com/327/" target="_blank">XKCD comic that is relevant</a>.</p>
</body>

</html>