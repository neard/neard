<?php

class TplAppHosts
{
    const MENU = 'hosts';
    
    const ACTION_SWITCH_HOST = 'switchHost';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::HOSTS), self::MENU, get_called_class());
    }
    
    public static function getMenuHosts()
    {
        global $neardLang;
        $items = '';
        $actions = '';
        
        foreach (Util::getHosts() as $host) {
            $tplSwitchHost = TplApp::getActionMulti(
                self::ACTION_SWITCH_HOST, array($host['ip'], $host['domain'], $host['enabled']),
                array($host['domain'] . ' (' . $host['ip'] . ')', ($host['enabled'] ? TplAestan::GLYPH_CHECK : '')),
                false, get_called_class()
            );
        
            // Item
            $items .= $tplSwitchHost[TplApp::SECTION_CALL] . PHP_EOL;
        
            // Action
            $actions .= PHP_EOL . $tplSwitchHost[TplApp::SECTION_CONTENT];
        }
        
        $items .= TplAestan::getItemSeparator() . PHP_EOL .
            TplAestan::getItemNotepad(basename(HOSTS_FILE), HOSTS_FILE) . PHP_EOL;
        
        return $items . $actions;
    }
    
    public static function getActionSwitchHost($ip, $domain, $enabled)
    {
        $switch = $enabled ? ActionSwitchHost::SWITCH_OFF : ActionSwitchHost::SWITCH_ON;
        return TplApp::getActionRun(Action::SWITCH_HOST, array($ip, $domain, $switch)) . PHP_EOL .
            TplAppReload::getActionReload() . PHP_EOL;
    }
    
}
