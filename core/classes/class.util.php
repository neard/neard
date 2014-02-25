<?php

class Util
{
    const LOG_ERROR = 'ERROR';
    const LOG_WARNING = 'WARNING';
    const LOG_INFO = 'INFO';
    const LOG_DEBUG = 'DEBUG';
    
    public static function cleanArgv($key, $type = 'string')
    {
        if (isset($_SERVER['argv'])) {
            if ($type == 'string') {
                return (isset($_SERVER['argv'][$key]) && !empty($_SERVER['argv'][$key])) ? trim($_SERVER['argv'][$key]) : '';
            } elseif ($type == 'numeric') {
                return (isset($_SERVER['argv'][$key]) && is_numeric($_SERVER['argv'][$key])) ? intval($_SERVER['argv'][$key]) : '';
            } elseif ($type == 'boolean') {
                return (isset($_SERVER['argv'][$key])) ? true : false;
            } elseif ($type == 'array') {
                return (isset($_SERVER['argv'][$key]) && is_array($_SERVER['argv'][$key])) ? $_SERVER['argv'][$key] : array();
            }
        }
        
        return false;
    }
    
    public static function cleanGetVar($key, $type = 'string')
    {
        if (is_string($key)) {
            if ($type == 'string') {
                return (isset($_GET[$key]) && !empty($_GET[$key])) ? stripslashes($_GET[$key]) : '';
            } elseif ($type == 'numeric') {
                return (isset($_GET[$key]) && is_numeric($_GET[$key])) ? intval($_GET[$key]) : '';
            } elseif ($type == 'boolean') {
                return (isset($_GET[$key])) ? true : false;
            } elseif ($type == 'array') {
                return (isset($_GET[$key]) && is_array($_GET[$key])) ? $_GET[$key] : array();
            }
        }
    
        return false;
    }
    
