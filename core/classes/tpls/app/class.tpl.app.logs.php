<?php

class TplAppLogs
{
    const MENU = 'logs';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::LOGS), self::MENU, get_called_class());
    }
    
    public static function getMenuLogs()
    {
        global $neardBs;
        
        $files = array();
        
        $handle = @opendir($neardBs->getLogsPath());
        if (!$handle) {
            return '';
        }
        
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && Util::endWith($file, '.log')) {
                $files[] = $file;
            }
        }
        
        closedir($handle);
        ksort($files);
        
        $result = '';
        foreach ($files as $file) {
            $result .= TplAestan::getItemNotepad(basename($file), $neardBs->getLogsPath() . '/' . $file) . PHP_EOL;
        }
        return $result;
    }
}
