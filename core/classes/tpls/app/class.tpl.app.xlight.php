<?php

class TplAppXlight
{
    const MENU = 'xlight';
    const MENU_VERSIONS = 'xlightVersions';
    const MENU_SERVICE = 'xlightService';
    
    const ACTION_SWITCH_VERSION = 'switchXlightVersion';
    const ACTION_CHANGE_PORT = 'changeXlightPort';
    const ACTION_INSTALL_SERVICE = 'installXlightService';
    const ACTION_REMOVE_SERVICE = 'removeXlightService';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::XLIGHT), self::MENU, get_called_class());
    }
    
    public static function getMenuXlight()
    {
        global $neardBins, $neardLang, $neardTools;
        
        $tplVersions = TplApp::getMenu($neardLang->getValue(Lang::VERSIONS), self::MENU_VERSIONS, get_called_class());
        $tplService = TplApp::getMenu($neardLang->getValue(Lang::SERVICE), self::MENU_SERVICE, get_called_class());
        
        return
        
            // Items
            $tplVersions[TplApp::SECTION_CALL] . PHP_EOL .
            $tplService[TplApp::SECTION_CALL] . PHP_EOL .
            TplAestan::getItemNotepad($neardLang->getValue(Lang::MENU_ERROR_LOGS), $neardBins->getXlight()->getLogError()) . PHP_EOL .
            TplAestan::getItemNotepad($neardLang->getValue(Lang::MENU_SESSION_LOGS), $neardBins->getXlight()->getLogSession()) . PHP_EOL .
            TplAestan::getItemNotepad($neardLang->getValue(Lang::MENU_STATS_LOGS), $neardBins->getXlight()->getLogStats()) . PHP_EOL .
            TplAestan::getItemNotepad($neardLang->getValue(Lang::MENU_TRANSFER_LOGS), $neardBins->getXlight()->getLogTransfer()) . PHP_EOL . PHP_EOL .
            
            // Actions
            $tplVersions[TplApp::SECTION_CONTENT] . PHP_EOL .
            $tplService[TplApp::SECTION_CONTENT] . PHP_EOL;
    }
    
    public static function getMenuXlightVersions()
    {
        global $neardBins;
        $items = '';
        $actions = '';
        
        foreach ($neardBins->getXlight()->getVersionList() as $version) {
            $tplSwitchXlightVersion = TplApp::getActionMulti(
                self::ACTION_SWITCH_VERSION, array($version),
                array($version, $version == $neardBins->getXlight()->getVersion() ? TplAestan::GLYPH_CHECK : ''),
                false, get_called_class()
            );
            
            // Item
            $items .= $tplSwitchXlightVersion[TplApp::SECTION_CALL] . PHP_EOL;
        
            // Action
            $actions .= PHP_EOL . $tplSwitchXlightVersion[TplApp::SECTION_CONTENT];
        }
        
        return $items . $actions;
    }
    
    public static function getActionSwitchXlightVersion($version)
    {
        global $neardBs, $neardCore, $neardBins;
    
        //TODO: Manage services via Aestan or Win32Service ext ?
        return TplService::getActionDelete(BinXlight::SERVICE_NAME) . PHP_EOL .
            TplAestan::getActionServicesClose() . PHP_EOL .
            TplApp::getActionRun(Action::SWITCH_VERSION, array($neardBins->getXlight()->getName(), $version)) . PHP_EOL .
            TplService::getActionCreate(BinXlight::SERVICE_NAME) . PHP_EOL .
            TplService::getActionStart(BinXlight::SERVICE_NAME) . PHP_EOL .
            TplAppReload::getActionReload() . PHP_EOL .
            TplApp::getActionExec() . PHP_EOL;
    }
    
    public static function getMenuXlightService()
    {
        global $neardLang, $neardBins;
        
        $tplChangePort = TplApp::getActionMulti(
            self::ACTION_CHANGE_PORT, null,
            array($neardLang->getValue(Lang::MENU_CHANGE_PORT), TplAestan::GLYPH_NETWORK),
            false, get_called_class()
        );
        
        //TODO: Manage services via Aestan or Win32Service ext ?
        $result = TplAestan::getItemActionServiceStart($neardBins->getXlight()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceStop($neardBins->getXlight()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceRestart($neardBins->getXlight()->getService()->getName()) . PHP_EOL .
            /*TplService::getItemStart(BinXlight::SERVICE_NAME) . PHP_EOL .
            TplService::getItemStop(BinXlight::SERVICE_NAME) . PHP_EOL .
            TplService::getItemRestart(BinXlight::SERVICE_NAME) . PHP_EOL .*/
            TplAestan::getItemSeparator() . PHP_EOL .
            TplApp::getActionRun(
                Action::CHECK_PORT, array($neardBins->getXlight()->getName(), $neardBins->getXlight()->getPort()),
                array(sprintf($neardLang->getValue(Lang::MENU_CHECK_PORT), $neardBins->getXlight()->getPort()), TplAestan::GLYPH_LIGHT)
            ) . PHP_EOL .
            $tplChangePort[TplApp::SECTION_CALL] . PHP_EOL;
        
        $isInstalled = $neardBins->getXlight()->getService()->isInstalled();
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
        
        $result .= $tplChangePort[TplApp::SECTION_CONTENT] . PHP_EOL;
    
        return $result;
    }
    
    public static function getActionChangeXlightPort()
    {
        global $neardLang, $neardBins;
    
        return TplApp::getActionRun(Action::CHANGE_PORT, array($neardBins->getXlight()->getName())) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionInstallXlightService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinXlight::SERVICE_NAME, ActionService::INSTALL)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionRemoveXlightService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinXlight::SERVICE_NAME, ActionService::REMOVE)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
}
