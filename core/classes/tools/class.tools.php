<?php

class Tools
{
    private $composer;
    private $console;
    private $git;
    private $imagemagick;
    private $notepad2;
    private $setenv;
    private $svn;
    private $xdc;
    
    public function __construct()
    {
        
    }
    
    private function getRootPath($tool)
    {
        global $neardBs;
        return $neardBs->getToolsPath() . '/' . $tool;
    }
    
    public function getComposer()
    {
        if ($this->composer == null) {
            $this->composer = new ToolComposer($this->getRootPath('composer'));
        }
        return $this->composer;
    }

    public function getConsole()
    {
        if ($this->console == null) {
            $this->console = new ToolConsole($this->getRootPath('console'));
        }
        return $this->console;
    }

    public function getGit()
    {
        if ($this->git == null) {
            $this->git = new ToolGit($this->getRootPath('git'));
        }
        return $this->git;
    }
    
    public function getImageMagick()
    {
        if ($this->imagemagick == null) {
            $this->imagemagick = new ToolImagemagick($this->getRootPath('imagemagick'));
        }
        return $this->imagemagick;
    }
    
    public function getNotepad2()
    {
        if ($this->notepad2 == null) {
            $this->notepad2 = new ToolNotepad2($this->getRootPath('notepad2'));
        }
        return $this->notepad2;
    }
    
    public function getSetenv()
    {
        if ($this->setenv == null) {
            $this->setenv = new ToolSetenv($this->getRootPath('setenv'));
        }
        return $this->setenv;
    }
    
    public function getSvn()
    {
        if ($this->svn == null) {
            $this->svn = new ToolSvn($this->getRootPath('svn'));
        }
        return $this->svn;
    }
    
    public function getXdc()
    {
        if ($this->xdc == null) {
            $this->xdc = new ToolXdc($this->getRootPath('xdc'));
        }
        return $this->xdc;
    }

}
