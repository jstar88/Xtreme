<?php
/**
 * examples of cache usage
 * */
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'../Xtreme.php');
Xtreme::init();
Xtreme::setBaseDirectory(dirname(__FILE__));
Xtreme::setCachesDirectory('tmp');
Xtreme::setTemplatesDirectory('templates');
Xtreme::assign('key','I have been compiled in compressed php code!');
$html=Xtreme::output('fasthello');
$html.= "<br>";

//using hardisk for storing compressed and compiled php
Xtreme::init();
Xtreme::setBaseDirectory(dirname(__FILE__));
Xtreme::setCachesDirectory('tmp');
Xtreme::setTemplatesDirectory('templates');
Xtreme::assign('key',"I'm more fast, i don't need to be compiled");
$html.=Xtreme::output('fasthello');
$html.= "<br>";

//using hardisk and internal cache
Xtreme::init();
Xtreme::setBaseDirectory(dirname(__FILE__));
Xtreme::setCachesDirectory('tmp');
Xtreme::setTemplatesDirectory('templates');
for($i=1;$i<=10;$i++){
   Xtreme::assign('key',"wow i'm super fast! number $i");
   $html.=Xtreme::output('fasthello',true);
   $html.= "<br>";
}
echo $html;
?>
