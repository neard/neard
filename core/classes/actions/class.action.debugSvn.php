<?php

class ActionDebugSvn
{
    public function __construct($args)
    {
        global $neardLang, $neardBins, $neardTools, $neardWinbinder;
        
        if (isset($args[0]) && !empty($args[0])) {
            $editor = false;
            $msgBoxError = false;
            $caption = $neardLang->getValue(Lang::DEBUG) . ' ' . $neardLang->getValue(Lang::SVN) . ' - ';
            if ($args[0] == BinSvn::CMD_VERSION) {
                $caption .= $neardLang->getValue(Lang::DEBUG_SVN_VERSION);
            }
            $caption .= ' (' . $args[0] . ')';
            
            $debugOutput = $neardBins->getSvn()->getCmdLineOutput($args[0]);
            
            if ($editor) {
                Util::openFileContent($caption, $debugOutput['content']);
            } else {
                if ($msgBoxError) {
                    $neardWinbinder->messageBoxError(
                        $debugOutput['content'],
                        $caption
                    );
                } else {
                    $neardWinbinder->messageBoxInfo(
                        $debugOutput['content'],
                        $caption
                    );
                }
            }
        }
    }
}
