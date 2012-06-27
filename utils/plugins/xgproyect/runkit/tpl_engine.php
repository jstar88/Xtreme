<?php

$name = "Template engine";
$config_line .= AdmPlugin($name, "");

    runkit_function_rename('parsetemplate','parsetemplate_old');
    runkit_function_rename('new_parsetemplate','parsetemplate');
    
    runkit_function_rename('gettemplate','gettemplate_old');
    runkit_function_rename('new_gettemplate','gettemplate');

function new_parsetemplate($template, $array = array())
{

    include_once (XGP_ROOT . 'lib/Xtreme.php');
    Xtreme::init();
    Xtreme::setBaseDirectory(XGP_ROOT);
    Xtreme::setCachesDirectory('cache');
    Xtreme::setTemplatesDirectory(TEMPLATE_DIR);
    Xtreme::setTemplateExtension(Xtreme::PHP);
    Xtreme::assign($array);
    return Xtreme::output($template);
}

function new_gettemplate($templatename)
{
    return $templatename;
}

?>