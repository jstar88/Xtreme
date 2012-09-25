<?php
/**
 *A simple example of iteration
 * */
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'../Xtreme.php');
Xtreme::init();
Xtreme::setBaseDirectory(dirname(__FILE__));
Xtreme::setCachesDirectory('tmp');
Xtreme::setTemplatesDirectory('templates');
Xtreme::assign('names',array('Nick','Pier','John','Frank'));
Xtreme::output('foreach',false,true);
?>