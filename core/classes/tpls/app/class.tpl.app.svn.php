<?php

class TplAppSvn
{
    const MENU = 'svn';
    const MENU_REPOS = 'svnRepos';
    
    const ACTION_REFRESH_REPOS = 'refreshRepos';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::SVN), self::MENU, get_called_class());
    }
    
    public static function getMenuSvn()
    {
        global $neardLang;
        
        $tplRepos = TplApp::getMenu($neardLang->getValue(Lang::REPOS), self::MENU_REPOS, get_called_class());
        $tplRefreshRepos = TplApp::getActionMulti(
            self::ACTION_REFRESH_REPOS, null,
            array($neardLang->getValue(Lang::MENU_REFRESH_REPOS), TplAestan::GLYPH_RELOAD),
            false, get_called_class()
        );
        
        return
        
            // Items
            $tplRepos[TplApp::SECTION_CALL] . PHP_EOL .
            $tplRefreshRepos[TplApp::SECTION_CALL] . PHP_EOL .
            TplAestan::getItemLink($neardLang->getValue(Lang::WEBSVN), 'websvn/', true) . PHP_EOL .
            
            // Actions
            $tplRepos[TplApp::SECTION_CONTENT] .
            $tplRefreshRepos[TplApp::SECTION_CONTENT];
    }
    
    public static function getMenuSvnRepos()
    {
        global $neardTools;
        $result = '';
        
        foreach ($neardTools->getSvn()->findRepos() as $repo) {
            $result .= TplAestan::getItemConsole(
                basename($repo),
                TplAestan::GLYPH_SVN,
                $neardTools->getConsole()->getTabTitleSvn(),
                $neardTools->getConsole()->getTabTitleSvn($repo),
                $repo
            ) . PHP_EOL;
        }
        
        return $result;
    }
    
    public static function getActionRefreshRepos()
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::REFRESH_REPOS, array(ActionRefreshRepos::SVN)) . PHP_EOL .
            TplAppReload::getActionReload() . PHP_EOL;
    }
    
}
