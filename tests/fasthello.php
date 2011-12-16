<?php
/**
 * examples of internal cache usages
 * */
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'../Xtreme.php');

$html='';
Xtreme::init();
Xtreme::setBaseDirectory(dirname(__FILE__));
Xtreme::setCachesDirectory('tmp');
Xtreme::setTemplatesDirectory('templates');
for($i=1;$i<=10;$i++){
   Xtreme::assignForReuse('fasthello','key',"wow i'm super fast! number $i");
   $html.=Xtreme::output('fasthello',true);
   $html.= "<br>";
}
echo $html;
echo '<br>*Hard-disk accesses: '.Xtreme::getHddAccess();
?>