    public static function contains($string, $search)
    {
        if (!empty($string) && !empty($search)) {
            $result = stripos($string, $search);
            if ($result !== false) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    public static function startWith($string, $search)
    {
        $length = strlen($search);
        return (substr($string, 0, $length) === $search);
    }
    
    public static function endWith($string, $search)
    {
        $length = strlen($search);
        $start  = $length * -1;
        return (substr($string, $start) === $search);
    }
    
    public static function random($length = 32, $withNumeric = true)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($withNumeric){
            $characters .= '0123456789';
        }
        
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $randomString;
    }
    
    public static function clearFolder($path, $exclude = array())
    {
        $result = array();
        $result['return'] = true;
        $result['nb_files'] = 0;
    
        $handle = @opendir($path);
        if (!$handle) {
            return;
        }
        
        while ($file = readdir($handle)) {
            if ($file == '.' || $file == '..' || in_array($file, $exclude)) {
                continue;
            }
            if (is_dir($path . '/' . $file)) {
                $r = self::clearFolder($path . '/' . $file);
                if (!$r) {
                    $result['return'] = false;
                    return $result;
                }
            } else {
                $r = self::unlinkAlt($path . '/' . $file);
                if ($r) {
                    $result['nb_files']++;
                } else {
                    $result['return'] = false;
                    return $result;
                }
            }
        }
        
        closedir($handle);
    
        return $result;
    }
    
    public static function deleteFolder($path) {
        if (is_dir($path)) {
            if (substr($path, strlen($path) - 1, 1) != '/') {
                $path .= '/';
            }
            $files = glob($path . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    self::deleteFolder($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($path);
        }
    }
    
    public static function findFile($startPath, $findFile)
    {
        $result = false;
        
        $handle = @opendir($startPath);
        if (!$handle) {
            return false;
        }
        
        while ($file = readdir($handle)) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (is_dir($startPath . '/' . $file)) {
                $result = self::findFile($startPath . '/' . $file, $findFile);
                if ($result !== false) {
                    break;
                }
            } elseif ($file == $findFile) {
                $result = self::formatUnixPath($startPath . '/' . $file);
                break;
            }
        }
        
        closedir($handle);
        return $result;
    }
    
    public static function findFiles($startPath, $findFiles = array(''))
    {
        $result = array();
    
        $handle = @opendir($startPath);
        if (!$handle) {
            return $result;
        }
    
        while ($file = readdir($handle)) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (is_dir($startPath . '/' . $file)) {
                $tmpResults = self::findFiles($startPath . '/' . $file, $findFiles);
                foreach($tmpResults as $tmpResult) {
                    $result[] = $tmpResult;
                }
            } elseif (is_file($startPath . '/' . $file)) {
                foreach ($findFiles as $findFile) {
                    if (self::endWith($file, $findFile) || empty($findFile)) {
                        $result[] = self::formatUnixPath($startPath . '/' . $file);
                    }
                }
            }
        }
    
        closedir($handle);
        return $result;
    }
    
    public static function isValidIp($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
            || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }
    
    public static function isValidPort($port)
    {
        return is_numeric($port) && ($port > 0 || $port <= 65535);
    }
    
    public static function getHosts()
    {
        $result = array();
        
        if (file_exists(HOSTS_FILE)) {
            $hostsFile = file(HOSTS_FILE);
            foreach($hostsFile as $key => $row) {
                $newRow = trim(preg_replace('/\s+/', ' ', $row));
                $expRow = explode(' ', $newRow);
                if (trim($expRow[0]) == '#' && isset($expRow[1]) && self::isValidIp($expRow[1]) && isset($expRow[2])) {
                    $result[] = array(
                        'enabled' => false,
                        'ip'      => $expRow[1],
                        'domain'  => $expRow[2],
                    );
                } elseif (isset($expRow[0]) && isset($expRow[1]) && self::isValidIp($expRow[0])) {
                    $result[] = array(
                        'enabled' => true,
                        'ip'      => $expRow[0],
                        'domain'  => $expRow[1],
                    );
                }
            }
        }
        
        return $result;
    }
    
    public static function refactorHostsFile()
    {
        $header = '# Copyright (c) 1993-2006 Microsoft Corp.' . PHP_EOL;
        $header .= '#' . PHP_EOL;
        $header .= '# This is a sample HOSTS file used by Microsoft TCP/IP for Windows.' . PHP_EOL;
        $header .= '#' . PHP_EOL;
        $header .= '# This file contains the mappings of IP addresses to host names. Each' . PHP_EOL;
        $header .= '# entry should be kept on an individual line. The IP address should' . PHP_EOL;
        $header .= '# be placed in the first column followed by the corresponding host name.' . PHP_EOL;
        $header .= '# The IP address and the host name should be separated by at least one' . PHP_EOL;
        $header .= '# space.' . PHP_EOL . '#' . PHP_EOL;
        $header .= '# Additionally, comments (such as these) may be inserted on individual' . PHP_EOL;
        $header .= '# lines or following the machine name denoted by a \'#\' symbol.' . PHP_EOL . PHP_EOL;
        
        $hosts = self::getHosts();
        if (!empty($hosts)) {
            $enabledHosts = '## Enabled' . PHP_EOL;
            $disabledHosts = '## Disabled' . PHP_EOL;
            foreach($hosts as $host) {
                if ($host['enabled']) {
                    $enabledHosts .= str_pad($host['ip'], 20) . $host['domain'] . PHP_EOL;
                } else {
                    $disabledHosts .= '# ' . str_pad($host['ip'], 18) . $host['domain'] . PHP_EOL;
                }
            }
            file_put_contents(HOSTS_FILE, $header . $enabledHosts . PHP_EOL . $disabledHosts);
        }
    }
    
    public static function replaceDefine($path, $var, $value)
    {
        self::replaceInFile($path, array(
            '/^define\((.*?)' . $var . '(.*?),/' => 'define(\'' . $var . '\', ' . (is_int($value) ? $value : '\'' . $value . '\'') . ');'
        ));
    }
    
    public static function replaceInFile($path, $replaceList)
    {
        if (file_exists($path)) {
            $lines = file($path);
            $fp = fopen($path, 'w');
            foreach ($lines as $nb => $line) {
                $replaceDone = false;
                foreach ($replaceList as $regex => $replace) {
                    if (preg_match($regex, $line, $matches)) {
                        $countParams = preg_match_all('/{{(\d+)}}/', $replace, $paramsMatches);
                        if ($countParams > 0 && $countParams <= count($matches)) {
                            foreach ($paramsMatches[1] as $paramsMatch) {
                                $replace = str_replace('{{' . $paramsMatch . '}}', $matches[$paramsMatch], $replace);
                            }
                        }
                        self::logDebug('Replace in file ' . $path . ' :');
                        self::logDebug('## line_num: ' . trim($nb));
                        self::logDebug('## old: ' . trim($line));
                        self::logDebug('## new: ' . trim($replace));
                        fwrite($fp, $replace . PHP_EOL);
                        
                        $replaceDone = true;
                        break;
                    }
                }
                if (!$replaceDone) {
                    fwrite($fp, $line);
                }
            }
            fclose($fp);
        }
    }
    
    public static function fsockopenAlt($hostname, $port, $timeout = null)
    {
        global $neardBs;
        
        $neardBs->removeErrorHandling();
        $timeout = $timeout == null ? ini_get("default_socket_timeout") : $timeout;
        $result = @fsockopen('127.0.0.1', intval($port), $errno, $errstr, 1);
        $neardBs->initErrorHandling();
        
        return $result;
    }
    
    public static function unlinkAlt($path)
    {
        global $neardBs;
    
        $neardBs->removeErrorHandling();
        $result = @unlink($path);
        $neardBs->initErrorHandling();
    
        return $result;
    }
    
    public static function isPortInUse($port)
    {
        return self::fsockopenAlt('127.0.0.1', intval($port), 1) !== false;
    }
    
    public static function isOnline()
    {
        global $neardConfig;
        return $neardConfig->getStatus() == Config::STATUS_ONLINE;
    }
    
    public static function getVersionList($path)
    {
        $result = array();
        
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                $filePath = $path . '/' . $file;
                if ($file != "." && $file != ".." && is_dir($filePath)) {
                    $result[] = str_replace(basename($path), '', $file);
                }
            }
            closedir($handle);
        }
        
