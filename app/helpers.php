<?php 
function getControllerName(){
    $currentAction = \Route::currentRouteAction();
    list($controller, $method) = explode('@', $currentAction);    
    return $controller = preg_replace('/.*\\\/', '', $controller);
}
function flash($message,$level = 'info'){
    session()->flash('flash_message',$message);
    session()->flash('flash_message_level',$level);
}
function fixbrokenHtml($sValue){
    $doc = new DOMDocument();
    $doc->substituteEntities = false;
    $content = mb_convert_encoding($sValue, 'html-entities', 'utf-8');
    @$doc->loadHTML($content);
    $sValue = $doc->saveHTML();
    return $sValue;
}

function shortString($string,$length){    
    return fixbrokenHtml(substr(strip_tags(html_entity_decode($string),'<br><b>'),0,$length).'...');
}

function YMD2MDY($date, $join = '/') {
    $dateArr = preg_split("/[-\/ ]/", $date);
    return $dateArr[2] . $join. $dateArr[1] . $join . $dateArr[0];
}
function MDY2YMD($date, $join = '-'){
    $dateArr = preg_split("/[-\/ ]/", $date);
    return $dateArr[2] . $join. $dateArr[0] . $join . $dateArr[1];   
}

function DMY2YMD($date, $join = '-'){
    $dateArr = preg_split("/[-\/ ]/", $date);
    $dateN =  $dateArr[2] . $join. $dateArr[1] . $join . $dateArr[0];  
    return empty($dateArr[3]) ? $dateN:$dateN.' '.$dateArr[3];
}

function YMD2DMY($date, $join = '-'){
    $dateArr = preg_split("/[-\/ ]/", $date);
    $dateN =  $dateArr[2] . $join. $dateArr[1] . $join . $dateArr[0];  
    return empty($dateArr[3]) ? $dateN:$dateN.' '.$dateArr[3];
}
function DMY2YMDNoTime($date, $join = '-'){
    $dateArr = preg_split("/[-\/ ]/", $date);
    return $dateArr[2] . $join. $dateArr[1] . $join . $dateArr[0];
}

function getPakistanTime($date){
    $date = new DateTime($date, new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone('Asia/Karachi'));
    return $date->format('Y-m-d H:i:s');
}

function getSydnyTime($date){
    $date = new DateTime($date, new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone('Australia/Sydney'));
    return $date->format('Y-m-d H:i:s');
}

    
