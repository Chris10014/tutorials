<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guessing game from Christoph Lansche</title>
</head>

<body>
    <header>
        <h1>Welcome to my guessing game</h1>
    </header>
    <main>
        <?php
        if(!isset($_GET['guess'])){
            echo "<p>Missing guess parameter.</p>";
        } else {
            if($_GET['guess'] == false) {
                echo "<p>Your guess is too short.</p>";
            } elseif((is_numeric($_GET['guess'])) === false) {
                echo "<p>Your guess is not a number.</p>";
            } elseif($_GET['guess'] > 81) {
                echo "<p>Your guess is too high.</p>";
            } elseif ($_GET['guess'] < 81) {
                echo "<p>Your guess is too low.</p>";
            } else {
                echo "<p>Congratulations - You are right.</p>";
            }
        } 
        ?>
    </main>
    <footer></footer>
</body>

</html>