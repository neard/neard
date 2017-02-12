<?php

class ActionDebugMongodb
{
    public function __construct($args)
    {
        global $neardLang, $neardBins, $neardTools, $neardWinbinder;
        
        if (isset($args[0]) && !empty($args[0])) {
            $editor = false;
            $msgBoxError = false;
            $caption = $neardLang->getValue(Lang::DEBUG) . ' ' . $neardLang->getValue(Lang::MONGODB) . ' - ';
            if ($args[0] == BinMongodb::CMD_VERSION) {
                $caption .= $neardLang->getValue(Lang::DEBUG_MONGODB_VERSION);
            }
            $caption .= ' (' . $args[0] . ')';
            
            $debugOutput = $neardBins->getMongodb()->getCmdLineOutput($args[0]);
            
            if ($editor) {
                $neardTools->getNotepad2Mod()->open($caption, $debugOutput['content']);
            } else {
                if ($msgBoxError) {
                    $neardWinbinder->messageBoxError(
                        $debugOutput,
                        $caption
                    );
                } else {
                    $neardWinbinder->messageBoxInfo(
                        $debugOutput,
                        $caption
                    );
                }
            }
        }
    }
}
