<?php

class Lang
{
    // General
    const ALL_RUNNING_HINT = 'allRunningHint';
    const SOME_RUNNING_HINT = 'someRunningHint';
    const NONE_RUNNING_HINT = 'noneRunningHint';
    
    // Single
    const ABOUT = 'about';
    const ALIASES = 'aliases';
    const APPS = 'apps';
    const BINS = 'bins';
    const BITCOIN = 'bitcoin';
    const DEBUG = 'debug';
    const DONATE = 'donate';
    const DONATE_BITCOIN = 'donateBitcoin';
    const DONATE_VIA = 'donateVia';
    const ERROR = 'error';
    const EXECUTABLE = 'executable';
    const EXTENSIONS = 'extensions';
    const GITHUB = 'github';
    const HELP = 'help';
    const HOSTS = 'hosts';
    const LANG = 'lang';
    const LOGS_VERBOSE = 'logsVerbose';
    const MODULES = 'modules';
    const PAYPAL = 'paypal';
    const QUIT = 'quit';
    const RELOAD = 'reload';
    const REPOS = 'repos';
    const RESTART = 'restart';
    const SERVICE = 'service';
    const SETTINGS = 'settings';
    const STARTUP = 'startup';
    const STATUS = 'status';
    const TOOLS = 'tools';
    const VERBOSE_DEBUG = 'verboseDebug';
    const VERBOSE_REPORT = 'verboseReport';
    const VERBOSE_SIMPLE = 'verboseSimple';
    const VERSION = 'version';
    const VERSIONS = 'versions';
    const VIRTUAL_HOSTS = 'virtualHosts';
    const WINDOWS_HOSTS = 'windowsHosts';
    
    // Menu
    const MENU_ABOUT = 'menuAbout';
    const MENU_ACCESS_LOGS = 'menuAccessLogs';
    const MENU_ADD_ALIAS = 'menuAddAlias';
    const MENU_ADD_VHOST = 'menuAddVhost';
    const MENU_CHANGE_PORT = 'menuChangePort';
    const MENU_CHECK_PORT = 'menuCheckPort';
    const MENU_EDIT_ALIAS = 'menuEditAlias';
    const MENU_EDIT_VHOST = 'menuEditVhost';
    const MENU_ERROR_LOGS = 'menuErrorLogs';
    const MENU_INSTALL_SERVICE = 'menuInstallService';
    const MENU_LOCALHOST = 'menuLocalhost';
    const MENU_PUT_OFFLINE = 'menuPutOffline';
    const MENU_PUT_ONLINE = 'menuPutOnline';
    const MENU_REFRESH_REPOS = 'menuRefreshRepos';
    const MENU_REMOVE_SERVICE = 'menuRemoveService';
    const MENU_RESTART_SERVICE = 'menuRestartService';
    const MENU_RESTART_SERVICES = 'menuRestartServices';
    const MENU_START_SERVICE = 'menuStartService';
    const MENU_START_SERVICES = 'menuStartServices';
    const MENU_STOP_SERVICE = 'menuStopService';
    const MENU_STOP_SERVICES = 'menuStopServices';
    const MENU_WWW_DIRECTORY = 'menuWwwDirectory';
    
    // Bins
    const APACHE = 'apache';
    const PHP = 'php';
    const PEAR = 'pear';
    const MYSQL = 'mysql';
    const MARIADB = 'mariadb';
    const NODEJS = 'nodejs';
    
    // Apps
    const GITLIST = 'gitlist';
    const PHPMYADMIN = 'phpmyadmin';
    const WEBGRIND = 'webgrind';
    const WEBSVN = 'websvn';
    
    // Tools
    const CONSOLE = 'console';
    const GIT = 'git';
    const IMAGICK = 'imagick';
    const SVN = 'svn';
    const TCCLE = 'tccle';
    const XDC = 'xdc';
    
    // Init
    const BIN_NOT_FOUND = 'binNotFound';
    const BIN_CONF_NOT_FOUND = 'binConfNotFound';
    const BIN_EXE_NOT_FOUND = 'binExeNotFound';
    
    // Action Switch version
    const SWITCH_VERSION_TITLE = 'switchVersionTitle';
    const SWITCH_VERSION_RELOAD_CONFIG = 'switchVersionReloadConfig';
    const SWITCH_VERSION_RELOAD_BINS = 'switchVersionReloadBins';
    const SWITCH_VERSION_REGISTRY = 'switchVersionRegistry';
    const SWITCH_VERSION_RESET_SERVICES = 'switchVersionResetServices';
    const SWITCH_VERSION_SAME_ERROR = 'switchVersionSameError';
    const SWITCH_VERSION_OK = 'switchVersionOk';
    const SWITCH_VERSION_OK_RESTART = 'switchVersionOkRestart';
    const APACHE_INCPT = 'apacheIncpt';
    const PHP_INCPT = 'phpIncpt';
    const NEARD_CONF_NOT_FOUND_ERROR = 'neardConfNotFoundError';
    const NEARD_CONF_MALFORMED_ERROR = 'neardConfMalformedError';
    
