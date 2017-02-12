<?php

class TplService
{
    public static function getActionCreate($sName)
    {
        return TplApp::getActionRun(Action::SERVICE, array($sName, ActionService::CREATE));
    }
    
    public static function getActionStart($sName)
    {
        return TplApp::getActionRun(Action::SERVICE, array($sName, ActionService::START));
    }
    
    public static function getActionStop($sName)
    {
        return TplApp::getActionRun(Action::SERVICE, array($sName, ActionService::STOP));
    }
    
    public static function getActionRestart($sName)
    {
        return TplApp::getActionRun(Action::SERVICE, array($sName, ActionService::RESTART));
    }
    
    public static function getActionInstall($sName)
    {
        return TplApp::getActionRun(Action::SERVICE, array($sName, ActionService::INSTALL));
    }
    
    public static function getActionRemove($sName)
    {
        return TplApp::getActionRun(Action::SERVICE, array($sName, ActionService::REMOVE));
    }
    
    public static function getItemStart($sName)
    {
        global $neardLang;
        
        return TplApp::getActionRun(
            Action::SERVICE, array($sName, ActionService::START),
            array($neardLang->getValue(Lang::MENU_START_SERVICE), TplAestan::GLYPH_START)
        );
    }
    
    public static function getItemStop($sName)
    {
        global $neardLang;
        
        return TplApp::getActionRun(
            Action::SERVICE, array($sName, ActionService::STOP),
            array($neardLang->getValue(Lang::MENU_STOP_SERVICE), TplAestan::GLYPH_STOP)
        );
    }
    
    public static function getItemRestart($sName)
    {
        global $neardLang;
        
        return TplApp::getActionRun(
            Action::SERVICE, array($sName, ActionService::RESTART),
            array($neardLang->getValue(Lang::MENU_RESTART_SERVICE), TplAestan::GLYPH_RELOAD)
        );
    }
    
    public static function getItemInstall($sName)
    {
        global $neardLang;
        
        return TplApp::getActionRun(
            Action::SERVICE, array($sName, ActionService::INSTALL),
            array($neardLang->getValue(Lang::MENU_INSTALL_SERVICE), TplAestan::GLYPH_SERVICE_INSTALL)
        );
    }
    
    public static function getItemRemove($sName)
    {
        global $neardLang;
        
        return TplApp::getActionRun(
            Action::SERVICE, array($sName, ActionService::REMOVE),
            array($neardLang->getValue(Lang::MENU_REMOVE_SERVICE), TplAestan::GLYPH_SERVICE_REMOVE)
        );
    }
}
