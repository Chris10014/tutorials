<?php

$ops = [];
$lines = array_map('trim', file('data/example.txt'));
print "<br>1. ";
print_r($lines);
print "<br>2. ";
foreach ($lines as $line) {
    echo "$line, ";
    $ops[] = explode(' ', $line);
}
print "<br>3. ";

print_r($ops);
print "<br>";

function emulate($ops, $infiniteReturn = false)
{
    $pos = 0;
    $acc = 0;
    $visited = [];
    do {
        if (!isset($ops[$pos])) {
            return $acc;
        }

        if (isset($visited[$pos])) {
            if ($infiniteReturn) {
                return $acc;
            }
            return false;
        }

        $visited[$pos] = true;
        list($op, $val) = $ops[$pos];
        // echo "<hr>";
        // echo "a $op val $val";

        // echo "<hr>";
        switch ($op) {
            case 'nop':
                $pos++;
                break;
            case 'acc':
                $pos++;
                $acc += $val;
                break;
            case 'jmp':
                $pos += $val;
                break;
        }
    } while (true);
}

function fixErrorAndEmulateP2($ops, $returnCountSolutions = false): int
{
    $count = 0;
    foreach (['nop' => 'jmp', 'jmp' => 'nop'] as $switchFrom => $switchTo) {
        foreach ($ops as &$op) {
            if ($op[0] === $switchFrom) {
                $op[0] = $switchTo;
                if (($p2 = emulate($ops, false)) !== false) {
                    if ($returnCountSolutions) {
                        $count++;
                    } else {
                        return $p2;
                    }
                }
                $op[0] = $switchFrom;
            }
        }
    }

    return $count;
}

$p1 = emulate($ops, true);
$p2 = fixErrorAndEmulateP2($ops);

echo "P1: {$p1}\n";
echo "P2: {$p2}\n";

echo "Duration: " . (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) . "\n";

// emulate all possibilities
$p2MaxSolutions = fixErrorAndEmulateP2($ops, true);
echo "P2 MAX: {$p2MaxSolutions}\n";

echo "Duration: " . (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) . "\n";



$bootcode = file("data/bootcode.txt");
$accumulator = 0;
$operationsDone = array();
echo "<hr><hr>1. ";
print_r($bootcode);
print "<br>2. ";
for($i = 0; $i < count($bootcode); $i++) {
    $bootcode[$i] = explode(" ", $bootcode[$i]);
}
print_r($bootcode);
print "<br>";
$action = (str_split($bootcode[1][1]));
print "hhuhu: " . $bootcode[1][1] . "<br>";
print $bootcode[1][1] + 5;
print "<br>";
$counter = 0;
$k = 0;
while(!isset($operationsDone[$k])) {
    $counter++;
$operation = $bootcode[$k][0];
if($operation == "acc") {
    $accumulator = $accumulator + $bootcode[$k][1];
        $operationsDone[$k] = true;
    $k++;
} elseif($operation == "nop") {
        $operationsDone[$k] = true;
        $k++;
} else {
        $operationsDone[$k] = true;
        $k = $k + $bootcode[$k][1];
        echo "- $k --- $bootcode[$k][1] ---";
}
echo "<br>Iteration: " . $counter . " Index: " . $k . "<br>";
}
print_r($operationsDone);
echo "<br>Result P1: " . $accumulator;



?>