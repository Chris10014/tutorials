<?php
$pplist = preg_split('/\n\s/', file_get_contents("data/passportlist.txt"));
// $pplist = preg_split('/\n\s/', file_get_contents("data/example.txt"));
// echo count($pplist) . "<br>";
// $pplist_str = (implode($pplist));
// print_r($pplist);
// echo "<hr>";
// $pplist_array = preg_split('/\s/', $pplist);
// print_r($pplist_array);

// print_r($pplist_array);
$final_ppList = array();
for($i = 0; $i<count($pplist); $i++) {
    $passport = preg_split('/(\s\n|\s+)/', $pplist[$i]);
    echo "<br>PP: ";
    print_r($passport);
    for($z = 0; $z < count($passport); $z++){
    echo "<br>";              
       
        $value = substr($passport[$z], (strpos($passport[$z], ':') ?: -1) + 1);
        echo $value . ", ";
        $key = str_replace('[', '', substr($passport[$z], 0, strrpos($passport[$z], ":")));
        $final_ppList[$i][$key] = $value;
        
    }
}
echo "<br>uu: ";
print_r($final_ppList);
echo "<hr>";
$validCounter = 0;
for($i = 0; $i < count($final_ppList); $i++) {
    echo "<br>" . $i . ". ";
    if(isset($final_ppList[$i]['byr']) &&  $final_ppList[$i]['byr'] >= 1920 && $final_ppList[$i]['byr'] <= 2002) {
        echo "yes" . $final_ppList[$i]['byr'] . ", ";        
        if (isset($final_ppList[$i]['iyr']) && $final_ppList[$i]['iyr'] >= 2010 && $final_ppList[$i]['iyr'] <= 2020) {
            echo "yes" . $final_ppList[$i]['iyr'] . ", ";
            if (isset($final_ppList[$i]['eyr']) && $final_ppList[$i]['eyr'] >= '2020' && $final_ppList[$i]['eyr'] <= '2030') {
                echo "yes" . $final_ppList[$i]['eyr'] . ", ";
                if (isset($final_ppList[$i]['hgt']) && (preg_match('/^1([5-8][0-9]|[9][0-3])(cm)+/', $final_ppList[$i]['hgt']) == 1 || preg_match('/([5][9]|[6][0-9]|[7][0-6])(in)/', $final_ppList[$i]['hgt']) == 1)) {
                    echo "yes" . $final_ppList[$i]['hgt'] . ", ";
                    if (isset($final_ppList[$i]['hcl']) && preg_match('/^#[0-9a-f]{6}$/', $final_ppList[$i]['hcl']) == 1) {
                        echo "yes" . $final_ppList[$i]['hcl'] . ", ";
                        if (isset($final_ppList[$i]['ecl']) && preg_match('/(^amb$|^blu$|^brn$|^gry$|^grn$|^hzl$|^oth$)/', $final_ppList[$i]['ecl']) == 1) {
                            echo "yes" . $final_ppList[$i]['ecl'] . ", ";
                            if (isset($final_ppList[$i]['pid']) && preg_match('/^[0-9]{9}$/', $final_ppList[$i]['pid']) == 1) {
                                echo "yes" . $final_ppList[$i]['pid'] . ".";
                                $validCounter++;
                            }
                        }
                    }
                }
            }
        }
    }
}

echo "<br><strong>" . $validCounter . "</strong>";
// echo "<br>";

// for($m = 0; $m < count($pplist); $m++) {
//     echo "PP: " . $pplist[$m] . "<br>";
// }

// $fields_req = ["byr", "iyr", "eyr", "hgt", "hcl", "ecl", "pid"];
// print "<br>";
// $nbr = 1;
// $pp = array();
// $validCounter = 0;
// for ($i = 0; $i < count($pplist); $i++) {
//     // if(preg_match("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", $pplist[$i])) 
//     if (trim($pplist[$i]) == "") {
//         $nbr++;
//         echo "<br>Nix<br>";
//     } else {
//         $ppNbr = $nbr;

