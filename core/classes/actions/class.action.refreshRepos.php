<?php

//TODO: Add Winbinder window

class ActionRefreshRepos
{
    const GIT = 'git';
    const SVN = 'svn';
    
    public function __construct($args)
    {
        global $neardTools;
        
        if (isset($args[0]) && !empty($args[0])) {
            if ($args[0] == self::GIT) {
                $neardTools->getGit()->findRepos(false);
            } elseif ($args[0] == self::SVN) {
                $neardTools->getSvn()->findRepos(false);
            }
        }
    }

}
