<?php

class TplAppMailhog
{
    const MENU = 'mailhog';
    const MENU_VERSIONS = 'mailhogVersions';
    const MENU_SERVICE = 'mailhogService';
    
    const ACTION_SWITCH_VERSION = 'switchMailhogVersion';
    const ACTION_CHANGE_PORT = 'changeMailhogPort';
    const ACTION_INSTALL_SERVICE = 'installMailhogService';
    const ACTION_REMOVE_SERVICE = 'removeMailhogService';
    const ACTION_LAUNCH_STARTUP = 'launchStartupMailhog';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::MAILHOG), self::MENU, get_called_class());
    }
    
    public static function getMenuMailhog()
    {
        global $neardBs, $neardConfig, $neardBins, $neardLang;
        
        $tplVersions = TplApp::getMenu($neardLang->getValue(Lang::VERSIONS), self::MENU_VERSIONS, get_called_class());
        $tplService = TplApp::getMenu($neardLang->getValue(Lang::SERVICE), self::MENU_SERVICE, get_called_class());
        
        return
        
            // Items
            $tplVersions[TplApp::SECTION_CALL] . PHP_EOL .
            $tplService[TplApp::SECTION_CALL] . PHP_EOL . PHP_EOL .
            TplAestan::getItemExe(
                $neardLang->getValue(Lang::MAILHOG),
                $neardConfig->getBrowser(),
                TplAestan::GLYPH_WEB_PAGE,
                $neardBs->getLocalUrl() . ':' . $neardBins->getMailhog()->getUiPort()
            ) . PHP_EOL .
            
            // Actions
            $tplVersions[TplApp::SECTION_CONTENT] . PHP_EOL .
            $tplService[TplApp::SECTION_CONTENT];
    }
    
    public static function getMenuMailhogVersions()
    {
        global $neardBins;
        $items = '';
        $actions = '';
        
        foreach ($neardBins->getMailhog()->getVersionList() as $version) {
            $tplSwitchMailhogVersion = TplApp::getActionMulti(
                self::ACTION_SWITCH_VERSION, array($version),
                array($version, $version == $neardBins->getMailhog()->getVersion() ? TplAestan::GLYPH_CHECK : ''),
                false, get_called_class()
            );
            
            // Item
            $items .= $tplSwitchMailhogVersion[TplApp::SECTION_CALL] . PHP_EOL;
        
            // Action
            $actions .= PHP_EOL . $tplSwitchMailhogVersion[TplApp::SECTION_CONTENT];
        }
        
        return $items . $actions;
    }
    
    public static function getActionSwitchMailhogVersion($version)
    {
        global $neardBs, $neardCore, $neardBins;
    
        return TplApp::getActionRun(Action::SWITCH_VERSION, array($neardBins->getMailhog()->getName(), $version)) . PHP_EOL .
            TplApp::getActionExec() . PHP_EOL;
    }
    
    public static function getMenuMailhogService()
    {
        global $neardLang, $neardBins;
        
        $tplChangePort = TplApp::getActionMulti(
            self::ACTION_CHANGE_PORT, null,
            array($neardLang->getValue(Lang::MENU_CHANGE_PORT), TplAestan::GLYPH_NETWORK),
            false, get_called_class()
        );
        
        $isLaunchStartup = $neardBins->getMailhog()->isLaunchStartup();
        $tplLaunchStartup = TplApp::getActionMulti(
            self::ACTION_LAUNCH_STARTUP, array($isLaunchStartup ? Config::DISABLED : Config::ENABLED),
            array($neardLang->getValue(Lang::MENU_LAUNCH_STARTUP_SERVICE), $isLaunchStartup ? TplAestan::GLYPH_CHECK : ''),
            false, get_called_class()
        );
        
        $isInstalled = $neardBins->getMailhog()->getService()->isInstalled();
        
        $result = TplAestan::getItemActionServiceStart($neardBins->getMailhog()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceStop($neardBins->getMailhog()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceRestart($neardBins->getMailhog()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemSeparator() . PHP_EOL .
            TplApp::getActionRun(
                Action::CHECK_PORT, array($neardBins->getMailhog()->getName(), $neardBins->getMailhog()->getSmtpPort()),
                array(sprintf($neardLang->getValue(Lang::MENU_CHECK_PORT), $neardBins->getMailhog()->getSmtpPort()), TplAestan::GLYPH_LIGHT)
            ) . PHP_EOL .
            $tplChangePort[TplApp::SECTION_CALL] . PHP_EOL .
            $tplLaunchStartup[TplApp::SECTION_CALL] . PHP_EOL;
        
        if (!$isInstalled) {
            $tplInstallService = TplApp::getActionMulti(
                self::ACTION_INSTALL_SERVICE, null,
                array($neardLang->getValue(Lang::MENU_INSTALL_SERVICE), TplAestan::GLYPH_SERVICE_INSTALL),
                $isInstalled, get_called_class()
            );
        
            $result .= $tplInstallService[TplApp::SECTION_CALL] . PHP_EOL . PHP_EOL .
            $tplInstallService[TplApp::SECTION_CONTENT] . PHP_EOL;
        } else {
            $tplRemoveService = TplApp::getActionMulti(
                self::ACTION_REMOVE_SERVICE, null,
                array($neardLang->getValue(Lang::MENU_REMOVE_SERVICE), TplAestan::GLYPH_SERVICE_REMOVE),
                !$isInstalled, get_called_class()
            );
        
            $result .= $tplRemoveService[TplApp::SECTION_CALL] . PHP_EOL . PHP_EOL .
            $tplRemoveService[TplApp::SECTION_CONTENT] . PHP_EOL;
        }
        
        $result .= $tplChangePort[TplApp::SECTION_CONTENT] . PHP_EOL .
            $tplLaunchStartup[TplApp::SECTION_CONTENT] . PHP_EOL;
        
        return $result;
    }
    
    public static function getActionChangeMailhogPort()
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::CHANGE_PORT, array($neardBins->getMailhog()->getName())) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionInstallMailhogService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinMailhog::SERVICE_NAME, ActionService::INSTALL)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionRemoveMailhogService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinMailhog::SERVICE_NAME, ActionService::REMOVE)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionLaunchStartupMailhog($launchStartup)
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::LAUNCH_STARTUP_SERVICE, array($neardBins->getMailhog()->getName(), $launchStartup)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
}
