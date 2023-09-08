<?php

namespace Linhnh95\LaravelLumenGenerate;

use Illuminate\Container\Container;

class CommandHelpers
{
    public function existFilesToMove()
    {
        return [

        ];
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return base_path();
    }

    /**
     * @return string
     */
    public function getAppPath()
    {
        return app_path();
    }

    /**
     * @return mixed
     */
    public function getAppNamespace()
    {
        return Container::getInstance()->getNamespace();
    }

    /**
     * @param $baseDirectory
     *
     * @param array $folders
     */
    public function parseAndMakeDirRecursive($baseDirectory, array $folders)
    {
        foreach ($folders as $folderName => $folderDirectories) {
            $currentFolder = "$baseDirectory/$folderName";
            if (!is_dir($currentFolder)) {
                mkdir($currentFolder);
            }
            if (is_array($folderDirectories) && count($folderDirectories) > 0) {
                $this->parseAndMakeDirRecursive($currentFolder, $folderDirectories);
            }
        }
    }

    /**
     * @param $directory
     */
    public function removeFolderAndFilesRecursive($directory)
    {
        if (is_dir($directory)) {
            $files = scandir($directory);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    $this->removeFolderAndFilesRecursive("$directory/$file");
                }
            }
            rmdir($directory);
        } elseif (file_exists($directory)) {
            unlink($directory);
        }
    }

    /**
     * @param $from
     * @param $to
     */
    public function copyFolderAndFilesRecursive($from, $to)
    {
        if (is_dir($from)) {
            if (file_exists($to)) {
                $this->removeFolderAndFilesRecursive($to);
            }
            @mkdir($to);
            $files = scandir($from);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    $this->copyFolderAndFilesRecursive("$from/$file", "$to/$file");
                }
            }
        } elseif (file_exists($from)) {
            copy($from, $to);
        }
    }

    /**
     * @param $basePath
     * @param $from
     * @param $to
     */
    public function renameNamespaceRecursive($basePath, $from, $to)
    {
        if (is_dir($basePath)) {
            $files = scandir($basePath);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    $this->renameNamespaceRecursive("$basePath/$file", $from, $to);
                }
            }
        } elseif (file_exists($basePath)) {
            $tempContent = file_get_contents($basePath);
            $newContent = str_replace($from, $to, $tempContent);
            file_put_contents($basePath, $newContent);
        }
    }

    /**
     * Copy File From File Base
     *
     * @param $files
     * @param $appPath
     * @param $basePath
     */
    public function copyFileFromFileBaseRecursive($files, $appPath, $basePath)
    {
        foreach ($files as $file => $path) {
            if (is_array($path)) {
                $this->copyFileFromFileBaseRecursive($path, $appPath, $basePath);
            }
            $filePath = str_replace($basePath, '', $path);
            if(is_string($filePath)){
                if (!file_exists($appPath . $filePath)) {
                    $tempContent = file_get_contents($basePath. $filePath);
                    $fp = fopen($appPath . $filePath, "wb");
                    fwrite($fp, $tempContent);
                    fclose($fp);
                }
            }
        }
    }
}