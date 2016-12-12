<?php

class Tools
{
    private $composer;
    private $console;
    private $drush;
    private $git;
    private $hostseditor;
    private $imagemagick;
    private $notepad2mod;
    private $phpmetrics;
    private $phpunit;
    private $python;
    private $ruby;
    private $wpcli;
    private $xdc;
    
    public function __construct()
    {
    }
    
    private function getRootPath($tool)
    {
        global $neardBs;
        return $neardBs->getToolsPath() . '/' . $tool;
    }
    
    public function update()
    {
        Util::logInfo('Update tools config');
        foreach ($this->getAll() as $tool) {
            $tool->update();
        }
    }
    
    public function getAll()
    {
        return array(
            $this->getComposer(),
            $this->getConsole(),
            $this->getDrush(),
            $this->getGit(),
            $this->getHostsEditor(),
            $this->getImageMagick(),
            $this->getNotepad2Mod(),
            $this->getPhpMetrics(),
            $this->getPhpUnit(),
            $this->getPython(),
            $this->getRuby(),
            $this->getWpCli(),
            $this->getXdc(),
        );
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
    
    public function getDrush()
    {
        if ($this->drush == null) {
            $this->drush = new ToolDrush($this->getRootPath('drush'));
        }
        return $this->drush;
    }

    public function getGit()
    {
        if ($this->git == null) {
            $this->git = new ToolGit($this->getRootPath('git'));
        }
        return $this->git;
    }
    
    public function getHostsEditor()
    {
        if ($this->hostseditor == null) {
            $this->hostseditor = new ToolHostsEditor($this->getRootPath('hostseditor'));
        }
        return $this->hostseditor;
    }
    
    public function getImageMagick()
    {
        if ($this->imagemagick == null) {
            $this->imagemagick = new ToolImagemagick($this->getRootPath('imagemagick'));
        }
        return $this->imagemagick;
    }
    
    public function getNotepad2Mod()
    {
        if ($this->notepad2mod == null) {
            $this->notepad2mod = new ToolNotepad2Mod($this->getRootPath('notepad2mod'));
        }
        return $this->notepad2mod;
    }
    
    public function getPhpMetrics()
    {
        if ($this->phpmetrics == null) {
            $this->phpmetrics = new ToolPhpMetrics($this->getRootPath('phpmetrics'));
        }
        return $this->phpmetrics;
    }
    
    public function getPhpUnit()
    {
        if ($this->phpunit == null) {
            $this->phpunit = new ToolPhpUnit($this->getRootPath('phpunit'));
        }
        return $this->phpunit;
    }
    
    public function getPython()
    {
        if ($this->python == null) {
            $this->python = new ToolPython($this->getRootPath('python'));
        }
        return $this->python;
    }
    
    public function getRuby()
    {
        if ($this->ruby == null) {
            $this->ruby = new ToolRuby($this->getRootPath('ruby'));
        }
        return $this->ruby;
    }
    
    public function getWpCli()
    {
        if ($this->wpcli == null) {
            $this->wpcli = new ToolWpCli($this->getRootPath('wpcli'));
        }
        return $this->wpcli;
    }
    
    public function getXdc()
    {
        if ($this->xdc == null) {
            $this->xdc = new ToolXdc($this->getRootPath('xdc'));
        }
        return $this->xdc;
    }
}
