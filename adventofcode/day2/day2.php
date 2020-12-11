<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php

    $input_str = file_get_contents("input.txt");

// echo $input_str . "\n";

$input_arr = preg_split("/[\s,]+/", $input_str, 3000);

// print_r($input_arr[2999]);
$input_final = array();
$k = 0;
    for ($i = 0; $i < count($input_arr); $i = $i+3) {

        $input_final[$k]['number'] = $input_arr[$i];
        $input_final[$k]['char'] = str_replace(':', '', $input_arr[$i + 1]);
        $input_final[$k]['passw'] = $input_arr[$i + 2];
        
        $k = $k + 1;
        
    }

    // print_r($input_final);
    $wrightPasswPart1 = 0;
    $wrightPasswPart2 = 0;

    for($i = 0; $i < count($input_final); $i++) {
        $min =  (int)explode('-', $input_final[$i]['number'])[0];
        $max = (int)explode('-', $input_final[$i]['number'])[1];
        $char = $input_final[$i]['char'];
        $passw = $input_final[$i]['passw'];
        $count = (int)substr_count($passw, $char); // Part 1   

        if($count >= $min && $count <= $max) {
            $wrightPasswPart1++;
        }

        if(($passw[$min - 1] == $char && $passw[$max - 1] != $char) || ($passw[$min - 1] != $char && $passw[$max - 1] == $char)) {
            $wrightPasswPart2++;
        }

    }
    echo "Part 1 result: " . $wrightPasswPart1 . "<br> Part 2 result: " . $wrightPasswPart2 . "<br>";

    ?>


</body>

</html>