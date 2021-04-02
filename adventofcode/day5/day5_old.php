<?php

// $seats = file_get_contents("data/seats.txt");
$seats = file_get_contents("data/example.txt");
// echo $seats;
$seats_array = preg_split("/\n/", $seats); // input as array
// print_r($seats_array);

for($i = 0; $i < count($seats_array); $i++) {
    $rows = 128;
    $row_max = 128;

   
    $seatNr_max = 0;

    for($k = 0; $k < 7; $k++) {        

        if($seats_array[$i][$k] == 'B') {
            $rows = $rows/2;
            $row_max = $row_max;
            $row_min = $row_max - $rows + 1;
        } else {
            $rows = $rows/2;
            $row_max = $row_max - $rows;
            $row_min = $row_max - $rows;
        }
    }

    $columns = 8;
    $column_max = 8;

    for ($k = 7; $k < 10; $k++) {

        if ($seats_array[$i][$k] == 'R') {
            $columns = $columns / 2;
            $column_max = $column_max;
            $column_min = $column_max - $columns + 1;
        } else {
            $columns = $columns / 2;
            $column_max = $column_max - $columns;
            $column_min = $column_max - $columns;
        }
    }

    $seatNr = ($row_max - 1) * 8 + $column_max - 1;
    if($seatNr > $seatNr_max) {
        $seatNr_max = $seatNr;
    }

    


    echo "Ticket " . $i . ": Row: ";
    print $row_max - 1;
    echo  "(Number of Rows: " . $rows . ")<br>";
    echo "Column";
    print $column_max - 1 . " Seat: ";
    print $seatNr;
    echo "<br>"; 


}

echo "Highest Nr.: <strong>" . $seatNr_max . "</strong><br>";

?>