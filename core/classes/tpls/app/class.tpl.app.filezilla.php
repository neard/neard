<?php

class TplAppFilezilla
{
    const MENU = 'filezilla';
    const MENU_VERSIONS = 'filezillaVersions';
    const MENU_SERVICE = 'filezillaService';
    
    const ACTION_SWITCH_VERSION = 'switchFilezillaVersion';
    const ACTION_CHANGE_PORT = 'changeFilezillaPort';
    const ACTION_INSTALL_SERVICE = 'installFilezillaService';
    const ACTION_REMOVE_SERVICE = 'removeFilezillaService';
    const ACTION_LAUNCH_STARTUP = 'launchStartupFilezilla';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::FILEZILLA), self::MENU, get_called_class());
    }
    
    public static function getMenuFilezilla()
    {
        global $neardBins, $neardLang, $neardTools;
        
        $tplVersions = TplApp::getMenu($neardLang->getValue(Lang::VERSIONS), self::MENU_VERSIONS, get_called_class());
        $tplService = TplApp::getMenu($neardLang->getValue(Lang::SERVICE), self::MENU_SERVICE, get_called_class());
        
        return
        
            // Items
            $tplVersions[TplApp::SECTION_CALL] . PHP_EOL .
            $tplService[TplApp::SECTION_CALL] . PHP_EOL .
            TplAestan::getItemNotepad($neardLang->getValue(Lang::MENU_LOGS), $neardBins->getFilezilla()->getLog()) . PHP_EOL .
            
            // Actions
            $tplVersions[TplApp::SECTION_CONTENT] . PHP_EOL .
            $tplService[TplApp::SECTION_CONTENT] . PHP_EOL;
    }
    
    public static function getMenuFilezillaVersions()
    {
        global $neardBins;
        $items = '';
        $actions = '';
        
        foreach ($neardBins->getFilezilla()->getVersionList() as $version) {
            $tplSwitchFilezillaVersion = TplApp::getActionMulti(
                self::ACTION_SWITCH_VERSION, array($version),
                array($version, $version == $neardBins->getFilezilla()->getVersion() ? TplAestan::GLYPH_CHECK : ''),
                false, get_called_class()
            );
            
            // Item
            $items .= $tplSwitchFilezillaVersion[TplApp::SECTION_CALL] . PHP_EOL;
        
            // Action
            $actions .= PHP_EOL . $tplSwitchFilezillaVersion[TplApp::SECTION_CONTENT];
        }
        
        return $items . $actions;
    }
    
    public static function getActionSwitchFilezillaVersion($version)
    {
        global $neardBs, $neardCore, $neardBins;
    
        return TplService::getActionDelete(BinFilezilla::SERVICE_NAME) . PHP_EOL .
            TplAestan::getActionServicesClose() . PHP_EOL .
            TplApp::getActionRun(Action::SWITCH_VERSION, array($neardBins->getFilezilla()->getName(), $version)) . PHP_EOL .
            TplService::getActionCreate(BinFilezilla::SERVICE_NAME) . PHP_EOL .
            TplService::getActionStart(BinFilezilla::SERVICE_NAME) . PHP_EOL .
            TplAppReload::getActionReload() . PHP_EOL .
            TplApp::getActionExec() . PHP_EOL;
    }
    
    public static function getMenuFilezillaService()
    {
        global $neardLang, $neardBins;
        
        $tplChangePort = TplApp::getActionMulti(
            self::ACTION_CHANGE_PORT, null,
            array($neardLang->getValue(Lang::MENU_CHANGE_PORT), TplAestan::GLYPH_NETWORK),
            false, get_called_class()
        );
        
        $isLaunchStartup = $neardBins->getFilezilla()->getLaunchStartup() == BinFilezilla::LAUNCH_STARTUP_ON;
        $tplLaunchStartup = TplApp::getActionMulti(
            self::ACTION_LAUNCH_STARTUP, array($isLaunchStartup ? BinFilezilla::LAUNCH_STARTUP_OFF : BinFilezilla::LAUNCH_STARTUP_ON),
            array($neardLang->getValue(Lang::MENU_LAUNCH_STARTUP_SERVICE), $isLaunchStartup ? TplAestan::GLYPH_CHECK : ''),
            false, get_called_class()
        );
        
        $result = TplAestan::getItemActionServiceStart($neardBins->getFilezilla()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceStop($neardBins->getFilezilla()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceRestart($neardBins->getFilezilla()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemSeparator() . PHP_EOL .
            TplApp::getActionRun(
                Action::CHECK_PORT, array($neardBins->getFilezilla()->getName(), $neardBins->getFilezilla()->getPort()),
                array(sprintf($neardLang->getValue(Lang::MENU_CHECK_PORT), $neardBins->getFilezilla()->getPort()), TplAestan::GLYPH_LIGHT)
            ) . PHP_EOL .
            TplApp::getActionRun(
                Action::CHECK_PORT, array($neardBins->getFilezilla()->getName(), $neardBins->getFilezilla()->getSslPort(), true),
                array(sprintf($neardLang->getValue(Lang::MENU_CHECK_PORT), $neardBins->getFilezilla()->getSslPort()) . ' (SSL)', TplAestan::GLYPH_RED_LIGHT)
            ) . PHP_EOL .
            $tplChangePort[TplApp::SECTION_CALL] . PHP_EOL .
            $tplLaunchStartup[TplApp::SECTION_CALL] . PHP_EOL;
        
        $isInstalled = $neardBins->getFilezilla()->getService()->isInstalled();
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
    
    public static function getActionChangeFilezillaPort()
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::CHANGE_PORT, array($neardBins->getFilezilla()->getName())) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionInstallFilezillaService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinFilezilla::SERVICE_NAME, ActionService::INSTALL)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionRemoveFilezillaService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinFilezilla::SERVICE_NAME, ActionService::REMOVE)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionLaunchStartupFilezilla($launchStartup)
    {
        global $neardBins;
        
        return TplApp::getActionRun(Action::LAUNCH_STARTUP_SERVICE, array($neardBins->getFilezilla()->getName(), $launchStartup)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
}
