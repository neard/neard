<?php

class TplAppPostgresql
{
    const MENU = 'postgresql';
    const MENU_VERSIONS = 'postgresqlVersions';
    const MENU_SERVICE = 'postgresqlService';
    const MENU_DEBUG = 'postgresqlDebug';
    
    const ACTION_ENABLE = 'enablePostgresql';
    const ACTION_SWITCH_VERSION = 'switchPostgresqlVersion';
    const ACTION_CHANGE_PORT = 'changePostgresqlPort';
    const ACTION_CHANGE_ROOT_PWD = 'changePostgresqlRootPwd';
    const ACTION_INSTALL_SERVICE = 'installPostgresqlService';
    const ACTION_REMOVE_SERVICE = 'removePostgresqlService';
    
    public static function process()
    {
        global $neardLang, $neardBins;
        
        return TplApp::getMenuEnable($neardLang->getValue(Lang::POSTGRESQL), self::MENU, get_called_class(), $neardBins->getPostgresql()->isEnable());
    }
    
    public static function getMenuPostgresql()
    {
        global $neardBins, $neardLang, $neardTools;
        $resultItems = $resultActions = '';
        
        $isEnabled = $neardBins->getPostgresql()->isEnable();
        
        // Download
        $resultItems .= TplAestan::getItemLink(
            $neardLang->getValue(Lang::DOWNLOAD_MORE),
            Util::getWebsiteUrl('modules/postgresql', '#releases'),
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
            $resultActions .= $tplService[TplApp::SECTION_CONTENT] . PHP_EOL;
            
            // Debug
            $tplDebug = TplApp::getMenu($neardLang->getValue(Lang::DEBUG), self::MENU_DEBUG, get_called_class());
            $resultItems .= $tplDebug[TplApp::SECTION_CALL] . PHP_EOL;
            $resultActions .= $tplDebug[TplApp::SECTION_CONTENT];
            
            // Console
            $resultItems .= TplAestan::getItemConsole(
                $neardLang->getValue(Lang::CONSOLE),
                TplAestan::GLYPH_CONSOLE,
                $neardTools->getConsole()->getTabTitlePostgresql()
            ) . PHP_EOL;
            
            // Conf
            $resultItems .= TplAestan::getItemNotepad(basename($neardBins->getPostgresql()->getConf()), $neardBins->getPostgresql()->getConf()) . PHP_EOL;
            
            // Errors log
            $resultItems .= TplAestan::getItemNotepad($neardLang->getValue(Lang::MENU_ERROR_LOGS), $neardBins->getPostgresql()->getErrorLog()) . PHP_EOL;
        }
        
        return $resultItems . PHP_EOL . $resultActions;
    }
    
    public static function getMenuPostgresqlVersions()
    {
        global $neardBins;
        $items = '';
        $actions = '';
        
        foreach ($neardBins->getPostgresql()->getVersionList() as $version) {
            $tplSwitchPostgresqlVersion = TplApp::getActionMulti(
                self::ACTION_SWITCH_VERSION, array($version),
                array($version, $version == $neardBins->getPostgresql()->getVersion() ? TplAestan::GLYPH_CHECK : ''),
                false, get_called_class()
            );
            
            // Item
            $items .= $tplSwitchPostgresqlVersion[TplApp::SECTION_CALL] . PHP_EOL;
        
            // Action
            $actions .= PHP_EOL . $tplSwitchPostgresqlVersion[TplApp::SECTION_CONTENT];
        }
        
        return $items . $actions;
    }
    
    public static function getActionEnablePostgresql($enable)
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::ENABLE, array($neardBins->getPostgresql()->getName(), $enable)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionSwitchPostgresqlVersion($version)
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::SWITCH_VERSION, array($neardBins->getPostgresql()->getName(), $version)) . PHP_EOL .
            TplApp::getActionExec() . PHP_EOL;
    }
    
    public static function getMenuPostgresqlService()
    {
        global $neardLang, $neardBins;
        
        $tplChangePort = TplApp::getActionMulti(
            self::ACTION_CHANGE_PORT, null,
            array($neardLang->getValue(Lang::MENU_CHANGE_PORT), TplAestan::GLYPH_NETWORK),
            false, get_called_class()
        );
        
        $isInstalled = $neardBins->getPostgresql()->getService()->isInstalled();
        
        $result = TplAestan::getItemActionServiceStart($neardBins->getPostgresql()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceStop($neardBins->getPostgresql()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceRestart($neardBins->getPostgresql()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemSeparator() . PHP_EOL .
            TplApp::getActionRun(
                Action::CHECK_PORT, array($neardBins->getPostgresql()->getName(), $neardBins->getPostgresql()->getPort()),
                array(sprintf($neardLang->getValue(Lang::MENU_CHECK_PORT), $neardBins->getPostgresql()->getPort()), TplAestan::GLYPH_LIGHT)
            ) . PHP_EOL .
            $tplChangePort[TplApp::SECTION_CALL] . PHP_EOL;
        
        $tplChangeRootPwd = null;
        if ($isInstalled) {
            $tplChangeRootPwd = TplApp::getActionMulti(
                self::ACTION_CHANGE_ROOT_PWD, null,
                array($neardLang->getValue(Lang::MENU_CHANGE_ROOT_PWD), TplAestan::GLYPH_PASSWORD),
                !$isInstalled, get_called_class()
            );
        
            $result .= $tplChangeRootPwd[TplApp::SECTION_CALL] . PHP_EOL;
        }
        
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
            ($tplChangeRootPwd != null ? $tplChangeRootPwd[TplApp::SECTION_CONTENT] . PHP_EOL : '');
    
        return $result;
    }
    
    public static function getMenuPostgresqlDebug()
    {
        global $neardLang;
    
        return TplApp::getActionRun(
            Action::DEBUG_POSTGRESQL, array(BinPostgresql::CMD_VERSION),
            array($neardLang->getValue(Lang::DEBUG_POSTGRESQL_VERSION), TplAestan::GLYPH_DEBUG)
        ) . PHP_EOL;
    }
    
    public static function getActionChangePostgresqlPort()
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::CHANGE_PORT, array($neardBins->getPostgresql()->getName())) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionChangePostgresqlRootPwd()
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::CHANGE_DB_ROOT_PWD, array($neardBins->getPostgresql()->getName())) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionInstallPostgresqlService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinPostgresql::SERVICE_NAME, ActionService::INSTALL)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionRemovePostgresqlService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinPostgresql::SERVICE_NAME, ActionService::REMOVE)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
}
