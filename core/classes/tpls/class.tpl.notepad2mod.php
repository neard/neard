<?php

class TplNotepad2Mod
{
    private function __construct()
    {
        
    }
    
    public static function process()
    {
        global $neardTools;
        copy($neardTools->getNotepad2Mod()->getConf() . '.nrd', $neardTools->getNotepad2Mod()->getConf());
    }
}
