<?php

class ActionRefreshReposStartup
{
    public function __construct($args)
    {
        global $neardTools;
        
        if (isset($args[0]) && !empty($args[0]) && isset($args[1])) {
            if ($args[0] == ActionRefreshRepos::GIT) {
                $neardTools->getGit()->setScanStartup($args[1]);
            } elseif ($args[0] == ActionRefreshRepos::SVN) {
                $neardTools->getSvn()->setScanStartup($args[1]);
            }
        }
    }
}