    // Action Check port
    const CHECK_PORT_TITLE = 'checkPortTitle';
    const PORT_USED_BY = 'portUsedBy';
    const PORT_NOT_USED = 'portNotUsed';
    const PORT_NOT_USED_BY = 'portNotUsedBy';
    const PORT_USED_BY_ANOTHER_DBMS = 'portUsedByAnotherDbms';
    const PORT_CHANGED = 'portChanged';
    
    // Action Install service
    const INSTALL_SERVICE_TITLE = 'installServiceTitle';
    const SERVICE_ALREADY_INSTALLED = 'serviceAlreadyInstalled';
    const SERVICE_INSTALLED = 'serviceInstalled';
    const SERVICE_INSTALL_ERROR = 'serviceInstallError';
    
    // Action Remove service
    const REMOVE_SERVICE_TITLE = 'removeServiceTitle';
    const SERVICE_NOT_EXIST = 'serviceNotExist';
    const SERVICE_REMOVED = 'serviceRemoved';
    const SERVICE_REMOVE_ERROR = 'serviceRemoveError';
    
    // Action Delete alias
    const DELETE_ALIAS_TITLE = 'deleteAliasTitle';
    const DELETE_ALIAS = 'deleteAlias';
    const ALIAS_REMOVED = 'aliasRemoved';
    const ALIAS_REMOVE_ERROR = 'aliasRemoveError';
    
    // Action Add/Edit alias
    const ADD_ALIAS_TITLE = 'addAliasTitle';
    const ALIAS_NAME_LABEL = 'aliasNameLabel';
    const ALIAS_DEST_LABEL = 'aliasDestLabel';
    const ALIAS_EXP_LABEL = 'aliasExpLabel';
    const ALIAS_DEST_PATH = 'aliasDestPath';
    const ALIAS_ALREADY_EXISTS = 'aliasAlreadyExists';
    const ALIAS_CREATED = 'aliasCreated';
    const ALIAS_CREATED_ERROR = 'aliasCreatedError';
    const EDIT_ALIAS_TITLE = 'editAliasTitle';
    
    // Action Delete vhost
    const DELETE_VHOST_TITLE =  'deleteVhostTitle';
    const DELETE_VHOST = 'deleteVhost';
    const VHOST_REMOVED = 'vhostRemoved';
    const VHOST_REMOVE_ERROR = 'vhostRemoveError';
    
    // Action Add/Edit vhost
    const ADD_VHOST_TITLE = 'addVhostTitle';
    const VHOST_SERVER_NAME_LABEL = 'vhostServerNameLabel';
    const VHOST_DOCUMENT_ROOT_LABEL = 'vhostDocumentRootLabel';
    const VHOST_EXP_LABEL = 'vhostExpLabel';
    const VHOST_DOC_ROOT_PATH = 'vhostDocRootPath';
    const VHOST_ALREADY_EXISTS = 'vhostAlreadyExists';
    const VHOST_CREATED = 'vhostCreated';
    const VHOST_CREATED_ERROR = 'vhostCreatedError';
    const EDIT_VHOST_TITLE = 'editVhostTitle';
    
    // Action Change port
    const CHANGE_PORT_TITLE = 'changePortTitle';
    const CHANGE_PORT_CURRENT_LABEL = 'changePortCurrentLabel';
    const CHANGE_PORT_NEW_LABEL = 'changePortNewLabel';
    const CHANGE_PORT_SAME_ERROR = 'changePortSameError';
    
    // Action Startup
    const STARTUP_STARTING_TEXT = 'startupStartingText';
    const STARTUP_KILL_PHP_PROCS_TEXT = 'startupKillPhpProcsText';
    const STARTUP_REFRESH_HOSTNAME_TEXT = 'startupRefreshHostnameText';
    const STARTUP_CHECK_BROWSER_TEXT = 'startupCheckBrowserText';
    const STARTUP_CLEAR_TMP_FOLDERS_TEXT = 'startupClearTmpFoldersText';
    const STARTUP_PURGE_LOGS_TEXT = 'startupPurgeLogsText';
    const STARTUP_REFRESH_ALIAS_HOMEPAGE_TEXT = 'startupRefreshAliasHomepageText';
    const STARTUP_CHECK_PATH_TEXT = 'startupCheckPathText';
    const STARTUP_SCAN_FOLDERS_TEXT = 'startupScanFoldersText';
    const STARTUP_CHANGE_OLD_PATHS_TEXT = 'startupChangeOldPathsText';
    const STARTUP_REGISTRY_TEXT = 'startupRegistryText';
    const STARTUP_REGISTRY_ERROR_TEXT = 'startupRegistryErrorText';
    const STARTUP_INSTALL_SERVICE_TEXT = 'startupInstallServiceText';
    const STARTUP_START_SERVICE_TEXT = 'startupStartServiceText';
    const STARTUP_PREPARE_RESTART_TEXT = 'startupPrepareRestartText';
    const STARTUP_RESTARTING_TEXT = 'startupRestartingText';
    const STARTUP_ERROR_TITLE = 'startupErrorTitle';
    const STARTUP_SERVICE_ERROR = 'startupServiceError';
    const STARTUP_PORT_ERROR = 'startupPortError';
    const STARTUP_SERVICE_CREATE_ERROR = 'startupServiceCreateError';
    const STARTUP_SERVICE_START_ERROR = 'startupServiceStartError';
    const STARTUP_SERVICE_APACHE_ERROR = 'startupServiceApacheError';
    const STARTUP_SERVICE_PORT_ERROR = 'startupServicePortError';
    const STARTUP_REFRESH_GIT_REPOS_TEXT = 'startupRefreshGitReposText';
    const STARTUP_REFRESH_SVN_REPOS_TEXT = 'startupRefreshSvnReposText';
    
