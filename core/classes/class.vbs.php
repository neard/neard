<?php

class Vbs
{
    const END_PROCESS_STR = 'FINISHED!';
    
    public function __construct()
    {
        
    }
    
    private static function writeLog($log)
    {
        global $neardBs;
        Util::logDebug($log, $neardBs->getVbsLogFilePath());
    }
    
    public static function countFilesFolders($path)
    {
        $basename = 'countFilesFolders';
        $resultFile = self::getResultFile($basename);
        
        $content = 'Dim objFso, objResultFile, objCheckFile' . PHP_EOL . PHP_EOL;
        $content .= 'Set objFso = CreateObject("scripting.filesystemobject")' . PHP_EOL;
        $content .= 'Set objResultFile = objFso.CreateTextFile("' . $resultFile . '", True)' . PHP_EOL;
        $content .= 'count = 0' . PHP_EOL;
        $content .= 'CountFiles("' . $path . '")' . PHP_EOL . PHP_EOL;
        $content .= 'Function CountFiles(ByVal path)' . PHP_EOL;
        $content .= '    Dim parentFld, subFld' . PHP_EOL;
        $content .= '    Set parentFld = objFso.GetFolder(path)' . PHP_EOL . PHP_EOL;
        $content .= '    count = count + parentFld.Files.Count + + parentFld.SubFolders.Count' . PHP_EOL;
        $content .= '    For Each subFld In parentFld.SubFolders' . PHP_EOL;
        $content .= '        count = count + CountFiles(subFld.Path)' . PHP_EOL;
        $content .= '    Next' . PHP_EOL . PHP_EOL;
        $content .= 'End Function' . PHP_EOL . PHP_EOL;
        $content .= 'objResultFile.Write count' . PHP_EOL;
        $content .= 'objResultFile.Close' . PHP_EOL;
    
        $result = self::exec($basename, $resultFile, $content, 30);
        return isset($result[0]) && is_numeric($result[0]) ? intval($result[0]) : false;
    }
    
    public static function findReposVbs($startPath, $findFolder, $checkFileIns)
    {
        $basename = 'findReposVbs';
        $resultFile = self::getResultFile($basename);
    
        $content = 'Dim objFso, objFile' . PHP_EOL . PHP_EOL;
        $content .= 'Set objFso = CreateObject("scripting.filesystemobject")' . PHP_EOL;
        $content .= 'Set objFile = objFso.CreateTextFile("' . $resultFile . '", True)' . PHP_EOL;
        $content .= 'findFolder = "' . $findFolder . '"' . PHP_EOL;
        $content .= 'checkFileIns = "' . $checkFileIns . '"' . PHP_EOL . PHP_EOL;
        $content .= 'FindRepos("' . $startPath . '")' . PHP_EOL . PHP_EOL;
        $content .= 'Function FindRepos(ByVal path)' . PHP_EOL;
        $content .= '    Dim parentFld, subFld' . PHP_EOL;
        $content .= '    Set parentFld = objFso.GetFolder(path)' . PHP_EOL . PHP_EOL;
        $content .= '    For Each subFld In parentFld.SubFolders' . PHP_EOL;
        $content .= '        If subFld.Name = findFolder And objFso.FileExists(subFld.Path & "\" & checkFileIns) Then' . PHP_EOL;
        $content .= '            objFile.Write parentFld.Path & vbCrLf' . PHP_EOL;
        $content .= '        End If' . PHP_EOL;
        $content .= '        FindRepos(subFld.Path)' . PHP_EOL;
        $content .= '    Next' . PHP_EOL;
        $content .= 'End Function' . PHP_EOL . PHP_EOL;
        $content .= 'objFile.Close' . PHP_EOL;
    
        return self::exec($basename, $resultFile, $content, 30);
    }
    
