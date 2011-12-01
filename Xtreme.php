<?php

/**
 * Xtreme
 * 
 * @package Xtreme  
 * @author Covolo Nicola
 * @copyright Covolo Nicola
 * @license GNU v3 *[no profit]
 * @access public
 * @since 2011
 * @version 2.0 
 * -> static architecture for better performance;
 * -> single global cache;
 * -> fix the internal cache system;
 * -> multidimensional language array support;
 * -> now you can choose to throw an exception if a key in template don't exist in language file;
 * -> automatic OS-dipendent path separators replacement;
 * -> cache saved in php formatt for better performance;
 */
abstract class Xtreme
{

    //----don't change these!----
    const SHOW_TAG = 'SHOW_TAG';
    const HIDE_TAG = 'HIDE_TAG';
    const DELETE_TAG = 'DELETE_TAG';
    const THROW_EXCEPTION = 'THROW_EXCEPTION';
    const PHP = 'PHP';
    const JSON = 'JSON';
    const XML = 'XML';
    const INI = 'INI';
    //---------------------------
    
    const TEMPLATE_CACHE_DIRECTORY = 'templates';
    const LANG_CACHE_DIRECTORY = 'langs';
    const DEFAULT_TEMPLATE = 'tpl';
    const DEFAULT_MASTER_LEFT = '{';
    const DEFAULT_MASTER_RIGHT = '}';
    const DEFAULT_ARRAY_LINK = '.';
    const DEFAULT_LANG_EXTENSION = self::JSON;
    const DEFAULT_LANGCACHE_ARRAYNAME = 'lang';


    //--------only internal usage
    private static $groups_template;
    private static $groups_php;
    private static $groups_html;
    private static $languages;
    private static $readyCompiled;
    private static $currentMainName;

    //--------external dependency
    private static $baseDirectory;
    private static $compileDirectory;
    private static $langDirectory;
    private static $langExtension;
    private static $templateExtension;
    private static $templateDirectories;
    private static $useCache;
    private static $useCompileCompression;
    private static $config;
    private static $onInexistenceTag;
    private static $country;
    private static $langCacheArrayName;

