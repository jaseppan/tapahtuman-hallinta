<?php
// array_search_match($needle, $haystack) returns all the keys of the values that match $needle in $haystack

function array_search_all($needle, $haystack) {
    foreach ($haystack as $k=>$v) {   
        if($haystack[$k]==$needle){       
           $array[] = $k;
        }
    }
    return $array;  
}
?>
