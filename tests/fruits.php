<?php
/**
 *A simple example of array usage
 * */
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'../Xtreme.php');
Xtreme::init();
Xtreme::setBaseDirectory(dirname(__FILE__));
Xtreme::setCachesDirectory('tmp');
Xtreme::setTemplatesDirectory('templates');

$array=array('key1'=>'apple','key2'=>'pear');
$array2=array('key3'=>'strawberry','key4'=>'plum');
Xtreme::assign($array);
Xtreme::assign('key',$array2);
$html=Xtreme::output('fruits');
echo $html;
?>