    /**
     * Xtreme::init()
     * 
     * @return null
     */
    public static function init()
    {
        self::$baseDirectory = self::appendSeparator(dirname(__file__));
        self::$compileDirectory = self::$baseDirectory;
        self::$templateDirectories = self::$baseDirectory;
        self::$langDirectory = self::$baseDirectory;
        self::$templateExtension = self::DEFAULT_TEMPLATE;
        self::$langExtension = self::DEFAULT_LANG_EXTENSION;
        self::$langCacheArrayName = self::DEFAULT_LANGCACHE_ARRAYNAME;
        self::$readyCompiled = array();
        self::$groups_template = new stdClass;
        self::$groups_php = new stdClass;
        self::$groups_html = new stdClass;
        self::$useCache = true;
        self::$useCompileCompression = true;
        self::$config = array('master' => array('left' => self::DEFAULT_MASTER_LEFT, 'right' => self::DEFAULT_MASTER_RIGHT), 'arrayLink' => self::DEFAULT_ARRAY_LINK);
        self::$onInexistenceTag = self::HIDE_TAG;
        self::$country = '';
        self::$languages = array();
        self::$currentMainName = '';
    }
    //-------------PATH FUNCTIONS---------------
    /**
     * Xtreme::appendSeparator()
     * Function used to append a separator at the end of path if it there isn't
     * 
     * @param mixed $path
     * @return the path
     */
    private static function appendSeparator($path)
    {
        if (substr($path, -1) != DIRECTORY_SEPARATOR)
            $path .= DIRECTORY_SEPARATOR;
        return $path;
    }
    /**
     * Xtreme::makeAbsolute()
     * Function used to added the root directory to path that don't have the directory separator at the start position
     * @param mixed $path
     * @return the path
     */
    private static function makeAbsolute($path)
    {
        return ($path{0} != DIRECTORY_SEPARATOR) ? self::$baseDirectory . $path : $path;
    }
    /**
     * Xtreme::fixSeparators()
     * Function used to make the paths OS-indipendeds
     * 
     * @param mixed $path
     * @return the path
     */
    private static function fixSeparators($path)
    {
        return trim(str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path));
    }
    /**
     * Xtreme::sanitizePath()
     * Function used to sanitize the input path. This function iglobe the above declarated functions
     * 
     * @param mixed $path
     * @return null
     */
    private static function sanitizePath($path)
    {
        return self::makeAbsolute(self::appendSeparator(self::fixSeparators($path)));
    }

    /**
     * Xtreme::sanitizeFoolder()
     * Function used to sanitize the input foolder. must be like "folder/"
     * 
     * @param mixed $foolder
     * @return null
     */
    private static function sanitizeFoolder($foolder)
    {
        if ($foolder{0} == DIRECTORY_SEPARATOR)
            $foolder = substr($foolder, 1);
        return self::appendSeparator(self::fixSeparators($foolder));
    }

    /**
     * Xtreme::sanitizeRoot()
     * Function used to sanitize the input root. must be like "*rootpath/"
     * 
     * @param mixed $path
     * @return null
     */
    private static function sanitizeRoot($path)
    {
        return self::appendSeparator(self::fixSeparators($path));
    }

    /**
     * Xtreme::getTplPath()
     * Function to know the complete path of template.
     * 
     * @param mixed $template
     * @return The complete path of the template passed. 
     */
    private static function getTplPath($template)
    {
        return self::$templateDirectories . self::fixSeparators($template) . '.' . self::$templateExtension;
    }

    /**
     * Xtreme::getCompiledTplPath()
     * Function to know the complete path of compiled templates(caches).
     * 
     * @param mixed $template
     * @return he complete path of compiled templates passed.
     */
    private static function getCompiledTplPath($template)
    {
        return self::$compileDirectory . self::TEMPLATE_CACHE_DIRECTORY . DIRECTORY_SEPARATOR . self::fixSeparators($template) . '.php';
    }

    /**
     * Xtreme::getLangPath()
     * Function to know the complete path of language file.
     * 
     * @param mixed $lang
     * @return The complete path of the language file passed.
     */
    private static function getLangPath($lang)
    {
        return self::$langDirectory . self::$country . self::fixSeparators($lang) . '.' . strtolower(self::$langExtension);
    }

    /**
     * Xtreme::getCompiledLangPath()
     * Function to know the complete path of compiled language file(caches).
     * 
     * @param mixed $lang
     * @return  The complete path of compiled language file passed.
     */
    private static function getCompiledLangPath($lang)
    {
        return self::$compileDirectory . self::LANG_CACHE_DIRECTORY . DIRECTORY_SEPARATOR . self::$country . self::fixSeparators($lang) . '.' . strtolower(self::JSON);
    }
    //-----------------------------------------

    //-------USER PREFERENCE FUNCTIONS
    /**
     * Xtreme::setBaseDirectory()
     * 
     * @param mixed $new
     * @return null
     */
    public static function setBaseDirectory($new)
    {
        self::$baseDirectory = self::sanitizeRoot($new);
    }
    /**
     * Xtreme::setCachesDirectory()
     * 
     * @param mixed $new
     * @return null
     */
    public static function setCachesDirectory($new)
    {
        self::$compileDirectory = self::sanitizePath($new);
    }
    /**
     * Xtreme::setLanguagesDirectory()
     * 
     * @param mixed $new
     * @return null
     */
    public static function setLanguagesDirectory($new)
    {
        self::$langDirectory = self::sanitizePath($new);
    }
    /**
     * Xtreme::setTemplatesDirectory()
     * 
     * @param mixed $new
     * @return null
     */
    public static function setTemplatesDirectory($new)
    {
        self::$templateDirectories = self::sanitizePath($new);
    }
    /**
     * Xtreme::setTemplateExtension()
     * 
     * @param mixed $new
     * @return null
     */
    public static function setTemplateExtension($new)
    {
        self::$templateExtension = ($new{0} == '.') ? substr($new, 1) : $new;
    }
    /**
     * Xtreme::setLangExtension()
     * 
     * @param mixed $new
     * @return null
     */
    public static function setLangExtension($new)
    {
        self::$langExtension = constant("self::$new");
    }
    /**
     * Xtreme::setConfig()
     * 
     * @param mixed $new
     * @return null
     */
    public static function setConfig($new)
    {
        self::$config = $new;
    }
    /**
     * Xtreme::setOnInexistenceTagEvent()
     * 
     * @param mixed $new
     * @return null
     */
    public static function setOnInexistenceTagEvent($new)
    {
        self::$onInexistenceTag = constant("self::$new");
    }
    /**
     * Xtreme::useCache()
     * 
     * @param mixed $status
     * @return null
     */
    public static function useCache($status)
    {
        self::$useCache = $status;
    }
    /**
     * Xtreme::useCompileCompression()
     * 
     * @param mixed $status
     * @return null
     */
    public static function useCompileCompression($status)
    {
        self::$useCompileCompression = $status;
    }

    /**
     * Xtreme::switchCountry()
     * Function used to switch language foolder.if second param is true then keys in memory of old language will be cleaned.
     * 
     * @param mixed $country
     * @param bool $cleanOld
     * @return null
     */
    public static function switchCountry($country, $cleanOld = false)
    {
        $country = $self::sanitizeFoolder($country);
        if ($cleanOld && isset(self::$languages[$country]))
        {
            unset(self::$languages[$country]);
        }
        self::$country = $country;
        if (!isset(self::$languages[$country]))
        {
            self::$languages[$country] = new stdClass();
        }
    }
    //------------------------------------------

    //-----------USEFUL FUNCTIONS FOR BUILDING PAGES
    public static function get()
    {
        $numargs = func_num_args();
        $arg_list = func_get_args();
        if ($numargs == 0)
            throw exception ('Function get() must be called with at least 1 arguments');
        if (!property_exists(self::$languages[self::$country], $arg_list[0]))
        {
            return self::errorManagement($arg_list, $numargs);
        }
        $return = self::$languages[self::$country]->$arg_list[0];
        for ($i = 1; $i < $numargs; $i++)
        {
            if (!isset($return[$arg_list[$i]]))
            {
                return self::errorManagement($arg_list, $numargs);
            }
            $return = $return[$arg_list[$i]];
        }
        return $return;
    }

    private static function buildArrayString($arg_list, $numargs)
    {
        $return = $arg_list[0];
        for ($i = 1; $i < $numargs; $i++)
            $return .= '[' . $arg_list[$i] . ']';
        return $return;
    }
    private static function errorManagement($arg_list, $numargs)
    {
        $return = '';
        switch (self::$onInexistenceTag)
        {
            case self::HIDE_TAG:
                $return = self::buildArrayString($arg_list, $numargs);
                $return = "<!--$return-->";
                break;
            case self::DELETE_TAG:
                $return = '';
                break;
            case self::SHOW_TAG:
                $return = self::buildArrayString($arg_list, $numargs);
                $return = "{$return}";
                break;
            case self::THROW_EXCEPTION:
                $desc = self::buildArrayString($arg_list, $numargs);
                throw exception ('Tryed to access to null language reference: ' . $desc);
                break;
            default:
                break;
        }
        return $return;
    }

    /**
     * Xtreme::assignLangFile()
     * Assign a language file
     * 
     * @param mixed $path
     * @param mixed $phpVars
     * @return null
     */
    public static function assignLangFile($path, $phpVars = null)
    {
        $langPath = self::getLangPath($path);
        $langCompiledPath = self::getCompiledLangPath($path);
        $lang = '';
        if (defined("LANG_{$path}_INSIDE"))
            return;
        if (self::$langExtension != self::PHP)
        {
            if (file_exists($langCompiledPath))
            {
                $lang = self::open_PHP($langCompiledPath, self::$langCacheArrayName);
            } elseif (file_exists($langPath))
            {
                $function = "open_" . self::$langExtension;
                $lang = self::$function($langPath, $phpVars);
                $self::saveAsPHP($langCompiledPath, $lang);
            }
            else
                die('Lang (' . $langPath . ') not found ');
        }
        else
        {
            $lang = self::open_PHP($langPath, $phpVars);
        }
        self::assign($lang);
        define("LANG_{$path}_INSIDE", true);
    }

    /* @example
    Xtreme assign('key','value');
    Xtreme::assign(array('key1'=>'value1','key2'=>'value2'));
    Xtreme::assin(new stdClass());
    Xtreme::assign('key',array('value1','value2','value3'));
    */
    /**
     * Xtreme::assign()
     * Assign a key-value. The key will be replaced with value contents when the bufferedOutput is called
     * 
     * @param mixed $key
     * @param string $value
     * @return null
     */
    public static function assign($key, $value = '')
    {
        if (is_array($key))
        {
            foreach ($key as $n => $v)
                self::$languages[self::$country]->$n = $v;
        } elseif (is_object($key))
        {

            foreach (get_object_vars($key) as $n => $v)
                self::$languages[self::$country]->$n = $v;
        } elseif (is_array($value))
        {
            foreach ($value as $k => $v)
            {
                self::$languages[self::$country]->{$key}[$k] = $v;
            }
        }
        else
            self::$languages[self::$country]->$key = $value;
    }

    /**
     * Xtreme::append()
     * Function to append a new value to the corrispective key passed 
     * 
     * @param mixed $key
     * @param string $value
     * @return null
     */
    public static function append($key, $value = '')
    {
        if (!property_exists(self::$languages[self::$country], $key))
        {
            self::$languages[self::$country]->$key = '';
        }
        self::$languages[self::$country]->$key .= $value;
    }

    /**
     * Xtreme::push()
     * Push a value in a array placed in passed key
     * 
     * @param mixed $key
     * @param mixed $value
     * @return null
     */
    public static function push($key, $value = null)
    {
        if (!property_exists(self::$languages[self::$country], $key))
        {
            self::$languages[self::$country]->$key = array();
        }
        $data = self::$languages[self::$country]->$key;
        $data[] = $value;
        self::$languages[self::$country]->$key = $data;
    }

    /**
     * Xtreme::output()
     * Get the html from parsed templates.
     * 
     * @param mixed $templates : an Array of templates that will be parsed and appended to html.
     * @param bool $reuse : if true, the parsed template is saved in internal variable for next fast usage
     * @param bool $draw : if true, output the html in screen.
     * @return html if draw option is set to false, nothing otherwise.
     */
    public static function output($templates, $reuse = false, $draw = false, $forGroup = false, $groupId = false)
    {
        if (!is_array($templates))
            $templates = explode('|', $templates);
        $out = '';
        foreach ($templates as $template)
        {
            if ($forGroup)
            {
                $templateName = self::getGroupCacheName($groupId, $template);
                $compiledTemplateFile = self::getCompiledTplPath(md5($templateName));
                $templateFile = self::getTplPath($template);
            }
            else
            {
                $compiledTemplateFile = self::getCompiledTplPath($template);
                $templateFile = self::getTplPath($template);
            }

            if (isset(self::$readyCompiled[$compiledTemplateFile]) && $reuse)
                $out .= self::$readyCompiled[$compiledTemplateFile]['code'];
            elseif (file_exists($compiledTemplateFile) && filemtime($compiledTemplateFile) >= filemtime($templateFile) && self::$useCache)
                $out .= self::bufferedOutput($compiledTemplateFile);
            elseif (file_exists($templateFile))
            {
                if ($forGroup)
                    self::compileGroup($groupId);
                self::save($compiledTemplateFile, self::compile($templateFile));
                $tmp = self::bufferedOutput($compiledTemplateFile);
                $out .= $tmp;
                if ($reuse)
                {
                    self::$readyCompiled[$compiledTemplateFile]['code'] = $tmp;
                }
            }
            else
                die('Template (' . $templateFile . ') not found ');
        }
        if (!$draw)
            return $out;
        echo $out;
    }
    public static function assignForReuse($templateName, $key, $value = 'null')
    {
        //if cache don't exist then this is a pre-assign
        if (!isset(self::$readyCompiled[$templateName]))
        {
            self::assign($key, $value);
        }
        else
        {
            if (is_array($key))
            {
                foreach ($key as $n => $v)
                    self::replaceCacheValues($templateName, $n, $v);
            } elseif (is_object($key))
            {
                foreach (get_object_vars($key) as $n => $v)
                    self::replaceCacheValues($templateName, $n, $v);
            } elseif (is_array($value))
            {
                self::replaceCacheValues($templateName, $key, (object)$value);
            }
            else
                self::replaceCacheValues($templateName, $key, $value);
        }
    }

    public static function assignToGroup($groupId, $blockId, $templateName = '', $type = 'template')
    {

        $storeType = 'groups';
        if ($type == 'template')
        {
            $storeType .= '_template';
        }
        else
        {
            $storeType .= '_html';
        }
        if (!property_exists(self::$$storeType, $groupId))
            self::$$storeType->{$groupId} = array();

        if (is_array($blockId))
        {
            foreach ($blockId as $n => $v)
            {
                self::$$storeType->{$groupId}[$n] = $v;
            }
        } elseif (is_object($blockId))
        {
            foreach (get_object_vars($blockId) as $n => $v)
                self::$$storeType->{$groupId}[$n] = $v;
        }
        else
        {
            self::$$storeType->{$groupId}[$blockId] = $templateName;
        }
    }
    public static function setCurrentMain($main)
    {
        self::$currentMainName = $main;
    }
    public static function assignGroupToGroup($startGroup, $startTemplate, $toGroup, $key)
    {
        $startTemplate = self::getTplPath($startTemplate);
        $CacheName = self::getGroupCacheName($startGroup, $startTemplate);
        if (!property_exists(self::$groups_php, $toGroup))
            self::$groups_php->{$toGroup} = array();
        self::$groups_php->{$toGroup}[$key]['cacheName'] = $CacheName;
        self::$groups_php->{$toGroup}[$key]['children_group'] = $startGroup;
        self::$groups_php->{$toGroup}[$key]['template'] = $startTemplate;
    }
    private static function existCurrentMainCache()
    {
        return file_exists(self::getCompiledTplPath(self::$currentMainName));
    }

    public static function outputGroup($groupId, $template, $reuse = false, $draw = false)
    {
        return self::output($template, $reuse, $draw, true, $groupId);
    }

    public static function clearCurrentLanguage()
    {
        self::$languages[self::$country] = new stdClass;
    }

    public static function clearReadyCompiled()
    {
        self::$readyCompiled = array();
    }

    public static function clearGroups()
    {
        self::$groups_php = new stdClass;
        self::$groups_html = new stdClass;
        self::$groups_template = new stdClass;
    }

    //----------FILES FUNCTION

    /**
     * Xtreme::saveAsPHP()
     * Save an array to php code
     * 
     * @param mixed $path
     * @param mixed $array
     * @return null
     */
    private static function saveAsPHP($path, $array)
    {
        $page = '<?php';
        $page .= self::transformArrayToPHP($array, '$' . self::$langCacheArrayName);
        $page .= '?>';
        self::save($path, $page);
    }

    /**
     * Xtreme::transformArrayToPHP()
     * transform recursively an array to php code
     * 
     * @param mixed $array : the array to parse
     * @param mixed $string : the rappresentation of passed array in php code
     * @return null
     */
    private static function transformArrayToPHP($array, $string)
    {
        foreach ($array as $key => $value)
        {
            $string .= '[\'' . $key . '\']=';
            if (is_array($value))
            {
                self::transformArrayToPHP($value, $string);
            }
            $string .= $value . ';';
        }
    }

    /**
     * Xtreme::save()
     * Recursively create folders path and save the contents to target file
     * 
     * @param mixed $file : target file complete path es('/myfolder/myfile.json') 
     * @param mixed $content : the contents that you want to save in passed file path
     * @return null
     */
    private static function save($file, $content)
    {
        $path = substr($file, 0, strrpos($file, DIRECTORY_SEPARATOR, -1));
        if (!file_exists($path) && mkdir($path, 0755, true) === false)
            echo "failed to create [$path] directory";
        if (file_put_contents($file, $content) === false)
            echo "failed to save [$file]";
    }

    /**
     * Xtreme::open_PHP()
     * Open a php file and recursively merge multidimensional values of differents array in one.
     * 
     * @param mixed $path : the target file path.
     * @param mixed $phpVars : an array containg the arrays name in php file 
     * @return the created array.
     */
    private static function open_PHP($path, $phpVars)
    {
        $container = array();
        require ($path);
        foreach ($phpVars as $var)
            if (isset($$var))
                $container = array_merge_recursive($container, $$var);
        return $container;
    }

    /**
     * Xtreme::open_JSON()
     * Open a json file and decode its content to multidimensional array.
     * 
     * @param mixed $path :the path of json file.
     * @return the created array.
     */
    private static function open_JSON($path)
    {
        return json_decode(file_get_contents($path), true);
    }

    /**
     * Xtreme::open_XML()
     * Open a xml file and decode its content to multidimensional array.
     * 
     * @param mixed $path :the path of xml file.
     * @return the created array.
     */
    private static function open_XML($path)
    {
        return simplexml_load_file($path);
    }


    /**
     * Xtreme::open_INI()
     * Open a ini file and decode its content to multidimensional array.
     * 
     * @param mixed $path :the path of ini file.
     * @return the created array.
     */
    private static function open_INI($path)
    {
        if (function_exists('parse_ini_string'))
            return parse_ini_string(file_get_contents($path), true);
        else
            return parse_ini_file($path, true);
    }
    //--------------------------------------------

    //----------- PARSE FUNCTIONS
    private static function replaceCacheValues($templateName, $key, $value)
    {
        //if the key don't exist in the map then map it
        if (!isset($self::$readyCompiled[$templateName]['map'][$key]))
        {
            $self::$readyCompiled[$templateName]['map'][$key] = self::getKeyMap(self::$readyCompiled[$templateName]['code'], self::get($key));
        }
        //replacing
        foreach ($self::$readyCompiled[$templateName]['map'][$key] as $position)
        {
            substr_replace($self::$readyCompiled[$templateName]['code'], $value, $position);
        }
    }
    private static function getKeyMap($code, $value)
    {
        $positions = array();
        if (empty($value))
            return $positions;
        while ($pos = strpos($code, $value))
        {
            $positions[] = $pos;
            $code = substr($code, $pos + strlen($value));
        }
        return $positions;
    }
    private static function compile($string)
    {
        $lines = file($string);
        $newLines = array();
        $matches = null;
        $masterLeft = self::$config['master']['left'];
        $masterRight = self::$config['master']['right'];
        $regex = "/\\{$masterLeft}([^{$masterLeft}{$masterRight}]+)\\{$masterRight}/";

        foreach ($lines as $line)
        {
            $num = preg_match_all($regex, $line, &$matches);
            if ($num > 0)
            {
                for ($i = 0; $i < $num; $i++)
                {
                    $match = $matches[0][$i];
                    if (strpos($matches[1][$i], ';') !== false)
                        continue;
                    $new = self::transformSyntax($matches[1][$i]);
                    $line = str_replace($match, $new, $line);
                }
            }
            $newLines[] = $line;
        }
        if (self::$useCompileCompression)
            return self::html_compress(implode('', $newLines));
        else
            return implode('', $newLines);
    }

    private static function compileGroup($groupId)
    {
        if (property_exists(self::$groups_html, $groupId))
            foreach (self::$groups_html->$groupId as $blockId => $html)
                self::assign($groupId, array($blockId => $html));
        if (property_exists(self::$groups_php, $groupId))
        {
            foreach (self::$groups_php->$groupId as $key => $php)
            {
                self::compileGroup($php['children_group']); //non ritorna nulla
                self::assign($groupId, array($key => self::compile($php['template'])));
                unset(self::$groups_php->$groupId[$key]);
            }
        }
        if (property_exists(self::$groups_template, $groupId))
        {
            foreach (self::$groups_template->$groupId as $blockId => $templateName)
            {
                self::assign($groupId, array($blockId => self::compile(self::getTplPath($templateName))));
            }
        }


    }
    private static function getGroupCacheName($groupId, $template)
    {
        $paths = "";
        if (property_exists(self::$groups_template, $groupId))
            foreach (self::$groups_template->$groupId as $blockId => $templateName)
                $paths .= $templateName;
        if (property_exists(self::$groups_php, $groupId))
            foreach (self::$groups_php->$groupId as $blockId => $php)
                $paths .= $php['cacheName'];
        return $template . $paths;
    }
    private static function isPartOfCurrentMain($name)
    {
        return strpos($name, self::$currentMainName) !== false;
    }

    private static function html_compress($html)
    {
        preg_match_all('!(<(?:code|pre).*>[^<]+</(?:code|pre)>)!', $html, $pre);
        $html = preg_replace('!<(?:code|pre).*>[^<]+</(?:code|pre)>!', '#pre#', $html); //ok
        $html = preg_replace('#<!–[^\[].+–>#', "", $html); //ok
        $html = preg_replace('/ {2,}/', ' ', $html); //ok
        $html = str_replace(array('\r', '\n', '\t'), '', $html);
        $html = preg_replace('/>[\s]+</', '><', $html); //ok
        if (!empty($pre[0]))
            foreach ($pre[0] as $tag)
                $html = preg_replace('!#pre#!', $tag, $html, 1);
        return $html;

    }
    private static function replace_callback($args)
    {
        $parametri = $args[2];
        $parametri = preg_replace('/([a-zA-Z0-9_]*)\\' . self::$config['arrayLink'] . '/i', '\'$1\',', $parametri);
        $parametri = preg_replace('/[,]([a-zA-Z0-9_]*[^,])/i', ',\'$1\'', $parametri);
        if (strpos($parametri, ',') === false)
            $parametri = "'$parametri'";
        return $args[1] . 'self::get(' . $parametri . ')';
    }

    private static function transformSyntax($input)
    {
        $from = '/(^|\[|,|\(|\+| )([a-zA-Z0-9_\\' . self::$config['arrayLink'] . ']*)($|\.|\)|\[|\]|\+)/';
        $to = 'self::replace_callback';

        $parts = explode(':', $input);

        $string = '';
        switch ($parts[0])
        {
            case 'if':
            case 'switch':
                $string = '<?php ' . $parts[0] . '(' . preg_replace_callback($from, $to, $parts[1]) . ') { ' . ($parts[0] == 'switch' ? 'default: ?>' : ' ?>');
                break;
            case 'foreach':
                $pieces = explode(',', $parts[1]);
                $string = '<?php foreach(' . preg_replace_callback($from, $to, $pieces[0]) . ' as ';
                $string .= preg_replace_callback($from, $to, $pieces[1]);
                if (sizeof($pieces) == 3)
                    $string .= '=>' . preg_replace_callback($from, $to, $pieces[2]);
                $string .= ') {  ?>';
                break;
            case 'end':
            case 'endswitch':
                $string = '<?php } ?>';
                break;
            case 'else':
                $string = '<?php } else { ?>';
                break;
            case 'case':
                $string = '<?php break; case ' . preg_replace_callback($from, $to, $parts[1]) . ': ?>';
                break;
            case 'include':
                $string = '<?php echo self::output("' . $parts[1] . '"); ?>';
                break;
            case 'group':
                $string = self::get($parts[1], $parts[2]);
                break;
            default:
                $string = '<?php echo ' . preg_replace_callback($from, $to, $parts[0]) . '; ?>';
                break;
        }
        return $string;
    }
    private static function bufferedOutput($compiledFile)
    {
        ob_start();
        include ($compiledFile);
        $out = ob_get_clean();
        return $out;
    }
    //--------------------------------------------
}

?>