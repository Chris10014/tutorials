<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christoph Lansches MD5</title>
    <style>
        body {
            padding: 1px;
            margin: 1px;
        }

        h1 {
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
        }

        p {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>

<body>
    <header>
        <h1>MD5 cracker</h1>
    </header>
    <main>
        <p>This application takes an MD5 hash of a four digit pin and check all 10,000 possible four digit PINs to determine the PIN.</p>
        <pre>
Debug Output:
<?php
       
        $goodtext = "Not found";
        // If there is no parameter, this code is all skipped
        if (isset($_GET['md5'])) {
            $timer_start = microtime(true);
            $md5 = $_GET['md5'];           

            $show = 15; // Number of debug outputs
            $k = 0; // Counter for checks
            
            // generate Pins
            for ($i = 0; $i <= 9999; $i++) {
                $k = $k + 1; 
                $pin = str_pad($i, 4, '0', STR_PAD_LEFT);
                $check = hash('md5', $pin);
               
                // Debug output until $show hits 0
                if ($show > 0) {
                    print "$i: $check $pin\n";
                    $show = $show - 1;
                }
                if ($md5 == $check) {                                       
                    $goodtext = $pin;                                   
                    break; // Exit loop
                }
            } 

            print "Total checks: $k\n";
            // Compute elapsed time
            $timer_end = microtime(true); 
            print "Ellapsed time: ";
            print $timer_end - $timer_start;
            print " seconds\n";  
        }
?>
        </pre>
        <p>Pin: <?= htmlentities($goodtext); ?></p>

        <form>
            <input type="text" name="md5" size="40" value="" />
            <input type="submit" value="Crack MD5" />
        </form>
        <p><a href="index.php">Reset this page</a></p>
    </main>
    <footer>

    </footer>
</body>

</html>