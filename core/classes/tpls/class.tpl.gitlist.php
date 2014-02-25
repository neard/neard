<?php

class TplGitlist
{
    private function __construct()
    {
        
    }
    
    public static function process()
    {
        global $neardConfig, $neardTools;
        
        $result = '[git]' . PHP_EOL;
        $result .= 'client = \'' . $neardTools->getGit()->getExe() . '\'' . PHP_EOL;
        
        // Repositories
        $refactorRepos = array();
        foreach ($neardTools->getGit()->findRepos(true) as $repo) {
            /*$repo = dirname($repo);
            if (!in_array($repo, $refactorRepos)) {
                $refactorRepos[] = $repo;*/
                $result .= 'repositories[] = \'' . Util::formatUnixPath($repo) . '\'' . PHP_EOL;
            //}
        }
        
        // App
        $result .= PHP_EOL . '[app]' . PHP_EOL;
        $result .= 'debug = false' . PHP_EOL;
        $result .= 'cache = false' . PHP_EOL . PHP_EOL;
        
        // Filetypes
        $result .= '[filetypes]' . PHP_EOL;
        $result .= '; extension = type' . PHP_EOL;
        $result .= '; dist = xml' . PHP_EOL . PHP_EOL;
        
        // Binary filetypes
        $result .= '[binary_filetypes]' . PHP_EOL;
        $result .= '; extension = true' . PHP_EOL;
        $result .= '; svh = false' . PHP_EOL;
        $result .= '; map = true' . PHP_EOL . PHP_EOL;
        
        return $result;
    }
}
