<?php

class Win32Ps
{
    const PID = 'pid';
    const EXE = 'exe';
    
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
        return self::callWin32Ps('win32_ps_list_procs');
    }
    
    public static function getStatProc()
    {
        return self::callWin32Ps('win32_ps_stat_proc');
    }
    
    public static function exists($pid)
    {
        return self::findProc($pid) !== false;
    }
    
    public static function findProc($pid)
    {
        if (!empty($pid)) {
            $pids = self::getListProcs();
            if ($pids !== false) {
                foreach ($pids as $aPid) {
                    if (isset($aPid[self::PID]) && $pid == $aPid[self::PID]) {
                        return $aPid;
                    }
                }
            }
        }
        
        return false;
    }
    
    public static function kill($pid)
    {
        global $neardWinbinder;
        
        $pid = intval($pid);
        if (!empty($pid)) {
            $neardWinbinder->exec('TASKKILL', '/F /PID ' . $pid, true);
        }
    }
}
