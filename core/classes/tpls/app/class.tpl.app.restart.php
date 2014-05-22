<?php

class TplAppRestart
{
    const ACTION = 'restart';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getActionMulti(
            self::ACTION, null,
            array($neardLang->getValue(Lang::RESTART), TplAestan::GLYPH_RESTART),
            false, get_called_class()
        );
    }
    
    public static function getActionRestart()
    {
        return TplApp::getActionRun(Action::MANUAL_RESTART) . PHP_EOL .
            TplApp::getActionExec() . PHP_EOL;
    }
}