    // Action Quit
    const EXIT_LEAVING_TEXT = 'exitLeavingText';
    const EXIT_REMOVE_SERVICE_TEXT = 'exitRemoveServiceText';
    
    // Action Change browser
    const CHANGE_BROWSER_TITLE = 'changeBrowserTitle';
    const CHANGE_BROWSER_EXP_LABEL = 'changeBrowserExpLabel';
    const CHANGE_BROWSER_OTHER_LABEL = 'changeBrowserOtherLabel';
    const CHANGE_BROWSER_OK = 'changeBrowserOk';
    
    // Action About
    const ABOUT_TITLE = 'aboutTitle';
    const ABOUT_TEXT = 'aboutText';
    
    // Action Debug Apache
    const DEBUG_APACHE_VERSION_NUMBER = 'debugApacheVersionNumber';
    const DEBUG_APACHE_COMPILE_SETTINGS = 'debugApacheCompileSettings';
    const DEBUG_APACHE_COMPILED_MODULES = 'debugApacheCompiledModules';
    const DEBUG_APACHE_CONFIG_DIRECTIVES = 'debugApacheConfigDirectives';
    const DEBUG_APACHE_VHOSTS_SETTINGS = 'debugApacheVhostsSettings';
    const DEBUG_APACHE_LOADED_MODULES = 'debugApacheLoadedModules';
    const DEBUG_APACHE_SYNTAX_CHECK = 'debugApacheSyntaxCheck';
    
    // Action others...
    const REGISTRY_SET_ERROR_TEXT = 'registrySetErrorText';
    
    // Windows forms
    const BUTTON_OK = 'buttonOk';
    const BUTTON_DELETE = 'buttonDelete';
    const BUTTON_SAVE = 'buttonSave';
    const BUTTON_FINISH = 'buttonFinish';
    const BUTTON_CANCEL = 'buttonCancel';
    const BUTTON_NEXT = 'buttonNext';
    const BUTTON_BACK = 'buttonBack';
    const BUTTON_BROWSE = 'buttonBrowse';
    const LOADING = 'loading';
    
    // Homepage
    const HOMEPAGE_OFFICIAL_WEBSITE = 'homepageOfficialWebsite';
    const HOMEPAGE_SERVICE_STARTED = 'homepageServiceStarted';
    const HOMEPAGE_SERVICE_STOPPED = 'homepageServiceStopped';
    const HOMEPAGE_ABOUT_TEXT = 'homepageAboutText';
    const HOMEPAGE_QUESTIONS_TITLE = 'homepageQuestionsTitle';
    const HOMEPAGE_QUESTIONS_TEXT = 'homepageQuestionsText';
    const HOMEPAGE_POST_ISSUE = 'homepagePostIssue';
    const HOMEPAGE_DONATE_TEXT = 'homepageDonateText';
    const HOMEPAGE_PHPINFO_TEXT = 'homepagePhpinfoText';
    const HOMEPAGE_BACK_TEXT = 'homepageBackText';
    
    private $current;
    private $raw;
    
    public function __construct()
    {
        $this->load();
    }

    public function load()
    {
        global $neardCore, $neardConfig;
        $this->raw = null;
        
        $this->current = $neardConfig->getDefaultLang();
        if (!empty($this->current) && in_array($this->current, $this->getList())) {
            $this->current = $neardConfig->getLang();
        }
        
        $this->raw = parse_ini_file($neardCore->getLangsPath() . '/' . $this->current . '.lng');
    }
    
    public function getCurrent()
    {
        return $this->current;
    }
    
    public function getList()
    {
        global $neardCore;
        $result = array();
    
        if ($handle = opendir($neardCore->getLangsPath())) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && Util::endWith($file, '.lng')) {
                    $result[] = str_replace('.lng', '', $file);
                }
            }
            closedir($handle);
        }
    
        return $result;
    }

    public function getValue($key)
    {
        return $this->raw[$key];
    }

}
