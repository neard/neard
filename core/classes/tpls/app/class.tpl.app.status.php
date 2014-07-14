<?php

class TplAppStatus
{
    const ACTION = 'status';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getActionMulti(
            self::ACTION, array(Util::isOnline() ? Config::STATUS_OFFLINE : Config::STATUS_ONLINE),
            array(Util::isOnline() ? $neardLang->getValue(Lang::MENU_PUT_OFFLINE) : $neardLang->getValue(Lang::MENU_PUT_ONLINE)),
            false, get_called_class()
        );
    }
    
    public static function getActionStatus($status)
    {
        global $neardBins;
        
        return TplApp::getActionRun(Action::SWITCH_STATUS, array($status)) . PHP_EOL .
            TplService::getActionRestart(BinApache::SERVICE_NAME) . PHP_EOL .
            TplService::getActionRestart(BinXlight::SERVICE_NAME) . PHP_EOL .
            TplAppReload::getActionReload() . PHP_EOL;
    }
}
