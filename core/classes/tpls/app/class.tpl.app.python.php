<?php

class TplAppPython
{
    const MENU = 'python';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::PYTHON), self::MENU, get_called_class());
    }
    
    public static function getMenuPython()
    {
        global $neardLang, $neardTools;
        
        $resultItems = TplAestan::getItemConsole(
            $neardLang->getValue(Lang::PYTHON_CONSOLE),
            TplAestan::GLYPH_PYTHON,
            $neardTools->getConsole()->getTabTitlePython()
        ) . PHP_EOL;
        
        $resultItems .= TplAestan::getItemExe(
            $neardLang->getValue(Lang::PYTHON) . ' IDLE',
            $neardTools->getPython()->getIdleExe(),
            TplAestan::GLYPH_PYTHON
        ) . PHP_EOL;
        
        $resultItems .= TplAestan::getItemExe(
            $neardLang->getValue(Lang::PYTHON_CP),
            $neardTools->getPython()->getCpExe(),
            TplAestan::GLYPH_PYTHON_CP
        ) . PHP_EOL;
        
        return $resultItems;
    }
}
