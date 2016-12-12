<?php

class TplAppClearFolders
{
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getActionRun(
            Action::CLEAR_FOLDERS, null,
            array($neardLang->getValue(Lang::MENU_CLEAR_FOLDERS), TplAestan::GLYPH_TRASHCAN)
        );
    }
}
