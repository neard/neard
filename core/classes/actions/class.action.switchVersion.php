<?php

class ActionSwitchVersion
{
    private $neardSplash;

    private $version;
    private $bin;
    private $currentVersion;
    private $service;
    private $changePort;
    private $boxTitle;

    const GAUGE_SERVICES = 1;
    const GAUGE_OTHERS = 7;

    public function __construct($args)
    {
        global $neardLang, $neardBins, $neardWinbinder;

        if (isset($args[0]) && !empty($args[0]) && isset($args[1]) && !empty($args[1])) {
            $this->pathsToScan = array();
            $this->version = $args[1];

            if ($args[0] == $neardBins->getApache()->getName()) {
                $this->bin = $neardBins->getApache();
                $this->currentVersion = $neardBins->getApache()->getVersion();
                $this->service = $neardBins->getApache()->getService();
                $this->changePort = true;
                $folderList = Util::getFolderList($neardBins->getApache()->getRootPath());
                foreach ($folderList as $folder) {
                    $this->pathsToScan[] = array(
                        'path' => $neardBins->getApache()->getRootPath() . '/' . $folder,
                        'includes' => array('.ini', '.conf'),
                        'recursive' => true
                    );
                }
            } elseif ($args[0] == $neardBins->getPhp()->getName()) {
                $this->bin = $neardBins->getPhp();
                $this->currentVersion = $neardBins->getPhp()->getVersion();
                $this->service = $neardBins->getApache()->getService();
                $this->changePort = false;
                $folderList = Util::getFolderList($neardBins->getPhp()->getRootPath());
                foreach ($folderList as $folder) {
                    $this->pathsToScan[] = array(
                        'path' => $neardBins->getPhp()->getRootPath() . '/' . $folder,
                        'includes' => array('.php', '.bat', '.ini', '.reg', '.inc'),
                        'recursive' => true
                    );
                }
            } elseif ($args[0] == $neardBins->getMysql()->getName()) {
                $this->bin = $neardBins->getMysql();
                $this->currentVersion = $neardBins->getMysql()->getVersion();
                $this->service = $neardBins->getMysql()->getService();
                $this->changePort = true;
                $folderList = Util::getFolderList($neardBins->getMysql()->getRootPath());
                foreach ($folderList as $folder) {
                    $this->pathsToScan[] = array(
                        'path' => $neardBins->getMysql()->getRootPath() . '/' . $folder,
                        'includes' => array('my.ini'),
                        'recursive' => false
                    );
                }
            } elseif ($args[0] == $neardBins->getMariadb()->getName()) {
                $this->bin = $neardBins->getMariadb();
                $this->currentVersion = $neardBins->getMariadb()->getVersion();
                $this->service = $neardBins->getMariadb()->getService();
                $this->changePort = true;
                $folderList = Util::getFolderList($neardBins->getMariadb()->getRootPath());
                foreach ($folderList as $folder) {
                    $this->pathsToScan[] = array(
                        'path' => $neardBins->getMariadb()->getRootPath() . '/' . $folder,
                        'includes' => array('my.ini'),
                        'recursive' => false
                    );
                }
            } elseif ($args[0] == $neardBins->getMongodb()->getName()) {
                $this->bin = $neardBins->getMongodb();
                $this->currentVersion = $neardBins->getMongodb()->getVersion();
                $this->service = $neardBins->getMongodb()->getService();
                $this->changePort = true;
                $folderList = Util::getFolderList($neardBins->getMongodb()->getRootPath());
                foreach ($folderList as $folder) {
                    $this->pathsToScan[] = array(
                        'path' => $neardBins->getMongodb()->getRootPath() . '/' . $folder,
                        'includes' => array('mongodb.conf'),
                        'recursive' => false
                    );
                }
            } elseif ($args[0] == $neardBins->getPostgresql()->getName()) {
                $this->bin = $neardBins->getPostgresql();
                $this->currentVersion = $neardBins->getPostgresql()->getVersion();
                $this->service = $neardBins->getPostgresql()->getService();
                $this->changePort = true;
                $folderList = Util::getFolderList($neardBins->getPostgresql()->getRootPath());
                foreach ($folderList as $folder) {
                    $this->pathsToScan[] = array(
                        'path' => $neardBins->getPostgresql()->getRootPath() . '/' . $folder,
                        'includes' => array('.nrd', '.conf', '.bat'),
                        'recursive' => true
                    );
                }
            } elseif ($args[0] == $neardBins->getNodejs()->getName()) {
                $this->bin = $neardBins->getNodejs();
                $this->currentVersion = $neardBins->getNodejs()->getVersion();
                $this->service = null;
                $this->changePort = false;
                $folderList = Util::getFolderList($neardBins->getNodejs()->getRootPath());
                foreach ($folderList as $folder) {
                    $this->pathsToScan[] = array(
                        'path' => $neardBins->getNodejs()->getRootPath() . '/' . $folder . '/etc',
                        'includes' => array('npmrc'),
                        'recursive' => true
                    );
                    $this->pathsToScan[] = array(
                        'path' => $neardBins->getNodejs()->getRootPath() . '/' . $folder . '/node_modules/npm',
                        'includes' => array('npmrc'),
                        'recursive' => false
                    );
                }
            } elseif ($args[0] == $neardBins->getFilezilla()->getName()) {
                $this->bin = $neardBins->getFilezilla();
                $this->currentVersion = $neardBins->getFilezilla()->getVersion();
                $this->service = $neardBins->getFilezilla()->getService();
                $this->changePort = true;
                $folderList = Util::getFolderList($neardBins->getFilezilla()->getRootPath());
                foreach ($folderList as $folder) {
                    $this->pathsToScan[] = array(
                        'path' => $neardBins->getFilezilla()->getRootPath() . '/' . $folder,
                        'includes' => array('.xml'),
                        'recursive' => true
                    );
                }
            } elseif ($args[0] == $neardBins->getMemcached()->getName()) {
                $this->bin = $neardBins->getMemcached();
                $this->currentVersion = $neardBins->getMemcached()->getVersion();
                $this->service = $neardBins->getMemcached()->getService();
                $this->changePort = true;
            } elseif ($args[0] == $neardBins->getSvn()->getName()) {
                $this->bin = $neardBins->getSvn();
                $this->currentVersion = $neardBins->getSvn()->getVersion();
                $this->service = $neardBins->getSvn()->getService();
                $this->changePort = true;
            }

            $this->boxTitle = sprintf($neardLang->getValue(Lang::SWITCH_VERSION_TITLE), $this->bin->getName(), $this->version);

            // Start splash screen
            $this->neardSplash = new Splash();
            $this->neardSplash->init(
                $this->boxTitle,
                self::GAUGE_SERVICES * count($neardBins->getServices()) + self::GAUGE_OTHERS,
                $this->boxTitle
            );

            $neardWinbinder->setHandler($this->neardSplash->getWbWindow(), $this, 'processWindow', 1000);
            $neardWinbinder->mainLoop();
            $neardWinbinder->reset();
        }
    }

