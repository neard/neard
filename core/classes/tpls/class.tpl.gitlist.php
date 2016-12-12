<?php

class TplGitlist
{
    private function __construct()
    {
    }
    
    public static function process()
    {
        global $neardApps, $neardTools;
        
        $result = '[git]' . PHP_EOL;
        $result .= 'client = \'' . $neardTools->getGit()->getExe() . '\'' . PHP_EOL;
        
        $foundRepos = $neardTools->getGit()->findRepos(true);
        if (!empty($foundRepos)) {
            foreach ($foundRepos as $repo) {
                $result .= 'repositories[] = \'' . Util::formatUnixPath($repo) . '\'' . PHP_EOL;
            }
        } else {
            $result .= 'repositories[] = \'\'' . PHP_EOL;
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
        
        file_put_contents($neardApps->getGitlist()->getConf(), $result);
    }
}
