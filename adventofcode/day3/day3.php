<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script>
        function message(text) {
            alert(text + " happend.");

        }
    </script>
</head>

<body onload="message('PAGELOAD')"
onresize="message('RESIZING')">    
<?php
$rounds = 5;
?>
    <form action="">
        <?php
        for ($x = 0; $x < $rounds; $x++) {
            print($x + 1);
            print ". ";
            print "<input type='number' name='steplength$x' placeholder='Schrittlänge'>";
            print "<input type='number' name='downsteps$x' placeholder='Abwärtsschritte' value=1><br><br>";
        }
        ?>
        <button type="submit">Los</button>
    </form>
    
</body>

</html>




<?php
if (count($_GET) > 0) {

   
    for ($x = 0; $x < $rounds; $x++) {


        $forrest = preg_split('/\n/', file_get_contents("forrest.txt"));
        // $forrest = preg_split('/\n/', file_get_contents("example.txt"));
        $steplen = isset($_GET['steplength' . $x]) ? $_GET['steplength' . $x] : 1;
        $rows = count($forrest);
        $rowlen = strlen($forrest[0]);
        $steps = $steplen * $rows;
        $treecounter = 0;
        $downsteps = isset($_GET['downsteps' . $x]) ? $_GET['downsteps' . $x] : 1;


        // echo "Steplen: " . $steplen . " Rows: " . $rows . "Rowlen: " . $rowlen . " Steps: " . $steps . "<br>";

        $forrestOrg = $forrest;

        for ($i = 0; $i < $rows; $i++) {
            while ($steps >= strlen($forrest[$i])) {
                $stringOrg = $forrestOrg[$i];
                $string = $forrest[$i];
                $forrest[$i] = preg_replace('/\s|\n/ ', '', $stringOrg . $string);
            }
        }


        $k = $steplen;

        for ($j = $downsteps; $j < count($forrest); $j = $j + $downsteps) {
            if ($forrest[$j][$k] == "#") {
                $treecounter++;
            }
            $k = $k + $steplen;
        }

        $treecounter_array[$x] = $treecounter;
    }

    print "<br>Ergebnis:<br>";
    for ($z = 0; $z < count($treecounter_array); $z++) {
        print $z + 1;
        echo ". " . $treecounter_array[$z] . " Trees<br>";
    }
    print array_product($treecounter_array);
}

?>