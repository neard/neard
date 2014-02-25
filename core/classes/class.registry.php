<?php

// StdRegProv class : http://msdn.microsoft.com/en-us/library/aa393664%28v=vs.85%29.aspx
// Shell class : http://msdn.microsoft.com/en-us/library/2x3w20xf%28v=vs.84%29.aspx

class Registry
{
    const HKEY_CLASSES_ROOT = 'HKCR';
    const HKEY_CURRENT_USER = 'HKCU';
    const HKEY_LOCAL_MACHINE = 'HKLM';
    const HKEY_USERS = 'HKEY_USERS';
    
    const REG_SZ = 'REG_SZ';
    const REG_EXPAND_SZ = 'REG_EXPAND_SZ';
    const REG_BINARY = 'REG_BINARY';
    const REG_DWORD = 'REG_DWORD';
    const REG_MULTI_SZ = 'REG_MULTI_SZ';
    
    const REG_ERROR_ENTRY = 'REG_ERROR_ENTRY';
    const REG_ERROR_SET = 'REG_ERROR_SET';
    const REG_NO_ERROR = 'REG_NO_ERROR';
    
    // App bins entry
    const APP_BINS_REG_SUBKEY = 'SYSTEM\CurrentControlSet\Control\Session Manager\Environment';
    const APP_BINS_REG_ENTRY = 'NEARD_BINS';
    
    // App path entry
    const APP_PATH_REG_SUBKEY = 'SYSTEM\CurrentControlSet\Control\Session Manager\Environment';
    const APP_PATH_REG_ENTRY = 'NEARD_PATH';
    
    // System path entry
    const SYSPATH_REG_SUBKEY = 'SYSTEM\CurrentControlSet\Control\Session Manager\Environment';
    const SYSPATH_REG_ENTRY = 'Path';
    
    private $latestError;
    
    public function __construct()
    {
        Util::logInitClass($this);
        
        $this->latestError = null;
    }
    
    private function writeLog($log)
    {
        global $neardBs;
        Util::logDebug($log, $neardBs->getRegistryLogFilePath());
    }
    
    public function exists($key, $subkey, $entry)
    {
        global $neardCore, $neardLang;
        $resultFile = Util::formatWindowsPath($neardCore->getTmpPath() . '/' . Util::random() . '.tmp');
        $this->latestError = null;
        
        $scriptContent = 'On Error Resume Next' . PHP_EOL;
        $scriptContent .= 'Err.Clear' . PHP_EOL . PHP_EOL;
        
        $scriptContent .= 'Dim objShell, objFso, objFile, outFile, entryValue' . PHP_EOL . PHP_EOL;
        
        $scriptContent .= 'outFile = "' . $resultFile . '"' . PHP_EOL;
        $scriptContent .= 'Set objShell = WScript.CreateObject("WScript.Shell")' . PHP_EOL;
        $scriptContent .= 'Set objFso = CreateObject("Scripting.FileSystemObject")' . PHP_EOL;
        $scriptContent .= 'Set objFile = objFso.CreateTextFile(outFile, True)' . PHP_EOL . PHP_EOL;
        
        $scriptContent .= 'entryValue = objShell.RegRead("' . $key . '\\' . $subkey . '\\' . $entry . '")' . PHP_EOL;
        $scriptContent .= 'If Err.Number <> 0 Then' . PHP_EOL;
        $scriptContent .= '    objFile.Write "' . self::REG_ERROR_ENTRY . '" & Err.Number & ": " & Err.Description' . PHP_EOL;
        $scriptContent .= 'Else' . PHP_EOL;
        $scriptContent .= '    objFile.Write "' . self::REG_NO_ERROR . '"' . PHP_EOL;
        $scriptContent .= 'End If' . PHP_EOL;
        $scriptContent .= 'objFile.Close' . PHP_EOL;
        
        $result = $this->execVbs($scriptContent, $resultFile);
        $this->writeLog('Exists ' . $key . '\\' . $subkey . '\\' . $entry);
        $this->writeLog('-> result: ' . $result);
        if ($result != self::REG_NO_ERROR) {
            $this->latestError = $neardLang->getValue(Lang::ERROR) . ' ' . str_replace(self::REG_ERROR_ENTRY, '', $result);
            return false;
        }
        
        return true;
    }
    