    public function processWindow($window, $id, $ctrl, $param1, $param2)
    {
        global $neardCore, $neardLang, $neardBins, $neardWinbinder;

        if ($this->version == $this->currentVersion) {
            $neardWinbinder->messageBoxWarning(sprintf($neardLang->getValue(Lang::SWITCH_VERSION_SAME_ERROR), $this->bin->getName(), $this->version), $this->boxTitle);
            $neardWinbinder->destroyWindow($window);
        }

        // scan folder
        $this->neardSplash->incrProgressBar();
        if (!empty($this->pathsToScan)) {
            Util::changePath(Util::getFilesToScan($this->pathsToScan));
        }

        // switch
        $this->neardSplash->incrProgressBar();
        if ($this->bin->switchVersion($this->version, true) === false) {
            $this->neardSplash->incrProgressBar(self::GAUGE_SERVICES * count($neardBins->getServices()) + self::GAUGE_OTHERS);
            $neardWinbinder->destroyWindow($window);
        }

        // stop service
        if ($this->service != null) {
            $binName = $this->bin->getName() == $neardLang->getValue(Lang::PHP) ? $neardLang->getValue(Lang::APACHE) : $this->bin->getName();
            $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::STOP_SERVICE_TITLE), $binName));
            $this->neardSplash->incrProgressBar();
            $this->service->stop();
        } else {
            $this->neardSplash->incrProgressBar();
        }

        // reload config
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::SWITCH_VERSION_RELOAD_CONFIG));
        $this->neardSplash->incrProgressBar();
        Bootstrap::loadConfig();

        // reload bins
        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::SWITCH_VERSION_RELOAD_BINS));
        $this->neardSplash->incrProgressBar();
        $neardBins->reload();

        // change port
        if ($this->changePort) {
            $this->bin->reload();
            $this->bin->changePort($this->bin->getPort());
        }

        // start service
        if ($this->service != null) {
            $binName = $this->bin->getName() == $neardLang->getValue(Lang::PHP) ? $neardLang->getValue(Lang::APACHE) : $this->bin->getName();
            $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::START_SERVICE_TITLE), $binName));
            $this->neardSplash->incrProgressBar();
            $this->service->start();
        } else {
            $this->neardSplash->incrProgressBar();
        }

        $this->neardSplash->incrProgressBar(self::GAUGE_SERVICES * count($neardBins->getServices()) + 1);
        $neardWinbinder->messageBoxInfo(
            sprintf($neardLang->getValue(Lang::SWITCH_VERSION_OK), $this->bin->getName(), $this->version),
            $this->boxTitle);
        $neardWinbinder->destroyWindow($window);

        $this->neardSplash->setTextLoading(sprintf($neardLang->getValue(Lang::SWITCH_VERSION_REGISTRY), Registry::APP_BINS_REG_ENTRY));
        $this->neardSplash->incrProgressBar(2);
        Util::setAppBinsRegKey(Util::getAppBinsRegKey(false));

        $this->neardSplash->setTextLoading($neardLang->getValue(Lang::SWITCH_VERSION_RESET_SERVICES));
        foreach ($neardBins->getServices() as $sName => $service) {
            $this->neardSplash->incrProgressBar();
            $service->delete();
        }

        $neardWinbinder->messageBoxInfo(
            sprintf($neardLang->getValue(Lang::SWITCH_VERSION_OK_RESTART), $this->bin->getName(), $this->version, APP_TITLE),
            $this->boxTitle);

        $neardCore->setExec(ActionExec::RESTART);

        $neardWinbinder->destroyWindow($window);
    }
}
