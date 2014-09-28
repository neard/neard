<?php

class Tools
{
    private $console;
    private $git;
    private $imagemagick;
    private $runfromprocess;
    private $setenv;
    private $sublimetext;
    private $svn;
    private $tccle;
    private $xdc;
    
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
    
    public function getImageMagick()
    {
        if ($this->imagemagick == null) {
            $this->imagemagick = new ToolImagemagick($this->getRootPath('imagemagick'));
        }
        return $this->imagemagick;
    }
    
    public function getRunFromProcess()
    {
        if ($this->runfromprocess == null) {
            $this->runfromprocess = new ToolRunFromProcess($this->getRootPath('runfromprocess'));
        }
        return $this->runfromprocess;
    }
    
    public function getSetenv()
    {
        if ($this->setenv == null) {
            $this->setenv = new ToolSetenv($this->getRootPath('setenv'));
        }
        return $this->setenv;
    }
    
    public function getSublimetext()
    {
        if ($this->sublimetext == null) {
            $this->sublimetext = new ToolSublimetext($this->getRootPath('sublimetext'));
        }
        return $this->sublimetext;
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
    
    public function getXdc()
    {
        if ($this->xdc == null) {
            $this->xdc = new ToolXdc($this->getRootPath('xdc'));
        }
        return $this->xdc;
    }

}
