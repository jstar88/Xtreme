<?php
class SystemUtil{

public static function list_recursively($directory,$callback, $filter=FALSE)
{
	if(substr($directory,-1) == '/')
	{
		$directory = substr($directory,0,-1);
	}
	if(!file_exists($directory) || !is_dir($directory))
	{
		return FALSE;
	}elseif(is_readable($directory))
	{
		$directory_list = opendir($directory);
		while($file = readdir($directory_list))
		{
			if($file != '.' && $file != '..')
			{
				$path = $directory.'/'.$file;
				if(is_readable($path))
				{
					$subdirectories = explode('/',$path);
					if(is_dir($path))
					{
						   $info = array(
							'path'      => $directory.'/',
							'name'      => str_replace(".$extension",'',basename($path)),
							'kind'      => 'directory' );
							call_user_func_array($callback,$info);
							list_recursively($path,$callback,$filter);
					}elseif(is_file($path))
					{
						$extension = end(explode('.',end($subdirectories)));
						if($filter === FALSE || $filter == $extension)
						{
							$info = array(
							'path'		=> $directory.'/',
							'name'		=> str_replace(".$extension",'',basename($path)),
							'extension' => $extension,
							'size'		=> filesize($path),
							'kind'		=> 'file');
							 call_user_func($callback,$info);
						}
					}
				}
			}
		}
		closedir($directory_list);
		return ;
	}else{
		return FALSE;
	}
}

public static function listFile_recursively($directory,$callback, $filter=FALSE)
{
	if(substr($directory,-1) == '/')
	{
		$directory = substr($directory,0,-1);
	}
	if(!file_exists($directory) || !is_dir($directory))
	{
		return FALSE;
	}elseif(is_readable($directory))
	{
		$directory_list = opendir($directory);
		while($file = readdir($directory_list))
		{
			if($file != '.' && $file != '..')
			{
				$path = $directory.'/'.$file;
				if(is_readable($path))
				{
					$subdirectories = explode('/',$path);
					if(is_dir($path))
					{
					    list_recursively($path,$callback,$filter);
					}elseif(is_file($path))
					{
						$extension = end(explode('.',end($subdirectories)));
						if($filter === FALSE || $filter == $extension)
						{
							$info = array(
							'path'		=> $directory.'/',
							'name'		=> str_replace(".$extension",'',basename($path)),
							'extension' => $extension,
							'size'		=> filesize($path));
                           // echo "found".$info[path].$info['name'];
							 call_user_func($callback,$info);
						}
					}
				}
			}
		}
		closedir($directory_list);
		return ;
	}else{
		return FALSE;
	}
}

//search for a file in all application: return false in case of fail
public static function search_recursively($directory,$filex)
{
   if(substr($directory,-1) == '/')
	{
		$directory = substr($directory,0,-1);
	}
   if(is_readable($directory))
	{
	  $directory_list = opendir($directory);
	  while($file = readdir($directory_list))
		{
		      $path = $directory.'/'.$file;
				if( is_file($path) && $filex==$file)
				{
						$info = array(
						'path'		=> $directory,
						'size'		=> filesize($path));
						return $info;
				}
				elseif(is_dir($path))
				  return search_recursively($path,$filex);
		}
		closedir($directory_list);
	}
	return false;   
}


}
?>
