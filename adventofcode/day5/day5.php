<?php

$tickets_input = file_get_contents("data/seats.txt");
// $tickets_input = file_get_contents("data/example.txt");

$tickets = preg_split("/\n/", $tickets_input); // input as array

$biggestSeatNumber = 0;
$seats = array();

//Iterate through all tickets
for($i = 0; $i < count($tickets); $i++) {

    $numberOfRows = 128;
    $numberOfColumns = 8;    

    //Generate array with all rows
    $rows = array();
    for ($j = 0; $j < $numberOfRows; $j++) {
        array_push($rows, $j);
    }
    // print "<br>Rows: ";
    // print_r($rows);
    // print "<br>";

    //Generate array with columns
    $columns = array();
    for ($j = 0; $j < $numberOfColumns; $j++) {
        array_push($columns, $j);
    }
    // print "<br>Columns: ";
    // print_r($columns);
    // print "<br>";

    //Iterate through the first 7 letters (row) of a ticket code
    for($k = 0; $k < 7; $k++) {

        $numberOfRows = $numberOfRows / 2;
        
        if($tickets[$i][$k] == "F") {
            
            for($z = 0; $z < $numberOfRows; $z++) {
                array_pop($rows);
            }
        } else {
            for ($z = 0; $z < $numberOfRows; $z++) {
                array_shift($rows);
            }
        }
    }
    
    // echo "<br>Ticket " . $i . ": Row " . $rows[0] . " ";

    //Iterate through the last letters (columns) of a ticket code
    for ($k = 7; $k < 10; $k++) {

        $numberOfColumns = $numberOfColumns / 2;

        if ($tickets[$i][$k] == "L") {

            for ($z = 0; $z < $numberOfColumns; $z++) {
                array_pop($columns);
            }
        } else {
            for ($z = 0; $z < $numberOfColumns; $z++) {
                array_shift($columns);
            }
        }
    }

    $seat = 8 * $rows[0] + $columns[0];
    array_push($seats, $seat);

    //Check for biggest Seat number
    if($seat > $biggestSeatNumber) {
        $biggestSeatNumber = $seat;
    }
    // echo "Column: " . $columns[0] . " Seat: <strong>";
    // print $seat;
    // echo "</strong><br>";
}
sort($seats);
// print_r($seats);
// print " #Seats: " . count($seats);

echo "<br>Biggest Seat number: <strong>" . $biggestSeatNumber . "</strong><br>";

for($n = 0; $n < count($seats); $n++) {
    $seat = $seats[$n];
    if(isset($seat_before) && $seat_before != $seat - 1) {
        $mySeat = $seat - 1;
    }
    $seat_before = $seat;
}
echo "My Seat: <strong>" . $mySeat . "</strong>";


?>