<?php
function array_dissect(array $array):array
{
    if(array_is_list($array)){
        return [$array[0],$array[1]];
    }
    $key=array_keys($array)[0];
    $value=array_values($array)[0];
    return [$key,$value];
}