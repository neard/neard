<?php

class TplAppMariadb
{
    const MENU = 'mariadb';
    const MENU_VERSIONS = 'mariadbVersions';
    const MENU_SERVICE = 'mariadbService';
    
    const ACTION_SWITCH_VERSION = 'switchMariadbVersion';
    const ACTION_INSTALL_SERVICE = 'installMariadbService';
    const ACTION_REMOVE_SERVICE = 'removeMariadbService';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::MARIADB), self::MENU, get_called_class());
    }
    
    public static function getMenuMariadb()
    {
        global $neardBins, $neardLang, $neardTools;
        
        $tplVersions = TplApp::getMenu($neardLang->getValue(Lang::VERSIONS), self::MENU_VERSIONS, get_called_class());
        $tplService = TplApp::getMenu($neardLang->getValue(Lang::SERVICE), self::MENU_SERVICE, get_called_class());
        
        return
        
            // Items
            $tplVersions[TplApp::SECTION_CALL] . PHP_EOL .
            $tplService[TplApp::SECTION_CALL] . PHP_EOL .
            TplAestan::getItemLink($neardLang->getValue(Lang::PHPMYADMIN), 'phpmyadmin/', true) . PHP_EOL .
            TplAestan::getItemConsole(
                $neardLang->getValue(Lang::CONSOLE),
                TplAestan::GLYPH_CONSOLE,
                $neardTools->getConsole()->getTabTitleMariadb()
            ) . PHP_EOL .
            TplAestan::getItemNotepad(basename($neardBins->getMariadb()->getConf()), $neardBins->getMariadb()->getConf()) . PHP_EOL .
            TplAestan::getItemNotepad($neardLang->getValue(Lang::MENU_ERROR_LOGS), $neardBins->getMariadb()->getErrorLog()) . PHP_EOL . PHP_EOL .
            
            // Actions
            $tplVersions[TplApp::SECTION_CONTENT] . PHP_EOL .
            $tplService[TplApp::SECTION_CONTENT];
    }
    
    public static function getMenuMariadbVersions()
    {
        global $neardBins;
        $items = '';
        $actions = '';
        
        foreach ($neardBins->getMariadb()->getVersionList() as $version) {
            $tplSwitchMariadbVersion = TplApp::getActionMulti(
                self::ACTION_SWITCH_VERSION, array($version),
                array($version, $version == $neardBins->getMariadb()->getVersion() ? TplAestan::GLYPH_CHECK : ''),
                false, get_called_class()
            );
            
            // Item
            $items .= $tplSwitchMariadbVersion[TplApp::SECTION_CALL] . PHP_EOL;
        
            // Action
            $actions .= PHP_EOL . $tplSwitchMariadbVersion[TplApp::SECTION_CONTENT];
        }
        
        return $items . $actions;
    }
    
    public static function getActionSwitchMariadbVersion($version)
    {
        global $neardBs, $neardCore, $neardBins;
    
        //TODO: Manage services via Aestan or Win32Service ext ?
        return TplService::getActionDelete(BinMariadb::SERVICE_NAME) . PHP_EOL .
            TplAestan::getActionServicesClose() . PHP_EOL .
            TplApp::getActionRun(Action::SWITCH_VERSION, array($neardBins->getMariadb()->getName(), $version)) . PHP_EOL .
            TplService::getActionCreate(BinMariadb::SERVICE_NAME) . PHP_EOL .
            TplService::getActionStart(BinMariadb::SERVICE_NAME) . PHP_EOL .
            TplAppReload::getActionReload() . PHP_EOL;
    }
    
    public static function getMenuMariadbService()
    {
        global $neardLang, $neardBins;
        
        //TODO: Manage services via Aestan or Win32Service ext ?
        $result = TplAestan::getItemActionServiceStart($neardBins->getMariadb()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceStop($neardBins->getMariadb()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceRestart($neardBins->getMariadb()->getService()->getName()) . PHP_EOL .
            /*TplService::getItemStart(BinMariadb::SERVICE_NAME) . PHP_EOL .
            TplService::getItemStop(BinMariadb::SERVICE_NAME) . PHP_EOL .
            TplService::getItemRestart(BinMariadb::SERVICE_NAME) . PHP_EOL .*/
            TplAestan::getItemSeparator() . PHP_EOL .
            TplApp::getActionRun(
                Action::CHECK_PORT, array($neardBins->getMariadb()->getName(), $neardBins->getMariadb()->getPort()),
                array(sprintf($neardLang->getValue(Lang::MENU_CHECK_PORT), $neardBins->getMariadb()->getPort()), TplAestan::GLYPH_LIGHT)
            ) . PHP_EOL .
            TplApp::getActionRun(
                Action::CHANGE_PORT, array($neardBins->getMariadb()->getName()),
                array($neardLang->getValue(Lang::MENU_CHANGE_PORT), TplAestan::GLYPH_NETWORK)
            ) . PHP_EOL;
            
        $isInstalled = $neardBins->getMariadb()->getService()->isInstalled();
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
    
        return $result;
    }
    
    public static function getActionInstallMariadbService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinMariadb::SERVICE_NAME, ActionService::INSTALL)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionRemoveMariadbService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinMariadb::SERVICE_NAME, ActionService::REMOVE)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
}