//         for ($k = 0; $k < count($fields_req); $k++) {           
//             $check = strstr($pplist[$i], $fields_req[$k]);
//             $value = string_between_two_string($pplist[$i], ':', ' ');
//             echo $value ."<br>";
//             // echo"<br>" . $ppNbr . " " . $fields_req[$k] . " checked: " . $pplist[$i] . ": ";
//             if (!isset($pp[$ppNbr][$fields_req[$k]]) || $pp[$ppNbr][$fields_req[$k]] == 0) {
//                 $pp[$ppNbr][$fields_req[$k]] = ($check != '') ? $value : "0";
              
//             }
//         }
//     }
// }
// print "<br>";
// for ($z = 0; $z < count($pp); $z++) {
//     // print $z + 1;
//     // print ". PP: ";
//     print_r($pp[$z+1]);
//     // print "<br>";
//     if (array_search(0, $pp[$z + 1]) == '') {
        // if($pp[$z + 1]['byr'] >= '1920' && $pp[$z + 1]['byr'] <= '2002'){
        //     echo $z+1 . " byr: yes";
        //     if($pp[$z + 1]['iyr'] >= '2010' && $pp[$z + 1]['iyr'] <= '2020') {
        //         echo $z + 1 . " iyr: yes";
        //         if($pp[$z + 1]['eyr'] >= '2020' && $pp[$z + 1]['eyr'] <= '2030') {
        //             echo $z + 1 . " eyr: yes";
        //             if((preg_match('/^(1[5-8]{2}|[8-9]{2}|[9][0-3])(cm)$/', $pp[$z + 1]['hgt']) == 1 || preg_match('/^(59|[6][0-9]|[7][0-6])(in)$/', $pp[$z + 1]['hgt']) == 1)){
        //                 echo $z + 1 . " hgt: yes";
        //                 if(preg_match('/^#[0-9a-f]{6}$/', $pp[$z + 1]['hcl']) == 1) {
        //                     echo $z + 1 . " hcl: yes";
        //                     if(preg_match('/(amb|blu|brn|gry|grn|hzl|oth)/', $pp[$z + 1]['ecl']) == 1) {
        //                         echo $z + 1 . " ecl: yes";
        //                         if(preg_match('/[0-9]{9}/', $pp[$z + 1]['pid']) == 1 ) {
        //                             echo $z + 1 . " pid: yes";
        //                             $validCounter++;

        //                         }
        //                     }
        //                 }
        //             }

        //         }

        //     }
        // }
             
//     }
// }
// echo "<br><strong>Valid Passports: " . $validCounter . "</strong>";


function string_between_two_string($str, $starting_word, $ending_word)
{
    $subtring_start = strpos($str, $starting_word);
    //Adding the strating index of the strating word to  
    //its length would give its ending index 
    $subtring_start += strlen($starting_word);
    //Length of our required sub string 
    $size = strpos($str, $ending_word, $subtring_start) - $subtring_start;
    // Return the substring from the index substring_start of length size  
    return substr($str, $subtring_start, $size);
} 
?>

<!-- <p>
byr (Birth Year) - four digits; at least 1920 and at most 2002.
iyr (Issue Year) - four digits; at least 2010 and at most 2020.
eyr (Expiration Year) - four digits; at least 2020 and at most 2030.
hgt (Height) - a number followed by either cm or in:
If cm, the number must be at least 150 and at most 193.
If in, the number must be at least 59 and at most 76.
hcl (Hair Color) - a # followed by exactly six characters 0-9 or a-f.
ecl (Eye Color) - exactly one of: amb blu brn gry grn hzl oth.
pid (Passport ID) - a nine-digit number, including leading zeroes.
cid (Country ID) - ignored, missing or not.

function string_between_two_string($str, $starting_word, $ending_word) 
{ 
    $subtring_start = strpos($str, $starting_word); 
    //Adding the strating index of the strating word to  
    //its length would give its ending index 
    $subtring_start += strlen($starting_word);   
    //Length of our required sub string 
    $size = strpos($str, $ending_word, $subtring_start) - $subtring_start;   
    // Return the substring from the index substring_start of length size  
    return substr($str, $subtring_start, $size);   
} 

</p> -->