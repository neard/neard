<?php

class TplAppFilezilla
{
    const MENU = 'filezilla';
    const MENU_VERSIONS = 'filezillaVersions';
    const MENU_SERVICE = 'filezillaService';
    
    const ACTION_ENABLE = 'enableFilezilla';
    const ACTION_SWITCH_VERSION = 'switchFilezillaVersion';
    const ACTION_CHANGE_PORT = 'changeFilezillaPort';
    const ACTION_INSTALL_SERVICE = 'installFilezillaService';
    const ACTION_REMOVE_SERVICE = 'removeFilezillaService';
    
    public static function process()
    {
        global $neardLang, $neardBins;
    
        return TplApp::getMenuEnable($neardLang->getValue(Lang::FILEZILLA), self::MENU, get_called_class(), $neardBins->getFilezilla()->isEnable());
    }
    
    public static function getMenuFilezilla()
    {
        global $neardBins, $neardLang;
        $resultItems = $resultActions = '';
        
        $isEnabled = $neardBins->getFilezilla()->isEnable();
        
        // Download
        $resultItems .= TplAestan::getItemLink(
        $neardLang->getValue(Lang::DOWNLOAD_MORE),
            APP_WEBSITE . '/bins/filezilla/' . Util::getUtmSource() . '#releases',
            false,
            TplAestan::GLYPH_BROWSER
        ) . PHP_EOL;
    
        // Enable
        $tplEnable = TplApp::getActionMulti(
            self::ACTION_ENABLE, array($isEnabled ? Config::DISABLED : Config::ENABLED),
            array($neardLang->getValue(Lang::MENU_ENABLE), $isEnabled ? TplAestan::GLYPH_CHECK : ''),
            false, get_called_class()
        );
        $resultItems .= $tplEnable[TplApp::SECTION_CALL] . PHP_EOL;
        $resultActions .= $tplEnable[TplApp::SECTION_CONTENT] . PHP_EOL;
        
        if ($isEnabled) {
            $resultItems .= TplAestan::getItemSeparator() . PHP_EOL;
        
            // Versions
            $tplVersions = TplApp::getMenu($neardLang->getValue(Lang::VERSIONS), self::MENU_VERSIONS, get_called_class());
            $resultItems .= $tplVersions[TplApp::SECTION_CALL] . PHP_EOL;
            $resultActions .= $tplVersions[TplApp::SECTION_CONTENT] . PHP_EOL;
        
            // Service
            $tplService = TplApp::getMenu($neardLang->getValue(Lang::SERVICE), self::MENU_SERVICE, get_called_class());
            $resultItems .= $tplService[TplApp::SECTION_CALL] . PHP_EOL;
            $resultActions .= $tplService[TplApp::SECTION_CONTENT];
        
            // Admin interface
            $resultItems .= TplAestan::getItemExe(
                $neardLang->getValue(Lang::ADMINISTRATION),
                $neardBins->getFilezilla()->getItfExe(),
                TplAestan::GLYPH_FILEZILLA
            ) . PHP_EOL;
    
            // Log
            $resultItems .= TplAestan::getItemNotepad($neardLang->getValue(Lang::MENU_LOGS), $neardBins->getFilezilla()->getLog()) . PHP_EOL;
        }
        
        return $resultItems . PHP_EOL . $resultActions;
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
    
    public static function getActionEnableFilezilla($enable)
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::ENABLE, array($neardBins->getFilezilla()->getName(), $enable)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionSwitchFilezillaVersion($version)
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::SWITCH_VERSION, array($neardBins->getFilezilla()->getName(), $version)) . PHP_EOL .
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
            $tplChangePort[TplApp::SECTION_CALL] . PHP_EOL;
        
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
        
        $result .= $tplChangePort[TplApp::SECTION_CONTENT] . PHP_EOL;
    
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
}
