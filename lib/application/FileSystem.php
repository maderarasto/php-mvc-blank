<?php

namespace Lib\Application;

use Exception;

/**
 * The class represents an interface that provides functions to manipulate with files and directories
 */
class FileSystem
{
    private function __construct()
    {
        
    }

    /**
     * Checks if given path is a directory.
     * 
     * @param string $path 
     * @return bool
     */
    public static function isDirectory(string $path)
    {
        return is_dir($path);
    }

    /**
     * Checks if given path is a file.
     * 
     * @param string $path 
     * @return bool
     */
    public static function isFile(string $path)
    {
        return is_file($path);
    }

    /**
     * Get all content of file with given path.
     * 
     * @param string $filepath 
     * @return bool|string
     */
    public static function get(string $filepath)
    {
        return file_get_contents($filepath);
    }

    /**
     * Gets lines of file content with given path.
     * 
     * @param string $filepath 
     * @return array|bool
     */
    public static function lines(string $filepath)
    {
        return file($filepath);
    }

    /**
     * Puts content into the file. If not flags are given it will replace file content if it exists.
     * 
     * @param string $filepath 
     * @param string $content 
     * @param int $flags 
     * @return bool|int
     */
    public static function put(string $filepath, string $content, int $flags = 0)
    {
        return file_put_contents($filepath, $content);
    }

    /**
     * Gets list of directories in given directory path. If recursive is true then it will also contain all subdirectories.
     * 
     * @param string $directory 
     * @param bool $recursive 
     * @return array<bool|int|mixed|string>[]
     */
    public static function getDirectories(string $directory, bool $recursive = false)
    {
        $dirEntries = self::getEntries($directory);
        $dirDirs = [];

        if (empty($dirEntries)) {
            return $dirDirs;
        }

        foreach ($dirEntries as $dirEntry) {
            $entryPath = $directory . DIRECTORY_SEPARATOR . $dirEntry;

            if (!self::isDirectory($entryPath)) {
                continue;
            }

            $dirDirs[] = [
                'name' => $dirEntry,
                'path' => $entryPath,
                'size' => filesize($entryPath),
            ];

            if ($recursive) {
                $dirDirs = array_merge($dirDirs, self::getDirectories($entryPath, $recursive));
            }
        }

        return $dirDirs;
    }

    /**
     * Gets list of files in given directory path. If recursive is true then it will also contain all files in subdirectories.
     * 
     * @param string $directory 
     * @param bool $recursive 
     * @return array<bool|int|mixed|string>[]
     */
    public static function getFiles(string $directory, bool $recursive = false)
    {    
        $dirEntries = self::getEntries($directory);
        $dirFiles = [];

        if (empty($dirEntries)) {
            return $dirFiles;
        }

        foreach ($dirEntries as $dirEntry) {
            $entryPath = $directory . DIRECTORY_SEPARATOR . $dirEntry;

            if (self::isFile($entryPath)) {
                $dirFiles[] = [
                    'name' => $dirEntry,
                    'path' => $entryPath,
                    'size' => filesize($entryPath),
                ];
            }

            if ($recursive && self::isDirectory($entryPath)) {
                $dirFiles = array_merge($dirFiles, self::getFiles($entryPath, $recursive));
            }
        }

        return $dirFiles;
    }

    /**
     * Checks if entry exists for given path.
     * 
     * @param string $path 
     * @return bool
     */
    public static function exists(string $path)
    {
        return file_exists($path);
    }

    /**
     * Gets a name of entry for given path. If it's file it will return name without extension.
     * 
     * @param string $path 
     * @return mixed
     */
    public static function name(string $path)
    {
        ['filename' => $filename] = pathinfo($path);
        return $filename;
    }

    /**
     * Gets a base name of entry for given path. If it's file it will return name with extension.
     * 
     * @param string $path 
     * @return mixed
     */
    public static function basename(string $path)
    {
        ['basename' => $basename] = pathinfo($path);
        return $basename;
    }

    /**
     * Gets an extension of file for given path.
     * 
     * @param string $path 
     * @throws Exception 
     * @return mixed
     */
    public static function extension(string $path)
    {
        if (self::isDirectory($path)) {
            throw new Exception('A file "'. $path . '" is directory!');
        }
        
        ['extension' => $extension] = pathinfo($path);
        return $extension;
    }

    /**
     * Gets size in bytes of entry for given path.
     * 
     * @param string $path 
     * @return bool|int
     */
    public static function size(string $path)
    {
        return filesize($path);
    }

    /**
     * Copies entry from one location to another.
     * 
     * @param string $from source path
     * @param string $to destination path
     * @return bool
     */
    public static function copy(string $from, string $to)
    {   
        return copy($from, $to);
    }

    /**
     * Moves entry from one location to another.
     * 
     * @param string $from source path
     * @param string $to destination path
     * @return bool
     */
    public static function move(string $from, string $to)
    {   
        return rename($from, $to);
    }

    /**
     * Deletes a file for given path.
     * 
     * @param string $path 
     * @throws Exception throws exception if entry is directory.
     * @return bool
     */
    public static function delete(string $path)
    {
        if (self::isDirectory($path)) {
            throw new Exception('A file "'. $path . '" is directory!');
        }

        return unlink($path);
    }

    /**
     * Creates a directory with given path and permissions.
     * 
     * @param string $path 
     * @param int $permissions 
     * @param bool $recursive 
     * @return bool
     */
    public static function makeDirectory(string $path, int $permissions = 0755, bool $recursive = false)
    {
        return mkdir($path, $permissions, $recursive);
    }

    /**
     * Ensures that directory with given path exists. If directory doesn't exist it will create it otherwise return true.
     * 
     * @param string $path 
     * @param int $permissions 
     * @param bool $recursive 
     * @return bool
     */
    public static function ensureDirectory(string $path, int $permissions = 0755, bool $recursive = false)
    {
        if (self::exists($path)) {
            return true;
        }

        return mkdir($path, $permissions, $recursive);
    }

    /**
     * Removes directory with all its content.
     * 
     * @param string $directory 
     * @throws Exception 
     * @return bool
     */
    public static function deleteDirectory(string $directory)
    {
        if (!self::isDirectory($directory)) {
            throw new Exception('Directory "' . $directory . '" not found!');
        }

        self::cleanDirectory($directory);
        return rmdir($directory);
    }

    /**
     * Cleans directory with all its content.
     * 
     * @param string $directory 
     * @return void
     */
    public static function cleanDirectory(string $directory)
    {
        $dirEntries = self::getEntries($directory);

        if (empty($dirEntries)) {
            return;
        }

        foreach ($dirEntries as $entryName) {
            $entryPath = $directory . DIRECTORY_SEPARATOR . $entryName;

            if (self::isDirectory($entryPath)) {
                self::cleanDirectory($entryPath);
                rmdir($entryPath);
            } else {
                self::delete($entryPath);
            }
        }
    }

    protected static function getEntries(string $directory)
    {
        if (!self::isDirectory($directory)) {
            throw new Exception('Directory "' . $directory . '" not found!');
        }

        $dirEntries = scandir($directory);

        // Return without dot entries
        return array_slice($dirEntries, 2);
    }
}