    public function getValue($key, $subkey, $entry)
    {
        global $neardCore, $neardLang;
        $resultFile = Util::formatWindowsPath($neardCore->getTmpPath() . '/' . Util::random() . '.tmp');
        $this->latestError = null;
        
        $scriptContent = 'On Error Resume Next' . PHP_EOL;
        $scriptContent .= 'Err.Clear' . PHP_EOL . PHP_EOL;
        
        $scriptContent .= 'Dim objShell, objFso, objFile, outFile, entryValue' . PHP_EOL . PHP_EOL;
        
        $scriptContent .= 'outFile = "' . $resultFile . '"' . PHP_EOL;
        $scriptContent .= 'Set objShell = WScript.CreateObject("WScript.Shell")' . PHP_EOL;
        $scriptContent .= 'Set objFso = CreateObject("Scripting.FileSystemObject")' . PHP_EOL;
        $scriptContent .= 'Set objFile = objFso.CreateTextFile(outFile, True)' . PHP_EOL . PHP_EOL;
        
        $scriptContent .= 'entryValue = objShell.RegRead("' . $key . '\\' . $subkey . '\\' . $entry . '")' . PHP_EOL;
        $scriptContent .= 'If Err.Number <> 0 Then' . PHP_EOL;
        $scriptContent .= '    objFile.Write "' . self::REG_ERROR_ENTRY . '" & Err.Number & ": " & Err.Description' . PHP_EOL;
        $scriptContent .= 'Else' . PHP_EOL;
        $scriptContent .= '    objFile.Write entryValue' . PHP_EOL;
        $scriptContent .= 'End If' . PHP_EOL;
        $scriptContent .= 'objFile.Close' . PHP_EOL;
        
        $result = $this->execVbs($scriptContent, $resultFile);
        $this->writeLog('GetValue ' . $key . '\\' . $subkey . '\\' . $entry);
        $this->writeLog('-> result: ' . $result);
        if (Util::startWith($result, self::REG_ERROR_ENTRY)) {
            $this->latestError = $neardLang->getValue(Lang::ERROR) . ' ' . str_replace(self::REG_ERROR_ENTRY, '', $result);
            return false;
        }
        
        return $result;
    }
    
    public function setStringValue($key, $subkey, $entry, $value)
    {
        return $this->setValue($key, $subkey, $entry, $value, 'SetStringValue');
    }
    
    public function setExpandStringValue($key, $subkey, $entry, $value)
    {
        return $this->setValue($key, $subkey, $entry, $value, 'SetExpandedStringValue');
    }
    