        return $result;
    }
    
    public static function getAliasContent($name, $dest)
    {
        $dest = self::formatUnixPath($dest);
        return 'Alias /' . $name . ' "' . $dest . '"' . PHP_EOL . PHP_EOL .
        '# to give access to gitlist from outside' . PHP_EOL .
        '# replace the lines' . PHP_EOL .
        '#' . PHP_EOL .
        '#    Order Deny,Allow' . PHP_EOL .
        '#    Deny from all' . PHP_EOL .
        '#    Allow from ::1 127.0.0.1 localhost' . PHP_EOL .
        '#' . PHP_EOL . '# by' . PHP_EOL . '#' . PHP_EOL .
        '#    Order Allow,Deny' . PHP_EOL .
        '#    Allow from all' . PHP_EOL . '#' . PHP_EOL .
        '<Directory "' . $dest . '">' . PHP_EOL .
        '    Options Indexes FollowSymLinks MultiViews' . PHP_EOL .
        '    AllowOverride all' . PHP_EOL .
        '    Order Deny,Allow' . PHP_EOL .
        '    Deny from all' . PHP_EOL .
        '    Allow from ::1 127.0.0.1 localhost' . PHP_EOL .
        '</Directory>' . PHP_EOL;
    }
    
    public static function getMicrotime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
    
    public static function getSilentVbs()
    {
        global $neardCore;
        
        $scriptName = $neardCore->getTmpPath() . '/' . self::random() . '.vbs';
        $scriptContent = 'Set objShell = WScript.CreateObject("WScript.Shell")' . PHP_EOL;
        $scriptContent .= 'Set objFso = CreateObject("Scripting.FileSystemObject")' . PHP_EOL;
        $scriptContent .= 'Set args = WScript.Arguments' . PHP_EOL;
        $scriptContent .= 'num = args.Count' . PHP_EOL;
        $scriptContent .= 'sargs = ""' . PHP_EOL . PHP_EOL;
        $scriptContent .= 'If num = 0 Then' . PHP_EOL;
        $scriptContent .= '    WScript.Quit 1' . PHP_EOL;
        $scriptContent .= 'End If' . PHP_EOL . PHP_EOL;
        $scriptContent .= 'If num > 1 Then' . PHP_EOL;
        $scriptContent .= '    sargs = " "' . PHP_EOL;
        $scriptContent .= '    For k = 1 To num - 1' . PHP_EOL;
        $scriptContent .= '        anArg = args.Item(k)' . PHP_EOL;
        $scriptContent .= '        sargs = sargs & anArg & " "' . PHP_EOL;
        $scriptContent .= '    Next' . PHP_EOL;
        $scriptContent .= 'End If' . PHP_EOL . PHP_EOL;
        $scriptContent .= 'Return = objShell.Run("""" & args(0) & """" & sargs, 0, True)' . PHP_EOL;
        $scriptContent .= 'objFso.DeleteFile("' . self::formatWindowsPath($scriptName) . '")' . PHP_EOL;
        
        file_put_contents($scriptName, $scriptContent);
        return $scriptName;
    }
    
    public static function exitApp($restart = false)
    {
        global $neardBs, $neardCore, $neardWinbinder;
    
        $scriptName = $neardCore->getTmpPath() . '/' . self::random() . '.bat';
        $scriptContent = '@ECHO OFF' . PHP_EOL . PHP_EOL;
        $scriptContent .= 'SETLOCAL EnableDelayedExpansion' . PHP_EOL;
        $scriptContent .= 'ping 1.1.1.1 -n 1 -w 3000 > nul' . PHP_EOL;
        $scriptContent .= '"' . $neardBs->getExeFilePath() . '" -quit -id={neard}' . PHP_EOL;
        if ($restart) {
            self::logInfo('Restart App');
            $scriptContent .= '"' . $neardCore->getPhpCliSilentExe() . '" "' . Core::BOOTSTRAP_FILE . '" "' . Action::RESTART . '"' . PHP_EOL;
        } else {
            self::logInfo('Exit App');
        }
        $scriptContent .= 'ENDLOCAL' . PHP_EOL;
        file_put_contents($scriptName, $scriptContent);
        
        return $neardWinbinder->exec($scriptName, null, true);
    }
    
    public static function getAppBinsRegKey($fromRegistry = true)
    {
        global $neardRegistry;
        
        if ($fromRegistry) {
            $value = $neardRegistry->getValue(
                Registry::HKEY_LOCAL_MACHINE,
                Registry::APP_BINS_REG_SUBKEY,
                Registry::APP_BINS_REG_ENTRY
            );
            self::logDebug('App reg key from registry: ' . $value);
        } else {
            global $neardBins, $neardTools;
            $value = $neardBins->getApache()->getCurrentPath() . '/bin;';
            $value .= $neardBins->getPhp()->getCurrentPath() . ';';
            $value .= $neardBins->getPhp()->getPearPath() . ';';
            $value .= $neardTools->getImagick()->getCurrentPath() . ';';
            $value .= $neardTools->getSvn()->getCurrentPath() . ';';
            $value .= $neardTools->getGit()->getCurrentPath() . '/bin;';
            $value = self::formatWindowsPath($value);
            self::logDebug('Generated app reg key: ' . $value);
        }
        
        return $value;
    }
    
    public static function setAppBinsRegKey($value)
    {
        global $neardRegistry;
        return $neardRegistry->setStringValue(
            Registry::HKEY_LOCAL_MACHINE,
            Registry::APP_BINS_REG_SUBKEY,
            Registry::APP_BINS_REG_ENTRY,
            $value
        );
    }
    
    public static function getAppPathRegKey()
    {
        global $neardRegistry;
        return $neardRegistry->getValue(
            Registry::HKEY_LOCAL_MACHINE,
            Registry::APP_PATH_REG_SUBKEY,
            Registry::APP_PATH_REG_ENTRY
        );
    }
    
    public static function setAppPathRegKey($value)
    {
        global $neardRegistry;
        return $neardRegistry->setExpandStringValue(
            Registry::HKEY_LOCAL_MACHINE,
            Registry::APP_PATH_REG_SUBKEY,
            Registry::APP_PATH_REG_ENTRY,
            $value
        );
    }
    
    public static function getSysPathRegKey()
    {
        global $neardRegistry;
        return $neardRegistry->getValue(
            Registry::HKEY_LOCAL_MACHINE,
            Registry::SYSPATH_REG_SUBKEY,
            Registry::SYSPATH_REG_ENTRY
        );
    }
    
    public static function setSysPathRegKey($value)
    {
        global $neardRegistry;
        return $neardRegistry->setExpandStringValue(
            Registry::HKEY_LOCAL_MACHINE,
            Registry::SYSPATH_REG_SUBKEY,
            Registry::SYSPATH_REG_ENTRY,
            $value
        );
    }
    
    private static function log($data, $type, $file = null)
    {
        global $neardBs, $neardConfig;
        $file = $file == null ? $neardBs->getLogFilePath() : $file;
        
        $verbose = array();
        $verbose[0] = $type == self::LOG_ERROR || $type == self::LOG_WARNING;
        $verbose[1] = $verbose[0] || $type == self::LOG_INFO;
        $verbose[2] = $verbose[1] || $type == self::LOG_DEBUG;
        
        $writeLog = false;
        if ($neardConfig->getAppLogsVerbose() == 0 && $verbose[0]) {
            $writeLog = true;
        } elseif ($neardConfig->getAppLogsVerbose() == 1 && $verbose[1]) {
            $writeLog = true;
        } elseif ($neardConfig->getAppLogsVerbose() == 2 && $verbose[2]) {
            $writeLog = true;
        }
        
        if ($writeLog) {
            file_put_contents(
                $file,
                '[' . date('Y-m-d H:i:s', time()) . '] # ' . APP_TITLE . ' ' . $neardConfig->getAppVersion() . ' # ' . $type . ': ' . $data . PHP_EOL,
                FILE_APPEND
            );
        }
    }
    
    public static function logDebug($data, $file = null)
    {
        self::log($data, self::LOG_DEBUG, $file);
    }
    
    public static function logInfo($data, $file = null)
    {
        self::log($data, self::LOG_INFO, $file);
    }
    
    public static function logWarning($data, $file = null)
    {
        self::log($data, self::LOG_WARNING, $file);
    }
    
    public static function logError($data, $file = null)
    {
        self::log($data, self::LOG_ERROR, $file);
    }
    
    public static function logInitClass($classInstance)
    {
        self::logInfo('Init ' . get_class($classInstance));
    }
    
    public static function getPowerShellPath()
    {
        return self::findFile('C:\Windows\System32\WindowsPowerShell', 'powershell.exe');
    }
    
    public static function getPearVersion()
    {
        global $neardCore, $neardBins;
        
        $resultFile = self::formatWindowsPath($neardCore->getTmpPath() . '/' . self::random() . '.tmp');
        $scriptContent = '@ECHO OFF' . PHP_EOL . PHP_EOL;
        $scriptContent .= 'cmd /c "' . $neardBins->getPhp()->getPearExe() . '" -V > "' . $resultFile . '"' . PHP_EOL;
        
        $result = self::execBatch($resultFile, $scriptContent, 10);
        if (isset($result[0])) {
            $expResult = explode(' ', $result[0]);
            if (count($expResult) == 3) {
                $result = trim($expResult[2]);
            }
        }
        
        return $result;
    }
    
    public static function getSvnVersion()
    {
        global $neardCore, $neardTools;
        
        $resultFile = self::formatWindowsPath($neardCore->getTmpPath() . '/' . self::random() . '.tmp');
        $scriptContent = '@ECHO OFF' . PHP_EOL . PHP_EOL;
        $scriptContent .= 'cmd /c "' . $neardTools->getSvn()->getExe() . '" --version > "' . $resultFile . '"' . PHP_EOL;
        
        return self::execBatch($resultFile, $scriptContent, 10);
    }
    
    public static function findRepos($startPath, $findFolder, $checkFileIns = null)
    {
        $result = array();
        
        $handle = @opendir($startPath);
        if (!$handle) {
            return $result;
        }
        
        while ($folder = readdir($handle)) {
            if ($folder == '.' || $folder == '..') {
                continue;
            }
            if (is_dir($startPath . '/' . $folder)) {
                if ($folder == $findFolder && (empty($checkFileIns) || file_exists($startPath . '/' . $folder . '/' . $checkFileIns))) {
                    $result[] = $startPath;
                } else {
                    $resultSub = self::findRepos($startPath . '/' . $folder, $findFolder, $checkFileIns);
                    if (!empty($resultSub)) {
                        foreach ($resultSub as $aResult) {
                            array_push($result, $aResult);
                        }
                    }
                }
            }
        }
        
        closedir($handle);
        return $result;
    }
    
    public static function countFilesFolders($path)
    {
        global $neardCore;
        
        $resultFile = self::formatWindowsPath($neardCore->getTmpPath() . '/' . self::random() . '.tmp');
        $scriptContent = 'Dim objFso, objResultFile, objCheckFile' . PHP_EOL . PHP_EOL;
        $scriptContent .= 'Set objFso = CreateObject("scripting.filesystemobject")' . PHP_EOL;
        $scriptContent .= 'Set objResultFile = objFso.CreateTextFile("' . $resultFile . '", True)' . PHP_EOL;
        $scriptContent .= 'count = 0' . PHP_EOL;
        $scriptContent .= 'CountFiles("' . $path . '")' . PHP_EOL . PHP_EOL;
        $scriptContent .= 'Function CountFiles(ByVal path)' . PHP_EOL;
        $scriptContent .= '    Dim parentFld, subFld' . PHP_EOL;
        $scriptContent .= '    Set parentFld = objFso.GetFolder(path)' . PHP_EOL . PHP_EOL;
        $scriptContent .= '    count = count + parentFld.Files.Count + + parentFld.SubFolders.Count' . PHP_EOL;
        $scriptContent .= '    For Each subFld In parentFld.SubFolders' . PHP_EOL;
        $scriptContent .= '        count = count + CountFiles(subFld.Path)' . PHP_EOL;
        $scriptContent .= '    Next' . PHP_EOL . PHP_EOL;
        $scriptContent .= 'End Function' . PHP_EOL . PHP_EOL;
        $scriptContent .= 'objResultFile.Write count' . PHP_EOL;
        $scriptContent .= 'objResultFile.Close' . PHP_EOL;
    
        $result = self::execVbs($resultFile, $scriptContent, 30);
        return isset($result[0]) && is_numeric($result[0]) ? intval($result[0]) : false;
    }
    
    public static function findReposVbs($startPath, $findFolder, $checkFileIns)
    {
        global $neardCore;
        
        $resultFile = self::formatWindowsPath($neardCore->getTmpPath() . '/' . self::random() . '.tmp');
        $scriptContent = 'Dim objFso, objFile' . PHP_EOL . PHP_EOL;
        $scriptContent .= 'Set objFso = CreateObject("scripting.filesystemobject")' . PHP_EOL;
        $scriptContent .= 'Set objFile = objFso.CreateTextFile("' . $resultFile . '", True)' . PHP_EOL;
        $scriptContent .= 'findFolder = "' . $findFolder . '"' . PHP_EOL;
        $scriptContent .= 'checkFileIns = "' . $checkFileIns . '"' . PHP_EOL . PHP_EOL;
        $scriptContent .= 'FindRepos("' . $startPath . '")' . PHP_EOL . PHP_EOL;
        $scriptContent .= 'Function FindRepos(ByVal path)' . PHP_EOL;
        $scriptContent .= '    Dim parentFld, subFld' . PHP_EOL;
        $scriptContent .= '    Set parentFld = objFso.GetFolder(path)' . PHP_EOL . PHP_EOL;
        $scriptContent .= '    For Each subFld In parentFld.SubFolders' . PHP_EOL;
        $scriptContent .= '        If subFld.Name = findFolder And objFso.FileExists(subFld.Path & "\" & checkFileIns) Then' . PHP_EOL;
        $scriptContent .= '            objFile.Write parentFld.Path & vbCrLf' . PHP_EOL;
        $scriptContent .= '        End If' . PHP_EOL;
        $scriptContent .= '        FindRepos(subFld.Path)' . PHP_EOL;
        $scriptContent .= '    Next' . PHP_EOL;
        $scriptContent .= 'End Function' . PHP_EOL . PHP_EOL;
        $scriptContent .= 'objFile.Close' . PHP_EOL;
        
        return self::execVbs($resultFile, $scriptContent, 30);
    }
    
    public static function getDefaultBrowser()
    {
        global $neardCore;
    
        $resultFile = self::formatWindowsPath($neardCore->getTmpPath() . '/' . self::random() . '.tmp');
        $scriptContent = 'On Error Resume Next' . PHP_EOL;
        $scriptContent .= 'Err.Clear' . PHP_EOL . PHP_EOL;
    
        $scriptContent .= 'Dim objShell, objFso, objFile' . PHP_EOL . PHP_EOL;
    
        $scriptContent .= 'Set objShell = WScript.CreateObject("WScript.Shell")' . PHP_EOL;
        $scriptContent .= 'Set objFso = CreateObject("scripting.filesystemobject")' . PHP_EOL;
        $scriptContent .= 'Set objFile = objFso.CreateTextFile("' . $resultFile . '", True)' . PHP_EOL . PHP_EOL;
        
        $scriptContent .= 'objFile.Write objShell.RegRead("HKLM\SOFTWARE\Classes\http\shell\open\command\")' . PHP_EOL;
        $scriptContent .= 'objFile.Close' . PHP_EOL;
    
        $result = self::execVbs($resultFile, $scriptContent, 2);
        if ($result !== false && !empty($result)) {
            if (preg_match('/"([^"]+)"/', $result[0], $matches)) {
                return $matches[1];
            } else {
                return str_replace('"', '', $result[0]);
            }
        } else {
            return false;
        }
    }
    
    public static function getInstalledBrowsers()
    {
        global $neardCore;
    
        $resultFile = self::formatWindowsPath($neardCore->getTmpPath() . '/' . self::random() . '.tmp');
        $scriptContent = 'On Error Resume Next' . PHP_EOL;
        $scriptContent .= 'Err.Clear' . PHP_EOL . PHP_EOL;
        
        $scriptContent .= 'Dim objShell, objRegistry, objFso, objFile' . PHP_EOL . PHP_EOL;
        
        $scriptContent .= 'Set objShell = WScript.CreateObject("WScript.Shell")' . PHP_EOL;
        $scriptContent .= 'Set objRegistry = GetObject("winmgmts://./root/default:StdRegProv")' . PHP_EOL;
        $scriptContent .= 'Set objFso = CreateObject("scripting.filesystemobject")' . PHP_EOL;
        $scriptContent .= 'Set objFile = objFso.CreateTextFile("' . $resultFile . '", True)' . PHP_EOL . PHP_EOL;
        
        $scriptContent .= 'mainKey = "SOFTWARE\WOW6432Node\Clients\StartMenuInternet"' . PHP_EOL;
        $scriptContent .= 'checkKey = objShell.RegRead("HKLM\" & mainKey & "\")' . PHP_EOL;
        $scriptContent .= 'If Err.Number <> 0 Then' . PHP_EOL;
        $scriptContent .= '    Err.Clear' . PHP_EOL;
        $scriptContent .= '    mainKey = "SOFTWARE\Clients\StartMenuInternet"' . PHP_EOL;
        $scriptContent .= '    checkKey = objShell.RegRead("HKLM\" & mainKey & "\")' . PHP_EOL;
        $scriptContent .= '    If Err.Number <> 0 Then' . PHP_EOL;
        $scriptContent .= '        mainKey = ""' . PHP_EOL;
        $scriptContent .= '    End If' . PHP_EOL;
        $scriptContent .= 'End If' . PHP_EOL . PHP_EOL;
        
        $scriptContent .= 'Err.Clear' . PHP_EOL;
        $scriptContent .= 'If mainKey <> "" Then' . PHP_EOL;
        $scriptContent .= '    objRegistry.EnumKey &H80000002, mainKey, arrSubKeys' . PHP_EOL;
        $scriptContent .= '    For Each subKey In arrSubKeys' . PHP_EOL;
        $scriptContent .= '        objFile.Write objShell.RegRead("HKLM\SOFTWARE\Clients\StartMenuInternet\" & subKey & "\shell\open\command\") & vbCrLf' . PHP_EOL;
        $scriptContent .= '    Next' . PHP_EOL;
        $scriptContent .= 'End If' . PHP_EOL;
        $scriptContent .= 'objFile.Close' . PHP_EOL;
        
        $result = self::execVbs($resultFile, $scriptContent, 2);
        if ($result !== false && !empty($result)) {
            $rebuildResult = array();
            foreach ($result as $browser) {
                $rebuildResult[] = str_replace('"', '', $browser);
            }
            $result = $rebuildResult;
        }
    
        return $result;
    }
    
    public static function execBatch($resultFile , $content, $timeout, $silent = true)
    {
        global $neardCore, $neardWinbinder;
    
        $result = false;
        $endProcessStr = 'FINISHED!';
    
        $scriptPath = self::formatWindowsPath($neardCore->getTmpPath() . '/' . self::random()) . '.bat';
        $checkFile = self::formatWindowsPath($neardCore->getTmpPath() . '/' . self::random()) . '.tmp';
    
        // Build script footer
        $footer = PHP_EOL . PHP_EOL . 'REM START Footer Batch PHP class' . PHP_EOL .
            'echo ' . $endProcessStr . ' > "' . $checkFile . '"' . PHP_EOL .
            'REM END Footer Batch PHP class';
    
        // Process
        file_put_contents($scriptPath, $content . $footer);
        $neardWinbinder->exec($scriptPath, null, $silent);
    
        $maxtime = time() + $timeout;
        $noTimeout = $timeout == 0;
        while ($result === false || empty($result)) {
            if (file_exists($checkFile)) {
                $check = file($checkFile);
                if (!empty($check) && trim($check[0]) == $endProcessStr) {
                    $result = file($resultFile);
                }
            }
            if ($maxtime < time() && !$noTimeout) {
                break;
            }
        }
    
        self::unlinkAlt($checkFile);
        self::unlinkAlt($resultFile);
        self::unlinkAlt($scriptPath);
    
        if ($result !== false && !empty($result)) {
            $rebuildResult = array();
            foreach ($result as $repo) {
                $rebuildResult[] = trim($repo);
            }
            $result = $rebuildResult;
        }
    
        return $result;
    }
    
    public static function execVbs($resultFile , $content, $timeout)
    {
        global $neardCore, $neardWinbinder;
    
        $result = false;
        $endProcessStr = 'FINISHED!';
        
        $scriptPath = self::formatWindowsPath($neardCore->getTmpPath() . '/' . self::random()) . '.vbs';
        $checkFile = self::formatWindowsPath($neardCore->getTmpPath() . '/' . self::random()) . '.tmp';
    
        $randomVarName = self::random(15, false);
        $randomObjFso = self::random(15, false);
        $randomObjFile = self::random(15, false);
    
        // Build script header
        $header = '\' START Header Vbs PHP class' . PHP_EOL .
        'Dim ' . $randomVarName . ', ' . $randomObjFso . ', ' . $randomObjFile . PHP_EOL .
        'Set ' . $randomObjFso . ' = CreateObject("scripting.filesystemobject")' . PHP_EOL .
        'Set ' . $randomObjFile . ' = ' . $randomObjFso . '.CreateTextFile("' . $checkFile . '", True)' . PHP_EOL .
        '\' END Header Vbs PHP class' . PHP_EOL . PHP_EOL;
    
        // Build script footer
        $footer = PHP_EOL . PHP_EOL . '\' START Footer Vbs PHP class' . PHP_EOL .
        $randomObjFile . '.Write "' . $endProcessStr . '"' . PHP_EOL .
        $randomObjFile . '.Close' . PHP_EOL .
        '\' END Footer Vbs PHP class';
    
        // Process
        file_put_contents($scriptPath, $header . $content . $footer);
        $neardWinbinder->exec('wscript.exe', '"' . $scriptPath . '"');
    
        $maxtime = time() + $timeout;
        while ($result === false || empty($result)) {
            if (file_exists($checkFile)) {
                $check = file($checkFile);
                if (!empty($check) && $check[0] == $endProcessStr) {
                    $result = file($resultFile);
                }
            }
            if ($maxtime < time()) {
                break;
            }
        }
    
        self::unlinkAlt($checkFile);
        self::unlinkAlt($resultFile);
        self::unlinkAlt($scriptPath);
    
        if ($result !== false && !empty($result)) {
            $rebuildResult = array();
            foreach ($result as $repo) {
                $rebuildResult[] = trim($repo);
            }
            $result = $rebuildResult;
        }
    
        return $result;
    }
    
    public static function formatWindowsPath($path)
    {
        return str_replace('/', '\\', $path);
    }
    
    public static function formatUnixPath($path)
    {
        return str_replace('\\', '/', $path);
    }
    
    public static function getAppPaths()
    {
        global $neardCore;
        $result = array();
        
        if (file_exists($neardCore->getAppPaths())) {
            $paths = file($neardCore->getAppPaths());
            foreach ($paths as $path) {
                $result[] = trim($path);
            }
        }
        
        return $result;
    }
    
    public static function imgToBase64($path)
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    
public static function UTF_to_Unicode($input, $array=False) {

    $value = '';
    $val   = array();
 
    for($i=0; $i< strlen( $input ); $i++){
 
        $ints = ord ( $input[$i] );
     
        $z     = ord ( $input[$i] );
        $y     = ord ( $input[$i+1] ) - 128;
        $x     = ord ( $input[$i+2] ) - 128;
        $w     = ord ( $input[$i+3] ) - 128;
        $v     = ord ( $input[$i+4] ) - 128;
        $u     = ord ( $input[$i+5] ) - 128;
        
        /* Encoding 1 bit
        @@@@@@@@@@@@@@@@@@@@@@@@@@*/
        if( $ints >= 0 && $ints <= 127 ){
            // 1 bit
            $value[] = $z;
            $value1[]= dechex($z);
            //$val[]  = $value; 
        }
        
        /* Encoding 2 bit
        @@@@@@@@@@@@@@@@@@@@@@@@@@*/
        if( $ints >= 192 && $ints <= 223 ){
        // 2 bit
            //$value[] = $temp = ($z-192) * 64 + $y;
            $value[] = $temp = ($z-192) * 64 + $y;
            $value1[]= dechex($temp);
            //$val[]  = $value;
        }  
          
        /* Encoding 3 bit
        @@@@@@@@@@@@@@@@@@@@@@@@@@*/
        if( $ints >= 224 && $ints <= 239 ){
            // 3 bit
            $value[] = $temp = ($z-224) * 4096 + $y * 64 + $x;
            $value1[]= dechex($temp);
            //$val[]  = $value;
        } 
        
        /* Encoding 4 bit
        @@@@@@@@@@@@@@@@@@@@@@@@@@*/    
        if( $ints >= 240 && $ints <= 247 ){
            // 4 bit
            $value[] = $temp = ($z-240) * 262144 + $y * 4096 + $x * 64 + $w;
            $value1[]= dechex($temp);
        } 
         
        /* Encoding 5 bit
        @@@@@@@@@@@@@@@@@@@@@@@@@@*/   
        if( $ints >= 248 && $ints <= 251 ){
            // 5 bit
            $value[] = $temp = ($z-248) * 16777216 + $y * 262144 + $x * 4096 + $w * 64 + $v;
            $value1[]= dechex($temp);
        }
        
        /* Encoding 6 bit
        @@@@@@@@@@@@@@@@@@@@@@@@@@*/
        if( $ints == 252 || $ints == 253 ){
            // 6 bit
            $value[] = $temp = ($z-252) * 1073741824 + $y * 16777216 + $x * 262144 + $w * 4096 + $v * 64 + $u;
            $value1[]= dechex($temp);
        }
        
        /* Wrong Ord!
        @@@@@@@@@@@@@@@@@@@@@@@@@@*/
        if( $ints == 254 || $ints == 255 ){
            echo 'Wrong Result!<br>';
        }
     
    }
 
    if( $array === False ){
        $unicode = '';
        foreach($value as $value){
               $unicode .= '&#'.$value.';';
        
        }
        //return str_replace(array('&#', ';'), '', $unicode);
        return $unicode;
        
    }
    if($array === True ){
       return $value;
    }
 
}
    
}
