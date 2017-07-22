<?php

class Tools
{
    const TYPE = 'tools';

    private $composer;
    private $console;
    private $drush;
    private $git;
    private $hostseditor;
    private $imagemagick;
    private $notepad2mod;
    private $perl;
    private $phpmetrics;
    private $phpunit;
    private $python;
    private $ruby;
    private $wpcli;
    private $xdc;
    private $yarn;
    
    public function __construct()
    {
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
            $this->getPerl(),
            $this->getPhpMetrics(),
            $this->getPhpUnit(),
            $this->getPython(),
            $this->getRuby(),
            $this->getWpCli(),
            $this->getXdc(),
            $this->getYarn(),
        );
    }
    
    public function getComposer()
    {
        if ($this->composer == null) {
            $this->composer = new ToolComposer('composer', self::TYPE);
        }
        return $this->composer;
    }

    public function getConsole()
    {
        if ($this->console == null) {
            $this->console = new ToolConsole('console', self::TYPE);
        }
        return $this->console;
    }
    
    public function getDrush()
    {
        if ($this->drush == null) {
            $this->drush = new ToolDrush('drush', self::TYPE);
        }
        return $this->drush;
    }

    public function getGit()
    {
        if ($this->git == null) {
            $this->git = new ToolGit('git', self::TYPE);
        }
        return $this->git;
    }
    
    public function getHostsEditor()
    {
        if ($this->hostseditor == null) {
            $this->hostseditor = new ToolHostsEditor('hostseditor', self::TYPE);
        }
        return $this->hostseditor;
    }
    
    public function getImageMagick()
    {
        if ($this->imagemagick == null) {
            $this->imagemagick = new ToolImagemagick('imagemagick', self::TYPE);
        }
        return $this->imagemagick;
    }
    
    public function getNotepad2Mod()
    {
        if ($this->notepad2mod == null) {
            $this->notepad2mod = new ToolNotepad2Mod('notepad2mod', self::TYPE);
        }
        return $this->notepad2mod;
    }
    
    public function getPerl()
    {
        if ($this->perl == null) {
            $this->perl= new ToolPerl('perl', self::TYPE);
        }
        return $this->perl;
    }
    
    public function getPhpMetrics()
    {
        if ($this->phpmetrics == null) {
            $this->phpmetrics = new ToolPhpMetrics('phpmetrics', self::TYPE);
        }
        return $this->phpmetrics;
    }
    
    public function getPhpUnit()
    {
        if ($this->phpunit == null) {
            $this->phpunit = new ToolPhpUnit('phpunit', self::TYPE);
        }
        return $this->phpunit;
    }
    
    public function getPython()
    {
        if ($this->python == null) {
            $this->python = new ToolPython('python', self::TYPE);
        }
        return $this->python;
    }
    
    public function getRuby()
    {
        if ($this->ruby == null) {
            $this->ruby = new ToolRuby('ruby', self::TYPE);
        }
        return $this->ruby;
    }
    
    public function getWpCli()
    {
        if ($this->wpcli == null) {
            $this->wpcli = new ToolWpCli('wpcli', self::TYPE);
        }
        return $this->wpcli;
    }
    
    public function getXdc()
    {
        if ($this->xdc == null) {
            $this->xdc = new ToolXdc('xdc', self::TYPE);
        }
        return $this->xdc;
    }
    
    public function getYarn()
    {
        if ($this->yarn == null) {
            $this->yarn= new ToolYarn('yarn', self::TYPE);
        }
        return $this->yarn;
    }
}