    public static function getDefaultBrowser()
    {
        $basename = 'getDefaultBrowser';
        $resultFile = self::getResultFile($basename);
    
        $content = 'On Error Resume Next' . PHP_EOL;
        $content .= 'Err.Clear' . PHP_EOL . PHP_EOL;
        $content .= 'Dim objShell, objFso, objFile' . PHP_EOL . PHP_EOL;
        $content .= 'Set objShell = WScript.CreateObject("WScript.Shell")' . PHP_EOL;
        $content .= 'Set objFso = CreateObject("scripting.filesystemobject")' . PHP_EOL;
        $content .= 'Set objFile = objFso.CreateTextFile("' . $resultFile . '", True)' . PHP_EOL . PHP_EOL;
        $content .= 'objFile.Write objShell.RegRead("HKLM\SOFTWARE\Classes\http\shell\open\command\")' . PHP_EOL;
        $content .= 'objFile.Close' . PHP_EOL;
    
        $result = self::exec($basename, $resultFile, $content, 5);
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
        $basename = 'getInstalledBrowsers';
        $resultFile = self::getResultFile($basename);
        
        $content = 'On Error Resume Next' . PHP_EOL;
        $content .= 'Err.Clear' . PHP_EOL . PHP_EOL;
        $content .= 'Dim objShell, objRegistry, objFso, objFile' . PHP_EOL . PHP_EOL;
        $content .= 'Set objShell = WScript.CreateObject("WScript.Shell")' . PHP_EOL;
        $content .= 'Set objRegistry = GetObject("winmgmts://./root/default:StdRegProv")' . PHP_EOL;
        $content .= 'Set objFso = CreateObject("scripting.filesystemobject")' . PHP_EOL;
        $content .= 'Set objFile = objFso.CreateTextFile("' . $resultFile . '", True)' . PHP_EOL . PHP_EOL;
        $content .= 'mainKey = "SOFTWARE\WOW6432Node\Clients\StartMenuInternet"' . PHP_EOL;
        $content .= 'checkKey = objShell.RegRead("HKLM\" & mainKey & "\")' . PHP_EOL;
        $content .= 'If Err.Number <> 0 Then' . PHP_EOL;
        $content .= '    Err.Clear' . PHP_EOL;
        $content .= '    mainKey = "SOFTWARE\Clients\StartMenuInternet"' . PHP_EOL;
        $content .= '    checkKey = objShell.RegRead("HKLM\" & mainKey & "\")' . PHP_EOL;
        $content .= '    If Err.Number <> 0 Then' . PHP_EOL;
        $content .= '        mainKey = ""' . PHP_EOL;
        $content .= '    End If' . PHP_EOL;
        $content .= 'End If' . PHP_EOL . PHP_EOL;
        $content .= 'Err.Clear' . PHP_EOL;
        $content .= 'If mainKey <> "" Then' . PHP_EOL;
        $content .= '    objRegistry.EnumKey &H80000002, mainKey, arrSubKeys' . PHP_EOL;
        $content .= '    For Each subKey In arrSubKeys' . PHP_EOL;
        $content .= '        objFile.Write objShell.RegRead("HKLM\SOFTWARE\Clients\StartMenuInternet\" & subKey & "\shell\open\command\") & vbCrLf' . PHP_EOL;
        $content .= '    Next' . PHP_EOL;
        $content .= 'End If' . PHP_EOL;
        $content .= 'objFile.Close' . PHP_EOL;
    
        $result = self::exec($basename, $resultFile, $content, 5);
        if ($result !== false && !empty($result)) {
            $rebuildResult = array();
            foreach ($result as $browser) {
                $rebuildResult[] = str_replace('"', '', $browser);
            }
            $result = $rebuildResult;
        }
    
        return $result;
    }
    
    public static function getResultFile($basename)
    {
        return self::getTmpFile('.vbs', $basename);
    }
    
    public static function exec($basename, $resultFile, $content, $timeout, $logErrors = false)
    {
        global $neardBs, $neardCore, $neardWinbinder;
        $result = false;
    
        $scriptPath = self::getTmpFile('.vbs', $basename);
        $checkFile = self::getTmpFile('.tmp', $basename);
        $randomVarName = Util::random(15, false);
        $randomObjFile = Util::random(15, false);
        $randomObjFso = Util::random(15, false);
        
        $params = '"' . $scriptPath . '"';
        if ($logErrors) {
            $params .= ' 2>> "' . $neardBs->getVbsLogFilePath() . '"';
        }
        
        // Header
        $header = 'Dim ' . $randomVarName . ', ' . $randomObjFso . ', ' . $randomObjFile . PHP_EOL .
            'Set ' . $randomObjFso . ' = CreateObject("scripting.filesystemobject")' . PHP_EOL .
            'Set ' . $randomObjFile . ' = ' . $randomObjFso . '.CreateTextFile("' . $checkFile . '", True)' . PHP_EOL . PHP_EOL;
        
        // Footer
        $footer = PHP_EOL . PHP_EOL . $randomObjFile . '.Write "' . self::END_PROCESS_STR . '"' . PHP_EOL .
            $randomObjFile . '.Close' . PHP_EOL .
            $randomObjFso . '.DeleteFile("' . $scriptPath . '")';
    
        // Process
        file_put_contents($scriptPath, $header . $content . $footer);
        $neardWinbinder->exec('wscript.exe', $params);
    
        $maxtime = time() + $timeout;
        $noTimeout = $timeout == 0;
        while ($result === false || empty($result)) {
            if (file_exists($checkFile)) {
                $check = file($checkFile);
                if (!empty($check) && trim($check[0]) == self::END_PROCESS_STR) {
                    $result = file($resultFile);
                }
            }
            if ($maxtime < time() && !$noTimeout) {
                break;
            }
        }
        
        Util::unlinkAlt($scriptPath);
        Util::unlinkAlt($checkFile);
        Util::unlinkAlt($resultFile);
    
        self::writeLog('Exec:');
        self::writeLog('-> content: ' . str_replace(PHP_EOL, ' \\\\ ', $content));
        self::writeLog('-> checkFile: ' . $checkFile);
        self::writeLog('-> resultFile: ' . $resultFile);
        self::writeLog('-> scriptPath: ' . $scriptPath);
        
        if ($result !== false && !empty($result)) {
            $rebuildResult = array();
            foreach ($result as $row) {
                $row = trim($row);
                if (!empty($row)) {
                    $rebuildResult[] = $row;
                }
            }
            $result = $rebuildResult;
            self::writeLog('-> result: ' . substr(implode(' \\\\ ', $result), 0, 2048));
        } else {
            self::writeLog('-> result: NULL');
        }
        
        return $result;
    }
    
    private static function getTmpFile($ext, $customName = null)
    {
        global $neardCore;
        return Util::formatWindowsPath($neardCore->getTmpPath() . '/' . (!empty($customName) ? $customName . '-' : '') . Util::random() . $ext);
    }
    
}
