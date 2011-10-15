<?php
$action='';
if ($_POST)
{
    $directory = $_POST['directory'];
    $targetExt = $_POST['target_ext'];
    $sourceExt = $_POST['source_ext'];
    
    include('AutoConverter.php');
    $converter = new AutoConverter();
    //$converter->set_phpVar($_POST['php_var']);
    $converter->convertRecursively($directory, $sourceExt, $targetExt);
    $action= 'done';
}
$form="<html><head></head><body>
<form action='index.php' method='post'>
    directory in cui cercare <input type='text' name='directory' ></br>
    estensione presente<input type='text' name='source_ext' ></br>
    estensione desiderata<input type='text' name='target_ext' ></br>
    nome array in caso di sorgente php(mo)<input type='text' name='php_var' ></br>
    <input type='submit' value='Submit'>    
</form>
</body></html>";
echo $form.$action;

?>
