<?php

class TplAppLang
{
    const MENU = 'lang';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::LANG), self::MENU, get_called_class());
    }
    
    public static function getMenuLang()
    {
        global $neardLang;
        $items = '';
        $actions = '';
    
        foreach ($neardLang->getList() as $lang) {
            $tplSwitchLang = TplApp::getActionMulti(
                Action::SWITCH_LANG, array($lang),
                array(ucfirst($lang), $lang == $neardLang->getCurrent() ? TplAestan::GLYPH_CHECK : ''),
                false, get_called_class()
            );
            
            // Item
            $items .= $tplSwitchLang[TplApp::SECTION_CALL] . PHP_EOL;
            
            // Action
            $actions .= PHP_EOL . $tplSwitchLang[TplApp::SECTION_CONTENT] .  PHP_EOL;
        }
    
        return $items . $actions;
    }
    
    public static function getActionSwitchLang($lang)
    {
        return TplApp::getActionRun(Action::SWITCH_LANG, array($lang)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
}
