<?php

$name = "Template engine";
$config_line .= AdmPlugin($name, "");

    override_function('parsetemplate', '$template, $array', 'return new_parsetemplate($template, $array);');
    rename_function("__overridden__", 'x');
    override_function('gettemplate', '$templatename', 'return new_gettemplate($templatename);');

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