<?php

class TplAppReload
{
    const ACTION = 'reload';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getActionMulti(
            self::ACTION, null,
            array($neardLang->getValue(Lang::RELOAD), TplAestan::GLYPH_RELOAD),
            false, get_called_class()
        );
    }
    
    public static function getActionReload()
    {
        return TplApp::getActionRun(Action::RELOAD) . PHP_EOL .
            'Action: resetservices' . PHP_EOL .
            'Action: readconfig';
    }
}
