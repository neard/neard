<?php

abstract class Module
{
    const BUNDLE_RELEASE = 'bundleRelease';

    private $type;
    private $id;

    protected $name;
    protected $version;
    protected $release = 'N/A';

    protected $rootPath;
    protected $currentPath;
    protected $symlinkPath;
    protected $enable;
    protected $neardConf;
    protected $neardConfRaw;

    protected function __construct() {

    }

    protected function reload($id = null, $type = null) {
        global $neardBs;

        $this->id = empty($id) ? $this->id : $id;
        $this->type = empty($type) ? $this->type : $type;
        $mainPath = 'N/A';

        switch ($this->type) {
            case Apps::TYPE:
                $mainPath = $neardBs->getAppsPath();
                break;
            case Bins::TYPE:
                $mainPath = $neardBs->getBinPath();
                break;
            case Tools::TYPE:
                $mainPath = $neardBs->getToolsPath();
                break;
        }

        $this->rootPath = $mainPath . '/' . $this->id;
        $this->currentPath = $this->rootPath . '/' . $this->id . $this->version;
        $this->symlinkPath = $this->rootPath . '/current';
        $this->enable = is_dir($this->currentPath);
        $this->neardConf = $this->currentPath . '/neard.conf';
        $this->neardConfRaw = @parse_ini_file($this->neardConf);

        if ($neardBs->isBootstrap()) {
            $this->createSymlink();
        }

        if ($this->neardConfRaw !== false) {
            if (isset($this->neardConfRaw[self::BUNDLE_RELEASE])) {
                $this->release = $this->neardConfRaw[self::BUNDLE_RELEASE];
            }
        }
    }

    private function createSymlink()
    {
        $src = Util::formatWindowsPath($this->currentPath);
        $dest = Util::formatWindowsPath($this->symlinkPath);

        if(file_exists($dest)) {
            if (is_link($dest)) {
                $target = readlink($dest);
                if ($target == $src) {
                    return;
                }
                Batch::removeSymlink($dest);
            } elseif (is_file($dest)) {
                Util::logError('Removing . ' . $this->symlinkPath . ' file. It should not be a regular file');
                unlink($dest);
            } elseif (is_dir($dest)) {
                if (!(new \FilesystemIterator($dest))->valid()) {
                    rmdir($dest);
                } else {
                    Util::logError($this->symlinkPath . ' should be a symlink to ' . $this->currentPath . '. Please remove this dir and restart Neard.');
                    return;
                }
            }
        }

        Batch::createSymlink($src, $dest);
    }

    protected function replace($key, $value) {
        $this->replaceAll(array($key => $value));
    }

    protected function replaceAll($params) {
        $content = file_get_contents($this->neardConf);

        foreach ($params as $key => $value) {
            $content = preg_replace('|' . $key . ' = .*|', $key . ' = ' . '"' . $value.'"', $content);
            $this->neardConfRaw[$key] = $value;
        }

        file_put_contents($this->neardConf, $content);
    }

    public function update($sub = 0, $showWindow = false) {
        $this->updateConfig(null, $sub, $showWindow);
    }

    protected function updateConfig($version = null, $sub = 0, $showWindow = false) {
        $version = $version == null ? $this->version : $version;
        Util::logDebug(($sub > 0 ? str_repeat(' ', 2 * $sub) : '') . 'Update ' . $this->name . ' ' . $version . ' config...');
    }

    public function __toString() {
        return $this->getName();
    }

    public function getType() {
        return $this->type;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getVersion() {
        return $this->version;
    }

    public function getVersionList() {
        return Util::getVersionList($this->rootPath);
    }

    abstract public function setVersion($version);

    public function getRelease() {
        return $this->release;
    }

    public function getRootPath() {
        return $this->rootPath;
    }

    public function getCurrentPath() {
        return $this->currentPath;
    }

    public function getSymlinkPath() {
        return $this->symlinkPath;
    }

    public function isEnable() {
        return $this->enable;
    }
}
