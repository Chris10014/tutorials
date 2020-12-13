<?php

// Model and Controller
if (!isset($_GET['who']) || strlen($_GET['who']) < 1) {
    die("Name parameter missing");
}

if (isset($_POST['logout'])) {
    header("Location: index.php");
    exit;
}

$message = "Please select a strategy and press Play.";

$names = array("Rock", "Paper", "Scissors");
$human = isset($_POST['human']) ? ($_POST['human'] + 0) : -1; //add 0 to convert value into number

if (isset($_POST['human']) && $_POST['human'] == 3) {
    $message = "";
    for ($c = 0; $c < count($names); $c++) {
        for ($h = 0; $h < count($names); $h++) {
            $result = check($h, $c);
            $message .= "Human=$names[$h] Computer=$names[$c] Result=$result<br>";
        }
    }
} else {
    //Game will be played
    $computer = rand(0, 2);
    if ($human != -1) {
        $result = check($human, $computer);
        $message = "Your Play=$names[$human] Computer=$names[$computer] Result=$result";
    } else {
        $message = "You forgot to choose a strategy.";
    }
}


function check($human, $computer)
{
    //check whether human or computer won the game
    if ($human == $computer) {
        return "Tie";
    } elseif ($human == 0 && $computer == 1) {
        return "You lose";
    } elseif ($human == 0 && $computer == 2) {
        return "You win";
    } elseif ($human == 1 && $computer == 0) {
        return "You win";
    } elseif ($human == 1 && $computer == 2) {
        return "You lose";
    } elseif ($human == 2 && $computer == 0) {
        return "You lose";
    } else {
        return "You win";
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
    <title>Christoph Lansche - RPS Game Page</title>
</head>

<body>
    <header>
        <h1>Rock Paper Scissors</h1>
    </header>
    <main>
        <p>
            Welcome: <?= htmlentities($_GET['who']); ?>
        </p>
        <p>
            <form method="POST">
                <label for="human">Play the game: </label>
                <select name="human" id="strategy">
                    <option value="-1" selected>Select</option>
                    <option value="0">Rock</option>
                    <option value="1">Paper</option>
                    <option value="2">Scissors</option>
                    <option value="3">Test</option>
                </select>

                <input type="submit" value="Play">
                <input type="submit" name="logout" value="Logout">
            </form>
        </p>
        <p>

        <pre>
<?= $message ?>
        </pre>
        </p>
    </main>
    <footer>

    </footer>

</body>

</html>