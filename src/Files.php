<?php
namespace Gugusd999\Web;

class Files
{
    public static function exist(...$arg)
    {
        if(isset($arg[0]))
        {
            if(file_exists($arg[0]))
            {
                return true;
            }else{
                return false;
            }
        }
    }

    public static function slug(...$arg){
        if(isset($arg[0])){
            $name = $arg[0];
            $name = str_replace('"','',$name);
            $name = str_replace("'",'',$name);
            $name = str_replace(" ",'-',$name);
            $name = str_replace("@",'',$name);
            $name = str_replace(",",'',$name);
            $name = str_replace(".",'',$name);
            $name = str_replace("/",'',$name);
            $name = str_replace("|",'',$name);
            $name = str_replace("\\",'',$name);
            $name = str_replace("=",'',$name);
            $name = str_replace("+",'',$name);
            $name = str_replace("(",'',$name);
            $name = str_replace(")",'',$name);
            $name = str_replace("[",'',$name);
            $name = str_replace("]",'',$name);
            $name = str_replace(";",'',$name);
            $name = str_replace(":",'',$name);
            $name = str_replace("`",'',$name);
            $name = str_replace("#",'',$name);
            $name = str_replace("\$",'',$name);
            $name = str_replace("%",'',$name);
            $name = str_replace("^",'',$name);
            $name = str_replace("&",'',$name);
            $name = str_replace("?",'',$name);
            $name = str_replace("~",'',$name);
            return strtolower($name);
        }
    }

    public static function remove(...$arg){
        if(isset($arg[0])){
            if(file_exists($arg[0])
                && $arg[0] != ''
                && $arg != '/'
                && $arg != 'web'
                && $arg != 'views'
                && $arg != 'vendor'
                && $arg != 'module'
            ){
                unlink($arg[0]);
            }
        }
    }

    public static function write(...$arg)
    {
        if(isset($arg[0]) && isset($arg[1]))
        {
            $myfile = fopen($arg[0], "w") or die("Unable to open file!");
            $txt = $arg[1];
            fwrite($myfile, $txt);
            fclose($myfile);
        }
    }

    public static function read(...$arg)
    {
        $path = $arg[0];
        if(!file_exists($path)){
            return null;
        }
        $myfile = fopen($path, "r") or die("Unable to open file!");
        $er = fread($myfile,filesize($path));
        fclose($myfile);
        return $er;
    }
    
    function dir($directory = "./") {
        // Get the list of files and folders in the directory
        $contents = scandir($directory);
    
        // Exclude "." and ".." from the list
        $contents = array_diff($contents, array('.', '..'));
    
        // Initialize the array to hold the result
        $result = [];
    
        // Iterate through each item
        foreach ($contents as $item) {
            $itemPath = $directory . '/' . $item;
    
            // Determine if it's a folder or file
            if (is_dir($itemPath)) {
                // If it's a folder, add it to the result with type "folder"
                $result[] = [
                    'name' => $item,
                    'type' => 'folder'
                ];
            } else {
                // If it's a file, determine its extension and add it to the result with the corresponding type
                $extension = pathinfo($itemPath, PATHINFO_EXTENSION);
                $result[] = [
                    'name' => $item,
                    'type' => $extension ? $extension . ' file' : 'file'
                ];
            }
        }
    
        // Custom comparison function to sort folders first, then files, both alphabetically by name
        usort($result, function($a, $b) {
            if ($a['type'] === 'folder' && $b['type'] === 'folder') {
                return strcmp($a['name'], $b['name']);
            } elseif ($a['type'] === 'folder') {
                return -1;
            } elseif ($b['type'] === 'folder') {
                return 1;
            } else {
                return strcmp($a['name'], $b['name']);
            }
        });
    
        // Return the sorted list
        return $result;

    }

}
