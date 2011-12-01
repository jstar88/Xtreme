<?php
/**
 *A simple example of variable usage
 * */
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'../Xtreme.php');
Xtreme::init();
Xtreme::setBaseDirectory(dirname(__FILE__));
Xtreme::setCachesDirectory('tmp');
Xtreme::setTemplatesDirectory('templates');
Xtreme::assign('key','Hello world!');
Xtreme::output('hello',false,true);
?>