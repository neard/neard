<?php

class TplAppLogsVerbose
{
    const MENU = 'logsVerbose';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::LOGS_VERBOSE), self::MENU, get_called_class());
    }
    
    public static function getMenuLogsVerbose()
    {
        global $neardLang, $neardConfig;
        
        $items = '';
        $actions = '';
        
        $verboses = array(
            Config::VERBOSE_SIMPLE => $neardLang->getValue(Lang::VERBOSE_SIMPLE),
            Config::VERBOSE_REPORT => $neardLang->getValue(Lang::VERBOSE_REPORT),
            Config::VERBOSE_DEBUG  => $neardLang->getValue(Lang::VERBOSE_DEBUG),
            Config::VERBOSE_TRACE  => $neardLang->getValue(Lang::VERBOSE_TRACE),
        );
        
        foreach ($verboses as $verbose => $caption) {
            $tplSwitchLogsVerbose = TplApp::getActionMulti(
                Action::SWITCH_LOGS_VERBOSE, array($verbose),
                array($caption, $verbose == $neardConfig->getLogsVerbose() ? TplAestan::GLYPH_CHECK : ''),
                false, get_called_class()
            );
            
            // Item
            $items .= $tplSwitchLogsVerbose[TplApp::SECTION_CALL] . PHP_EOL;
            
            // Action
            $actions .= PHP_EOL . $tplSwitchLogsVerbose[TplApp::SECTION_CONTENT] .  PHP_EOL;
        }
    
        return $items . $actions;
    }
    
    public static function getActionSwitchLogsVerbose($verbose)
    {
        return TplApp::getActionRun(Action::SWITCH_LOGS_VERBOSE, array($verbose)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
}
