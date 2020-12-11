<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christoph Lansche</title>
</head>

<body>
    <header>
        <h1>Christoph Lansche Request / Response</h1>
    </header>
    <main>
        <p>The SHA256 hash of "Christoph Lansche" is
            <?php
            print hash('sha256', 'Christoph Lansche');
            ?>
            .</p>

        <pre>ASCII ART:

    ***********
    **       **
    **
    **
    **
    **       **
    ***********
        </pre>

        <p>
            <a href="check.php">Click here to check the error setting</a>
            <br>
            <a href="fail.php">Click here to cause a traceback</a>
        </p>

    </main>
    <footer>

    </footer>
</body>

</html>