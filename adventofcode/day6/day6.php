<?php

$markers = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
$groups = preg_split("/[\n]\s+/", file_get_contents("data/answeres_input.txt"));
// $groups = preg_split("/[\n]\s+/", file_get_contents("data/example.txt"));
print_r($groups);
$counter = 0;
 
 for($i = 0; $i < count($groups); $i++) {
     $group = (preg_split("/\n/", $groups[$i]));
    
     print "Group: " . $i . ", " . count($group) . " Personen<br>";
   
     for($k = 0; $k < count($group) ; $k++) {        
        
            print "<br> hm: ". $group[$k] . ", ";
            print_r(str_split($group[$k]));
            print "<br>";
            $groupMember = str_split($group[$k]);
            $markers = array_intersect($groupMember, $markers);        
        


        print "<hr>!";
        print_r($markers);
        print "<hr>";
        if($k == count($group) - 1) {
            $counter = $counter + count($markers);
            $markers =
                array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
        }


        //  $person = preg_split("/[\n]\s+/", $group[$k]);
        //  print "Person " . $k . ": ";
        //  print_r($person);
        //  print "<br>";        

     } 
     
 
}
echo "<br><strong>" . $counter . "</strong>";
?>