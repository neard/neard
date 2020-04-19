<?php

class Tools
{
    const TYPE = 'tools';

    private $composer;
    private $consolez;
    private $ghostscript;
    private $git;
    private $ngrok;
    private $perl;
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
            $this->getConsoleZ(),
            $this->getGhostscript(),
            $this->getGit(),
            $this->getNgrok(),
            $this->getPerl(),
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

    public function getConsoleZ()
    {
        if ($this->consolez == null) {
            $this->consolez = new ToolConsoleZ('consolez', self::TYPE);
        }
        return $this->consolez;
    }

    public function getGhostscript()
    {
        if ($this->ghostscript== null) {
            $this->ghostscript= new ToolGhostscript('ghostscript', self::TYPE);
        }
        return $this->ghostscript;
    }

    public function getGit()
    {
        if ($this->git == null) {
            $this->git = new ToolGit('git', self::TYPE);
        }
        return $this->git;
    }

    public function getNgrok()
    {
        if ($this->ngrok == null) {
            $this->ngrok= new ToolNgrok('ngrok', self::TYPE);
        }
        return $this->ngrok;
    }

    public function getPerl()
    {
        if ($this->perl == null) {
            $this->perl= new ToolPerl('perl', self::TYPE);
        }
        return $this->perl;
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
