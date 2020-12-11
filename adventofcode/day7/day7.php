<?php

// child => [...parents]
$part1Rules = [];

// parent => [...child => count]
$part2Rules = [];

// $first = file('data/rules.txt');

// print_r($first);
// print "<hr>";

$lines = file('data/rules.txt');
print_r($lines);
print "<br>";
$regex = '/(?<parent>\w+\s\w+) bags contain|(?<count>\d+)\s(?<child>\w+\s+\w+) bag/';
foreach ($lines as $line) {
    preg_match_all($regex, $line, $matches, PREG_UNMATCHED_AS_NULL | PREG_SET_ORDER);

    $bag = $matches[0]['parent'];
    $part1Rules[$bag] = $part1Rules[$bag] ?? [];
    $part2Rules[$bag] = $part2Rules[$bag] ?? [];

    foreach (array_slice($matches, 1) as $match) {
        list('child' => $child, 'count' => $count) = $match;
        $part1Rules[$child][] = $bag;
        $part2Rules[$bag][$child] = $count;
    }
}

$fnCount1 = function (string $bag, &$data = []) use ($part1Rules, &$fnCount1): int {
    foreach ($part1Rules[$bag] as $possibleParent) {
        $data[$possibleParent] = true;
        $fnCount1($possibleParent, $data);
    }

    return count($data);
};

$fn2Cache2 = [];
$fnCount2 = function (string $bag) use ($part2Rules, &$fnCount2, &$fnCache2): int {
    $ct = 0;
    foreach ($part2Rules[$bag] as $childBag => $count) {
        if (!isset($fnCache2[$childBag])) {
            $fnCache2[$childBag] = $fnCount2($childBag);
        }
        $ct += $count + ($count * $fnCache2[$childBag]);
    }

    return $ct;
};

echo "P1: {$fnCount1('shiny gold')}\n";
echo "P2: {$fnCount2('shiny gold')}\n";

echo "Duration: " . (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) . "\n";

$max1 = $max2 = PHP_INT_MIN;
foreach (array_keys($part1Rules) as $bag) {
    $ct1 = $fnCount1($bag);
    $ct2 = $fnCount2($bag);

    $max1 = max($max1, $ct1);
    $max2 = max($max2, $ct2);
}

echo "P1 MAX: {$max1}\n";
echo "P2 MAX: {$max2}\n";

echo "Duration: " . (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) . "\n";
