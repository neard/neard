<?php

class Win32Ps
{
    const NAME = 'name';
    const PID = 'pid';
    const PATH = 'path';
    
    public function __construct()
    {
    }
    
    private static function callWin32Ps($function)
    {
        global $neardBs;
        $result = false;
        
        if (function_exists($function)) {
            $result = @call_user_func($function);
        }
        
        return $result;
    }
    
    public static function getCurrentPid()
    {
        $procInfo = self::getStatProc();
        return isset($procInfo[self::PID]) ? intval($procInfo[self::PID]) : 0;
    }
    
    public static function getListProcs()
    {
        return Vbs::getListProcs();
    }
    
    public static function getStatProc()
    {
        return self::callWin32Ps('win32_ps_stat_proc');
    }
    
    public static function exists($pid)
    {
        return self::findByPid($pid) !== false;
    }
    
    public static function findByPid($pid)
    {
        if (!empty($pid)) {
            $procs = self::getListProcs();
            if ($procs !== false) {
                foreach ($procs as $proc) {
                    if ($proc[self::PID] == $pid) {
                        return $proc;
                    }
                }
            }
        }
    
        return false;
    }
    
    public static function findByPath($path)
    {
        $result = false;
        
        $path = Util::formatUnixPath($path);
        if (!empty($path) && is_file($path)) {
            $procs = self::getListProcs();
            if ($procs !== false) {
                foreach ($procs as $proc) {
                    $unixExePath = Util::formatUnixPath($proc[self::PATH]);
                    if ($unixExePath == $path) {
                        $result[] = $proc;
                    }
                }
            }
        }
    
        return $result;
    }
    
    public static function kill($pid)
    {
        global $acdcWinbinder;
        
        $pid = intval($pid);
        if (!empty($pid)) {
            Vbs::killProc($pid);
        }
    }
    
    public static function killProcs($procs)
    {
        if (empty($procs) || !is_array($procs)) {
            return;
        }
        
        foreach ($procs as $proc) {
            self::kill($proc[self::PID]);
        }
    }
}