    private function setValue($key, $subkey, $entry, $value, $type)
    {
        global $neardCore, $neardLang;
        $resultFile = Util::formatWindowsPath($neardCore->getTmpPath() . '/' . Util::random() . '.tmp');
        $this->latestError = null;
    
        $strKey = $key;
        if ($key == self::HKEY_CLASSES_ROOT) {
            $key = '&H80000000';
        } elseif ($key == self::HKEY_CURRENT_USER) {
            $key = '&H80000001';
        } elseif ($key == self::HKEY_LOCAL_MACHINE) {
            $key = '&H80000002';
        } elseif ($key == self::HKEY_LOCAL_MACHINE) {
            $key = '&H80000003';
        }
    
        $scriptContent = 'On Error Resume Next' . PHP_EOL;
        $scriptContent .= 'Err.Clear' . PHP_EOL . PHP_EOL;
    
        $scriptContent .= 'Const HKEY = ' . $key . PHP_EOL . PHP_EOL;
    
        $scriptContent .= 'Dim objShell, objRegistry, objFso, objFile, outFile, entryValue, newValue' . PHP_EOL . PHP_EOL;
    
        $scriptContent .= 'newValue = "' . str_replace('"', '""', $value) . '"' . PHP_EOL;
        $scriptContent .= 'outFile = "' . $resultFile . '"' . PHP_EOL;
        $scriptContent .= 'Set objShell = WScript.CreateObject("WScript.Shell")' . PHP_EOL;
        $scriptContent .= 'Set objRegistry = GetObject("winmgmts://./root/default:StdRegProv")' . PHP_EOL;
        $scriptContent .= 'Set objFso = CreateObject("Scripting.FileSystemObject")' . PHP_EOL;
        $scriptContent .= 'Set objFile = objFso.CreateTextFile(outFile, True)' . PHP_EOL . PHP_EOL;
    
        $scriptContent .= 'objRegistry.' . $type . ' HKEY, "' . $subkey . '", "' . $entry . '", newValue' . PHP_EOL;
        $scriptContent .= 'If Err.Number <> 0 Then' . PHP_EOL;
        $scriptContent .= '    objFile.Write "' . self::REG_ERROR_ENTRY . '" & Err.Number & ": " & Err.Description' . PHP_EOL;
        $scriptContent .= 'Else' . PHP_EOL;
        $scriptContent .= '    entryValue = objShell.RegRead("' . $strKey . '\\' . $subkey . '\\' . $entry . '")' . PHP_EOL;
        $scriptContent .= '    If entryValue = newValue Then' . PHP_EOL;
        $scriptContent .= '        objFile.Write "' . self::REG_NO_ERROR . '"' . PHP_EOL;
        $scriptContent .= '    Else' . PHP_EOL;
        $scriptContent .= '        objFile.Write "' . self::REG_ERROR_SET . '" & newValue' . PHP_EOL;
        $scriptContent .= '    End If' . PHP_EOL;
        $scriptContent .= 'End If' . PHP_EOL;
        $scriptContent .= 'objFile.Close' . PHP_EOL;
    
        $result = $this->execVbs($scriptContent, $resultFile);
        $this->writeLog('SetValue ' . $strKey . '\\' . $subkey . '\\' . $entry);
        $this->writeLog('-> value: ' . $value);
        $this->writeLog('-> result: ' . $result);
        if (Util::startWith($result, self::REG_ERROR_SET)) {
            $this->latestError = sprintf($neardLang->getValue(Lang::REGISTRY_SET_ERROR_TEXT), str_replace(self::REG_ERROR_SET, '', $result));
            return false;
        } elseif (Util::startWith($result, self::REG_ERROR_ENTRY)) {
            $this->latestError = $neardLang->getValue(Lang::ERROR) . ' ' . str_replace(self::REG_ERROR_ENTRY, '', $result);
            return false;
        }
    
        return $result == self::REG_NO_ERROR;
    }
    
    private function execVbs($scriptContent, $resultFile)
    {
        global $neardCore, $neardWinbinder;
        $result = false;
        
        $scriptPath = $neardCore->getTmpPath() . '/' . Util::random() . '.vbs';
        $this->writeLog('execVbs script: ' . $scriptPath);
        file_put_contents($scriptPath, $scriptContent);
        
        $maxtime = time() + 2;
        $neardWinbinder->exec('wscript.exe', '"' . $scriptPath . '"');
        
        while ($result === false || empty($result)) {
            $result = file_exists($resultFile) ? file_get_contents($resultFile) : false;
            if ($maxtime < time()) {
                break;
            }
        }
        
        Util::unlinkAlt($scriptPath);
        Util::unlinkAlt($resultFile);
        
        return $result;
    }
    
    public function getLatestError()
    {
        return $this->latestError;
    }
    
}
