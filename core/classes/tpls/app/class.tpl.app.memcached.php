<?php

class TplAppMemcached
{
    const MENU = 'memcached';
    const MENU_VERSIONS = 'memcachedVersions';
    const MENU_SERVICE = 'memcachedService';
    
    const ACTION_SWITCH_VERSION = 'switchMemcachedVersion';
    const ACTION_CHANGE_PORT = 'changeMemcachedPort';
    const ACTION_INSTALL_SERVICE = 'installMemcachedService';
    const ACTION_REMOVE_SERVICE = 'removeMemcachedService';
    const ACTION_LAUNCH_STARTUP = 'launchStartupMemcached';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::MEMCACHED), self::MENU, get_called_class());
    }
    
    public static function getMenuMemcached()
    {
        global $neardBs, $neardConfig, $neardBins, $neardLang;
        
        $tplVersions = TplApp::getMenu($neardLang->getValue(Lang::VERSIONS), self::MENU_VERSIONS, get_called_class());
        $tplService = TplApp::getMenu($neardLang->getValue(Lang::SERVICE), self::MENU_SERVICE, get_called_class());
        
        return
        
            // Items
            $tplVersions[TplApp::SECTION_CALL] . PHP_EOL .
            $tplService[TplApp::SECTION_CALL] . PHP_EOL .
            TplAestan::getItemNotepad($neardLang->getValue(Lang::MENU_LOGS), $neardBins->getMemcached()->getLog()) . PHP_EOL . PHP_EOL .
            
            // Actions
            $tplVersions[TplApp::SECTION_CONTENT] . PHP_EOL .
            $tplService[TplApp::SECTION_CONTENT];
    }
    
    public static function getMenuMemcachedVersions()
    {
        global $neardBins;
        $items = '';
        $actions = '';
        
        foreach ($neardBins->getMemcached()->getVersionList() as $version) {
            $tplSwitchMemcachedVersion = TplApp::getActionMulti(
                self::ACTION_SWITCH_VERSION, array($version),
                array($version, $version == $neardBins->getMemcached()->getVersion() ? TplAestan::GLYPH_CHECK : ''),
                false, get_called_class()
            );
            
            // Item
            $items .= $tplSwitchMemcachedVersion[TplApp::SECTION_CALL] . PHP_EOL;
        
            // Action
            $actions .= PHP_EOL . $tplSwitchMemcachedVersion[TplApp::SECTION_CONTENT];
        }
        
        return $items . $actions;
    }
    
    public static function getActionSwitchMemcachedVersion($version)
    {
        global $neardBs, $neardCore, $neardBins;
    
        return TplApp::getActionRun(Action::SWITCH_VERSION, array($neardBins->getMemcached()->getName(), $version)) . PHP_EOL .
            TplApp::getActionExec() . PHP_EOL;
    }
    
    public static function getMenuMemcachedService()
    {
        global $neardLang, $neardBins;
        
        $tplChangePort = TplApp::getActionMulti(
            self::ACTION_CHANGE_PORT, null,
            array($neardLang->getValue(Lang::MENU_CHANGE_PORT), TplAestan::GLYPH_NETWORK),
            false, get_called_class()
        );
        
        $isLaunchStartup = $neardBins->getMemcached()->isLaunchStartup();
        $tplLaunchStartup = TplApp::getActionMulti(
            self::ACTION_LAUNCH_STARTUP, array($isLaunchStartup ? Config::DISABLED : Config::ENABLED),
            array($neardLang->getValue(Lang::MENU_LAUNCH_STARTUP_SERVICE), $isLaunchStartup ? TplAestan::GLYPH_CHECK : ''),
            false, get_called_class()
        );
        
        $isInstalled = $neardBins->getMemcached()->getService()->isInstalled();
        
        $result = TplAestan::getItemActionServiceStart($neardBins->getMemcached()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceStop($neardBins->getMemcached()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceRestart($neardBins->getMemcached()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemSeparator() . PHP_EOL .
            TplApp::getActionRun(
                Action::CHECK_PORT, array($neardBins->getMemcached()->getName(), $neardBins->getMemcached()->getPort()),
                array(sprintf($neardLang->getValue(Lang::MENU_CHECK_PORT), $neardBins->getMemcached()->getPort()), TplAestan::GLYPH_LIGHT)
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
    
    public static function getActionChangeMemcachedPort()
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::CHANGE_PORT, array($neardBins->getMemcached()->getName())) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionInstallMemcachedService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinMemcached::SERVICE_NAME, ActionService::INSTALL)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionRemoveMemcachedService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinMemcached::SERVICE_NAME, ActionService::REMOVE)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionLaunchStartupMemcached($launchStartup)
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::LAUNCH_STARTUP_SERVICE, array($neardBins->getMemcached()->getName(), $launchStartup)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
}
