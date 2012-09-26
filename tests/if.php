<?php
/**
 *A simple example of iteration
 * */
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'../Xtreme.php');
Xtreme::init();
Xtreme::setBaseDirectory(dirname(__FILE__));
Xtreme::setCachesDirectory('tmp');
Xtreme::setTemplatesDirectory('templates');
Xtreme::assign('numbers',array('1'=>1,'2'=>2,'3'=>3));
Xtreme::output('if',false,true);
?>