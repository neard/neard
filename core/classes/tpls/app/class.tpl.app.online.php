<?php

class TplAppOnline
{
    const ACTION = 'status';
    
    public static function process()
    {
        global $neardConfig, $neardLang;
        
        return TplApp::getActionMulti(
            self::ACTION, array($neardConfig->isOnline() ? Config::DISABLED : Config::ENABLED),
            array($neardConfig->isOnline() ? $neardLang->getValue(Lang::MENU_PUT_OFFLINE) : $neardLang->getValue(Lang::MENU_PUT_ONLINE)),
            false, get_called_class()
        );
    }
    
    public static function getActionStatus($status)
    {
        return TplApp::getActionRun(Action::SWITCH_ONLINE, array($status)) . PHP_EOL .
            TplService::getActionRestart(BinApache::SERVICE_NAME) . PHP_EOL .
            TplService::getActionRestart(BinFilezilla::SERVICE_NAME) . PHP_EOL .
            TplAppReload::getActionReload() . PHP_EOL;
    }
}
