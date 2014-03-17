<?php

class Tools
{
    private $console;
    private $git;
    private $svn;
    private $tccle;
    private $sublimetext;
    private $xdc;
    private $imagick;
    
    public function __construct()
    {
        
    }
    
    private function getRootPath($tool)
    {
        global $neardBs;
        return $neardBs->getToolsPath() . '/' . $tool;
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

    public function getSvn()
    {
        if ($this->svn == null) {
            $this->svn = new ToolSvn($this->getRootPath('svn'));
        }
        return $this->svn;
    }
    
    public function getTccle()
    {
        if ($this->tccle == null) {
            $this->tccle = new ToolTccle($this->getRootPath('tccle'));
        }
        return $this->tccle;
    }
    
    public function getSublimetext()
    {
        if ($this->sublimetext == null) {
            $this->sublimetext = new ToolSublimetext($this->getRootPath('sublimetext'));
        }
        return $this->sublimetext;
    }
    
    public function getXdc()
    {
        if ($this->xdc == null) {
            $this->xdc = new ToolXdc($this->getRootPath('xdc'));
        }
        return $this->xdc;
    }

    public function getImagick()
    {
        if ($this->imagick == null) {
            $this->imagick = new ToolImagick($this->getRootPath('imagick'));
        }
        return $this->imagick;
    }

}
