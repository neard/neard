<?php

class TplAppApache
{
    const MENU = 'apache';
    const MENU_VERSIONS = 'apacheVersions';
    const MENU_SERVICE = 'apacheService';
    const MENU_MODULES = 'apacheModules';
    const MENU_ALIAS = 'apacheAlias';
    
    const ACTION_SWITCH_VERSION = 'switchApacheVersion';
    const ACTION_INSTALL_SERVICE = 'installApacheService';
    const ACTION_REMOVE_SERVICE = 'removeApacheService';
    const ACTION_SWITCH_MODULE = 'switchApacheModule';
    const ACTION_ADD_ALIAS = 'addAlias';
    const ACTION_EDIT_ALIAS = 'editAlias';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::APACHE), self::MENU, get_called_class());
    }
    
    public static function getMenuApache()
    {
        global $neardBins, $neardLang;
        
        $tplVersions = TplApp::getMenu($neardLang->getValue(Lang::VERSIONS), self::MENU_VERSIONS, get_called_class());
        $tplService = TplApp::getMenu($neardLang->getValue(Lang::SERVICE), self::MENU_SERVICE, get_called_class());
        $tplModules = TplApp::getMenu($neardLang->getValue(Lang::MODULES), self::MENU_MODULES, get_called_class());
        $tplAlias = TplApp::getMenu($neardLang->getValue(Lang::ALIASES), self::MENU_ALIAS, get_called_class());
        
        return
        
            // Items
            $tplVersions[TplApp::SECTION_CALL] . PHP_EOL .
            $tplService[TplApp::SECTION_CALL] . PHP_EOL .
            $tplModules[TplApp::SECTION_CALL] . PHP_EOL .
            $tplAlias[TplApp::SECTION_CALL] . PHP_EOL .
            TplAestan::getItemNotepad(basename($neardBins->getApache()->getConf()), $neardBins->getApache()->getConf()) . PHP_EOL .
            TplAestan::getItemNotepad($neardLang->getValue(Lang::MENU_ACCESS_LOGS), $neardBins->getApache()->getAccessLog()) . PHP_EOL .
            TplAestan::getItemNotepad($neardLang->getValue(Lang::MENU_ERROR_LOGS), $neardBins->getApache()->getErrorLog()) . PHP_EOL . PHP_EOL .
            
            // Actions
            $tplVersions[TplApp::SECTION_CONTENT] . PHP_EOL .
            $tplService[TplApp::SECTION_CONTENT] . PHP_EOL .
            $tplModules[TplApp::SECTION_CONTENT] . PHP_EOL .
            $tplAlias[TplApp::SECTION_CONTENT] . PHP_EOL;
    }
    
    public static function getMenuApacheVersions()
    {
        global $neardBins;
        $items = '';
        $actions = '';
        
        foreach ($neardBins->getApache()->getVersionList() as $version) {
            $glyph = '';
            $apachePhpModule = $neardBins->getPhp()->getApacheModule($version);
            if ($apachePhpModule === false) {
                $glyph = TplAestan::GLYPH_WARNING;
            } elseif ($version == $neardBins->getApache()->getVersion()) {
                $glyph = TplAestan::GLYPH_CHECK;
            }
            
            $tplSwitchApacheVersion = TplApp::getActionMulti(
                self::ACTION_SWITCH_VERSION, array($version),
                array($version, $glyph),
                false, get_called_class()
            );
            
            // Item
            $items .= $tplSwitchApacheVersion[TplApp::SECTION_CALL] . PHP_EOL;
        
            // Action
            $actions .= PHP_EOL . $tplSwitchApacheVersion[TplApp::SECTION_CONTENT];
        }
        
        return $items . $actions;
    }
    
    public static function getActionSwitchApacheVersion($version)
    {
        global $neardBins;
    
        //TODO: Manage services via Aestan or Win32Service ext ?
        return /*TplService::getActionDelete(BinApache::SERVICE_NAME) . PHP_EOL .
            TplAestan::getActionServicesClose() . PHP_EOL .*/
            TplApp::getActionRun(Action::SWITCH_VERSION, array($neardBins->getApache()->getName(), $version)) . PHP_EOL .
            TplApp::getActionExec() . PHP_EOL;
    }
    
    public static function getMenuApacheService()
    {
        global $neardLang, $neardBins;
        
        //TODO: Manage services via Aestan or Win32Service ext ?
        $result = TplAestan::getItemActionServiceStart($neardBins->getApache()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceStop($neardBins->getApache()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceRestart($neardBins->getApache()->getService()->getName()) . PHP_EOL .
            /*TplService::getItemStart(BinApache::SERVICE_NAME) . PHP_EOL .
            TplService::getItemStop(BinApache::SERVICE_NAME) . PHP_EOL .
            TplService::getItemRestart(BinApache::SERVICE_NAME) . PHP_EOL .*/
            TplAestan::getItemSeparator() . PHP_EOL .
            TplApp::getActionRun(
                Action::CHECK_PORT, array($neardBins->getApache()->getName(), $neardBins->getApache()->getPort()),
                array(sprintf($neardLang->getValue(Lang::MENU_CHECK_PORT), $neardBins->getApache()->getPort()), TplAestan::GLYPH_LIGHT)
            ) . PHP_EOL .
            TplApp::getActionRun(
                Action::CHANGE_PORT, array($neardBins->getApache()->getName()),
                array($neardLang->getValue(Lang::MENU_CHANGE_PORT), TplAestan::GLYPH_NETWORK)
            ) . PHP_EOL;
        
        $isInstalled = $neardBins->getApache()->getService()->isInstalled();
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
    
    public static function getActionInstallApacheService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinApache::SERVICE_NAME, ActionService::INSTALL)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionRemoveApacheService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinApache::SERVICE_NAME, ActionService::REMOVE)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getMenuApacheModules()
    {
        global $neardBins;
        $items = '';
        $actions = '';
    
        foreach ($neardBins->getApache()->getModulesFromConf() as $module => $enabled) {
            $tplSwitchApacheModule = TplApp::getActionMulti(
                self::ACTION_SWITCH_MODULE, array($module, $enabled),
                array($module, ($enabled == ActionSwitchApacheModule::SWITCH_ON ? TplAestan::GLYPH_CHECK : '')),
                false, get_called_class()
            );
            
            // Item
            $items .= $tplSwitchApacheModule[TplApp::SECTION_CALL] . PHP_EOL;
            
            // Action
            $actions .= PHP_EOL . $tplSwitchApacheModule[TplApp::SECTION_CONTENT];
        }
    
        return $items . $actions;
    }
    
    public static function getActionSwitchApacheModule($module, $enabled)
    {
        global $neardBins;
    
        $switch = $enabled ? ActionSwitchApacheModule::SWITCH_OFF : ActionSwitchApacheModule::SWITCH_ON;
        return TplApp::getActionRun(Action::SWITCH_APACHE_MODULE, array($module, $switch)) . PHP_EOL .
            TplAppReload::getActionReload() . PHP_EOL .
            TplService::getActionRestart(BinApache::SERVICE_NAME) . PHP_EOL;
    }
    
    public static function getMenuApacheAlias()
    {
        global $neardLang, $neardBins;
    
        $tplAddAlias = TplApp::getActionMulti(
            self::ACTION_ADD_ALIAS, null,
            array($neardLang->getValue(Lang::MENU_ADD_ALIAS), TplAestan::GLYPH_ADD),
            false, get_called_class()
        );
        
        // Items
        $items = $tplAddAlias[TplApp::SECTION_CALL] . PHP_EOL .
            TplAestan::getItemSeparator() . PHP_EOL;
        
        // Actions
        $actions = PHP_EOL . $tplAddAlias[TplApp::SECTION_CONTENT];
    
        foreach ($neardBins->getApache()->getAlias() as $alias) {
            $tplEditAlias = TplApp::getActionMulti(
                self::ACTION_EDIT_ALIAS, array($alias),
                array(sprintf($neardLang->getValue(Lang::MENU_EDIT_ALIAS), $alias), TplAestan::GLYPH_FILE),
                false, get_called_class()
            );
            
            // Items
            $items .= $tplEditAlias[TplApp::SECTION_CALL] . PHP_EOL;
            
            // Actions
            $actions .= PHP_EOL . PHP_EOL . $tplEditAlias[TplApp::SECTION_CONTENT];
        }
    
        return $items . $actions;
    }
    
    public static function getActionAddAlias()
    {
        return TplApp::getActionRun(Action::ADD_ALIAS) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionEditAlias($alias)
    {
        return TplApp::getActionRun(Action::EDIT_ALIAS, array($alias)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
}
