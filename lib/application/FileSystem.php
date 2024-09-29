<?php

namespace Lib\Application;

use Exception;

class FileSystem
{
    private function __construct()
    {
        
    }

    public static function isDirectory(string $path)
    {
        return is_dir($path);
    }

    public static function isFile(string $path)
    {
        return is_file($path);
    }

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

    public static function exists(string $path)
    {
        return file_exists($path);
    }

    public static function name(string $path)
    {
        ['filename' => $filename] = pathinfo($path);
        return $filename;
    }

    public static function basename(string $path)
    {
        ['basename' => $basename] = pathinfo($path);
        return $basename;
    }

    public static function extension(string $path)
    {
        if (self::isDirectory($path)) {
            throw new Exception('A file "'. $path . '" is directory!');
        }
        
        ['extension' => $extension] = pathinfo($path);
        return $extension;
    }

    public static function size(string $path)
    {
        return filesize($path);
    }

    public static function copy(string $from, string $to)
    {   
        return copy($from, $to);
    }

    public static function move(string $from, string $to)
    {   
        return rename($from, $to);
    }

    public static function delete(string $path)
    {
        if (self::isDirectory($path)) {
            throw new Exception('A file "'. $path . '" is directory!');
        }

        return unlink($path);
    }

    public static function makeDirectory(string $path, int $permissions = 0755, bool $recursive = false)
    {
        return mkdir($path, $permissions, $recursive);
    }

    public static function ensureDirectory(string $path, int $permissions = 0755, bool $recursive = false)
    {
        if (self::exists($path)) {
            return true;
        }

        return mkdir($path, $permissions, $recursive);
    }

    public static function deleteDirectory(string $directory)
    {
        if (!self::isDirectory($directory)) {
            throw new Exception('Directory "' . $directory . '" not found!');
        }

        self::cleanDirectory($directory);
        return rmdir($directory);
    }

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