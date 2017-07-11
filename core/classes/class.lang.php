<?php

class Lang
{
    // General
    const ALL_RUNNING_HINT = 'allRunningHint';
    const SOME_RUNNING_HINT = 'someRunningHint';
    const NONE_RUNNING_HINT = 'noneRunningHint';
    
    // Single
    const ABOUT = 'about';
    const ADMINISTRATION = 'administration';
    const ALIASES = 'aliases';
    const APPS = 'apps';
    const BINS = 'bins';
    const CHANGELOG = 'changelog';
    const DEBUG = 'debug';
    const DISABLED = 'disabled';
    const DONATE = 'donate';
    const DONATE_VIA = 'donateVia';
    const DOWNLOAD = 'download';
    const DOWNLOAD_MORE = 'downloadMore';
    const ENABLED = 'enabled';
    const ERROR = 'error';
    const EXECUTABLE = 'executable';
    const EXTENSIONS = 'extensions';
    const GIT_CONSOLE = 'gitConsole';
    const GITHUB = 'github';
    const HELP = 'help';
    const LANG = 'lang';
    const LICENSE = 'license';
    const LOGS = 'logs';
    const LOGS_VERBOSE = 'logsVerbose';
    const MODULES = 'modules';
    const NAME = 'name';
    const PAYPAL = 'paypal';
    const PYTHON_CONSOLE = 'pythonConsole';
    const PYTHON_CP = 'pythonCp';
    const QUIT = 'quit';
    const READ_CHANGELOG = 'readChangelog';
    const RELOAD = 'reload';
    const REPOS = 'repos';
    const RESTART = 'restart';
    const SERVICE = 'service';
    const SETTINGS = 'settings';
    const SSL = 'ssl';
    const STARTUP = 'startup';
    const STATUS = 'status';
    const STATUS_PAGE = 'statusPage';
    const SVN_CONSOLE = 'svnConsole';
    const TARGET = 'target';
    const TOOLS = 'tools';
    const VERBOSE_DEBUG = 'verboseDebug';
    const VERBOSE_REPORT = 'verboseReport';
    const VERBOSE_SIMPLE = 'verboseSimple';
    const VERBOSE_TRACE = 'verboseTrace';
    const VERSION = 'version';
    const VERSIONS = 'versions';
    const VIRTUAL_HOSTS = 'virtualHosts';
    const WEBSITE = 'website';
    
    // Menu
    const MENU_ABOUT = 'menuAbout';
    const MENU_ACCESS_LOGS = 'menuAccessLogs';
    const MENU_ADD_ALIAS = 'menuAddAlias';
    const MENU_ADD_VHOST = 'menuAddVhost';
    const MENU_CHANGE_PORT = 'menuChangePort';
    const MENU_CHANGE_ROOT_PWD = 'menuChangeRootPwd';
    const MENU_CHECK_PORT = 'menuCheckPort';
    const MENU_CHECK_UPDATE = 'menuCheckUpdate';
    const MENU_CLEAR_FOLDERS = 'menuClearFolders';
    const MENU_EDIT_ALIAS = 'menuEditAlias';
    const MENU_EDIT_VHOST = 'menuEditVhost';
    const MENU_ENABLE = 'menuEnable';
    const MENU_ERROR_LOGS = 'menuErrorLogs';
    const MENU_GEN_SSL_CERTIFICATE = 'menuGenSslCertificate';
    const MENU_INSTALL_SERVICE = 'menuInstallService';
    const MENU_LAUNCH_STARTUP = 'menuLaunchStartup';
    const MENU_LOCALHOST = 'menuLocalhost';
    const MENU_LOGS = 'menuLogs';
    const MENU_PUT_OFFLINE = 'menuPutOffline';
    const MENU_PUT_ONLINE = 'menuPutOnline';
    const MENU_REFRESH_REPOS = 'menuRefreshRepos';
    const MENU_REMOVE_SERVICE = 'menuRemoveService';
    const MENU_RESTART_SERVICE = 'menuRestartService';
    const MENU_RESTART_SERVICES = 'menuRestartServices';
    const MENU_REWRITE_LOGS = 'menuRewriteLogs';
    const MENU_SCAN_REPOS_STARTUP = 'menuScanReposStartup';
    const MENU_SESSION_LOGS = 'menuSessionLogs';
    const MENU_START_SERVICE = 'menuStartService';
    const MENU_START_SERVICES = 'menuStartServices';
    const MENU_STATS_LOGS = 'menuStatsLogs';
    const MENU_STOP_SERVICE = 'menuStopService';
    const MENU_STOP_SERVICES = 'menuStopServices';
    const MENU_TRANSFER_LOGS = 'menuTransferLogs';
    const MENU_UPDATE_ENV_PATH = 'menuUpdateEnvPath';
    const MENU_WWW_DIRECTORY = 'menuWwwDirectory';
    
    // Bins
    const APACHE = 'apache';
    const FILEZILLA = 'filezilla';
    const PHP = 'php';
    const PEAR = 'pear';
    const MEMCACHED = 'memcached';
    const MAILHOG = 'mailhog';
    const MARIADB = 'mariadb';
    const MONGODB = 'mongodb';
    const MYSQL = 'mysql';
    const NODEJS = 'nodejs';
    const POSTGRESQL = 'postgresql';
    const SVN = 'svn';
    
    // Apps
    const GITLIST = 'gitlist';
    const PHPMYADMIN = 'phpmyadmin';
    const WEBGRIND = 'webgrind';
    const WEBSVN = 'websvn';
    const ADMINER = 'adminer';
    const PHPMEMADMIN = 'phpmemadmin';
    const PHPPGADMIN = 'phppgadmin';
    
    // Tools
    const COMPOSER = 'composer';
    const CONSOLE = 'console';
    const DRUSH = 'drush';
    const GIT = 'git';
    const HOSTSEDITOR = 'hostseditor';
    const IMAGEMAGICK = 'imagemagick';
    const NOTEPAD2MOD = 'notepad2mod';
    const PERL = 'perl';
    const PHPMETRICS = 'phpmetrics';
    const PHPUNIT = 'phpunit';
    const PYTHON = 'python';
    const RUBY = 'ruby';
    const WPCLI = 'wpcli';
    const XDC = 'xdc';
    const YARN = 'yarn';
    
    // Errors
    const ERROR_CONF_NOT_FOUND = 'errorConfNotFound';
    const ERROR_EXE_NOT_FOUND = 'errorExeNotFound';
    const ERROR_FILE_NOT_FOUND = 'errorFileNotFound';
    const ERROR_INVALID_PARAMETER = 'errorInvalidParameter';
    
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
    
    // Action Switch PHP setting
    const SWITCH_PHP_SETTING_TITLE = 'switchPhpSettingTitle';
    const SWITCH_PHP_SETTING_NOT_FOUND = 'switchPhpSettingNotFound';
    
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
    
    // Action Start service
    const START_SERVICE_TITLE = 'startServiceTitle';
    const START_SERVICE_ERROR = 'startServiceError';
    
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
    const ALIAS_NOT_VALID_ALPHA = 'aliasNotValidAlpha';
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
    const VHOST_NOT_VALID_DOMAIN = 'vhostNotValidDomain';
    const VHOST_ALREADY_EXISTS = 'vhostAlreadyExists';
    const VHOST_CREATED = 'vhostCreated';
    const VHOST_CREATED_ERROR = 'vhostCreatedError';
    const EDIT_VHOST_TITLE = 'editVhostTitle';
    
    // Action Change port
    const CHANGE_PORT_TITLE = 'changePortTitle';
    const CHANGE_PORT_CURRENT_LABEL = 'changePortCurrentLabel';
    const CHANGE_PORT_NEW_LABEL = 'changePortNewLabel';
    const CHANGE_PORT_SAME_ERROR = 'changePortSameError';
    
    // Action Change database root password
    const CHANGE_DB_ROOT_PWD_TITLE = 'changeDbRootPwdTitle';
    const CHANGE_DB_ROOT_PWD_CURRENTPWD_LABEL = 'changeDbRootPwdCurrentpwdLabel';
    const CHANGE_DB_ROOT_PWD_NEWPWD1_LABEL = 'changeDbRootPwdNewpwd1Label';
    const CHANGE_DB_ROOT_PWD_NEWPWD2_LABEL = 'changeDbRootPwdNewpwd2Label';
    const CHANGE_DB_ROOT_PWD_NOTSAME_ERROR = 'changeDbRootPwdNotsameError';
    const CHANGE_DB_ROOT_PWD_INCORRECT_ERROR = 'changeDbRootPwdIncorrectError';
    const CHANGE_DB_ROOT_PWD_TEXT = 'changeDbRootPwdText';
    
    // Action Startup
    const STARTUP_STARTING_TEXT = 'startupStartingText';
    const STARTUP_ROTATION_LOGS_TEXT = 'startupRotationLogsText';
    const STARTUP_KILL_OLD_PROCS_TEXT = 'startupKillOldProcsText';
    const STARTUP_REFRESH_HOSTNAME_TEXT = 'startupRefreshHostnameText';
    const STARTUP_CHECK_BROWSER_TEXT = 'startupCheckBrowserText';
    const STARTUP_CLEAN_TMP_TEXT = 'startupCleanTmpText';
    const STARTUP_CLEAN_OLD_BEAHAVIORS_TEXT = 'startupCleanOldBehaviorsText';
    const STARTUP_REFRESH_ALIAS_TEXT = 'startupRefreshAliasText';
    const STARTUP_REFRESH_VHOSTS_TEXT = 'startupRefreshVhostsText';
    const STARTUP_CHECK_PATH_TEXT = 'startupCheckPathText';
    const STARTUP_SCAN_FOLDERS_TEXT = 'startupScanFoldersText';
    const STARTUP_CHANGE_PATH_TEXT = 'startupChangePathText';
    const STARTUP_REGISTRY_TEXT = 'startupRegistryText';
    const STARTUP_REGISTRY_ERROR_TEXT = 'startupRegistryErrorText';
    const STARTUP_UPDATE_CONFIG_TEXT = 'startupUpdateConfigText';
    const STARTUP_CHECK_SERVICE_TEXT = 'startupCheckServiceText';
    const STARTUP_INSTALL_SERVICE_TEXT = 'startupInstallServiceText';
    const STARTUP_START_SERVICE_TEXT = 'startupStartServiceText';
    const STARTUP_PREPARE_RESTART_TEXT = 'startupPrepareRestartText';
    const STARTUP_ERROR_TITLE = 'startupErrorTitle';
    const STARTUP_SERVICE_ERROR = 'startupServiceError';
    const STARTUP_SERVICE_CREATE_ERROR = 'startupServiceCreateError';
    const STARTUP_SERVICE_START_ERROR = 'startupServiceStartError';
    const STARTUP_SERVICE_SYNTAX_ERROR = 'startupServiceSyntaxError';
    const STARTUP_SERVICE_PORT_ERROR = 'startupServicePortError';
    const STARTUP_REFRESH_GIT_REPOS_TEXT = 'startupRefreshGitReposText';
    const STARTUP_GEN_SSL_CRT_TEXT = 'startupGenSslCrtText';
    
    // Action Quit
    const EXIT_LEAVING_TEXT = 'exitLeavingText';
    const EXIT_REMOVE_SERVICE_TEXT = 'exitRemoveServiceText';
    const EXIT_STOP_OTHER_PROCESS_TEXT = 'exitStopOtherProcessText';
    
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
    
    // Action Debug MySQL
    const DEBUG_MYSQL_VERSION = 'debugMysqlVersion';
    const DEBUG_MYSQL_VARIABLES = 'debugMysqlVariables';
    const DEBUG_MYSQL_SYNTAX_CHECK = 'debugMysqlSyntaxCheck';
    
    // Action Debug MariaDB
    const DEBUG_MARIADB_VERSION = 'debugMariadbVersion';
    const DEBUG_MARIADB_VARIABLES = 'debugMariadbVariables';
    const DEBUG_MARIADB_SYNTAX_CHECK = 'debugMariadbSyntaxCheck';
    
    // Action Debug MongoDB
    const DEBUG_MONGODB_VERSION = 'debugMongodbVersion';
    
    // Action Debug PostgreSQL
    const DEBUG_POSTGRESQL_VERSION = 'debugPostgresqlVersion';
    
    // Action Debug SVN
    const DEBUG_SVN_VERSION = 'debugSvnVersion';
    
    // Action others...
    const REGISTRY_SET_ERROR_TEXT = 'registrySetErrorText';
    
    // Action check version
    const CHECK_VERSION_TITLE = 'checkVersionTitle';
    const CHECK_VERSION_AVAILABLE_TEXT = 'checkVersionAvailableText';
    const CHECK_VERSION_CHANGELOG_TEXT = 'checkVersionChangelogText';
    const CHECK_VERSION_LATEST_TEXT = 'checkVersionLatestText';
    
    // Action gen SSL certificate
    const GENSSL_TITLE = 'genSslTitle';
    const GENSSL_PATH = 'genSslPath';
    const GENSSL_CREATED = 'genSslCreated';
    const GENSSL_CREATED_ERROR = 'genSslCreatedError';
    
    // Action restart
    const RESTART_TITLE = 'restartTitle';
    const RESTART_TEXT = 'restartText';
    
    // Action enable
    const ENABLE_TITLE = 'enableTitle';
    const ENABLE_BUNDLE_NOT_EXIST = 'enableBundleNotExist';
    
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
    const HOMEPAGE_ABOUT_HTML = 'homepageAboutHtml';
    const HOMEPAGE_LICENSE_TEXT = 'homepageLicenseText';
    const HOMEPAGE_QUESTIONS_TITLE = 'homepageQuestionsTitle';
    const HOMEPAGE_QUESTIONS_TEXT = 'homepageQuestionsText';
    const HOMEPAGE_POST_ISSUE = 'homepagePostIssue';
    const HOMEPAGE_PHPINFO_TEXT = 'homepagePhpinfoText';
    const HOMEPAGE_APC_TEXT = 'homepageApcText';
    const HOMEPAGE_MAILHOG_TEXT = 'homepageMailhogText';
    const HOMEPAGE_BACK_TEXT = 'homepageBackText';
    
    public static function getKeys()
    {
        return array(
            // General
            self::ALL_RUNNING_HINT,
            self::SOME_RUNNING_HINT,
            self::NONE_RUNNING_HINT,
            
            // Single
            self::ABOUT,
            self::ADMINISTRATION,
            self::ALIASES,
            self::APPS,
            self::BINS,
            self::CHANGELOG,
            self::DEBUG,
            self::DISABLED,
            self::DONATE,
            self::DONATE_VIA,
            self::DOWNLOAD,
            self::DOWNLOAD_MORE,
            self::ENABLED,
            self::ERROR,
            self::EXECUTABLE,
            self::EXTENSIONS,
            self::GIT_CONSOLE,
            self::GITHUB,
            self::HELP,
            self::LANG,
            self::LICENSE,
            self::LOGS,
            self::LOGS_VERBOSE,
            self::MODULES,
            self::NAME,
            self::PAYPAL,
            self::PYTHON_CONSOLE,
            self::PYTHON_CP,
            self::QUIT,
            self::READ_CHANGELOG,
            self::RELOAD,
            self::REPOS,
            self::RESTART,
            self::SERVICE,
            self::SETTINGS,
            self::SSL,
            self::STARTUP,
            self::STATUS,
            self::STATUS_PAGE,
            self::SVN_CONSOLE,
            self::TARGET,
            self::TOOLS,
            self::VERBOSE_DEBUG,
            self::VERBOSE_REPORT,
            self::VERBOSE_SIMPLE,
            self::VERBOSE_TRACE,
            self::VERSION,
            self::VERSIONS,
            self::VIRTUAL_HOSTS,
            self::WEBSITE,
            
            // Menu
            self::MENU_ABOUT,
            self::MENU_ACCESS_LOGS,
            self::MENU_ADD_ALIAS,
            self::MENU_ADD_VHOST,
            self::MENU_CHANGE_PORT,
            self::MENU_CHANGE_ROOT_PWD,
            self::MENU_CHECK_PORT,
            self::MENU_CHECK_UPDATE,
            self::MENU_CLEAR_FOLDERS,
            self::MENU_EDIT_ALIAS,
            self::MENU_EDIT_VHOST,
            self::MENU_ENABLE,
            self::MENU_ERROR_LOGS,
            self::MENU_GEN_SSL_CERTIFICATE,
            self::MENU_INSTALL_SERVICE,
            self::MENU_LAUNCH_STARTUP,
            self::MENU_LOCALHOST,
            self::MENU_LOGS,
            self::MENU_PUT_OFFLINE,
            self::MENU_PUT_ONLINE,
            self::MENU_REFRESH_REPOS,
            self::MENU_REMOVE_SERVICE,
            self::MENU_RESTART_SERVICE,
            self::MENU_RESTART_SERVICES,
            self::MENU_REWRITE_LOGS,
            self::MENU_SCAN_REPOS_STARTUP,
            self::MENU_SESSION_LOGS,
            self::MENU_START_SERVICE,
            self::MENU_START_SERVICES,
            self::MENU_STATS_LOGS,
            self::MENU_STOP_SERVICE,
            self::MENU_STOP_SERVICES,
            self::MENU_TRANSFER_LOGS,
            self::MENU_UPDATE_ENV_PATH,
            self::MENU_WWW_DIRECTORY,
            
            // Bins
            self::APACHE,
            self::FILEZILLA,
            self::PHP,
            self::PEAR,
            self::MEMCACHED,
            self::MAILHOG,
            self::MARIADB,
            self::MONGODB,
            self::MYSQL,
            self::NODEJS,
            self::POSTGRESQL,
            self::SVN,
            
            // Apps
            self::GITLIST,
            self::PHPMYADMIN,
            self::WEBGRIND,
            self::WEBSVN,
            self::ADMINER,
            self::PHPMEMADMIN,
            self::PHPPGADMIN,
            
            // Tools
            self::COMPOSER,
            self::CONSOLE,
            self::DRUSH,
            self::GIT,
            self::HOSTSEDITOR,
            self::IMAGEMAGICK,
            self::NOTEPAD2MOD,
            self::PHPMETRICS,
            self::PHPUNIT,
            self::PYTHON,
            self::RUBY,
            self::WPCLI,
            self::XDC,
            
            // Errors
            self::ERROR_CONF_NOT_FOUND,
            self::ERROR_EXE_NOT_FOUND,
            self::ERROR_FILE_NOT_FOUND,
            self::ERROR_INVALID_PARAMETER,
            
            // Action Switch version
            self::SWITCH_VERSION_TITLE,
            self::SWITCH_VERSION_RELOAD_CONFIG,
            self::SWITCH_VERSION_RELOAD_BINS,
            self::SWITCH_VERSION_REGISTRY,
            self::SWITCH_VERSION_RESET_SERVICES,
            self::SWITCH_VERSION_SAME_ERROR,
            self::SWITCH_VERSION_OK,
            self::SWITCH_VERSION_OK_RESTART,
            self::APACHE_INCPT,
            self::PHP_INCPT,
            self::NEARD_CONF_NOT_FOUND_ERROR,
            self::NEARD_CONF_MALFORMED_ERROR,
            
            // Action Switch PHP setting
            self::SWITCH_PHP_SETTING_TITLE,
            self::SWITCH_PHP_SETTING_NOT_FOUND,
            
            // Action Check port
            self::CHECK_PORT_TITLE,
            self::PORT_USED_BY,
            self::PORT_NOT_USED,
            self::PORT_NOT_USED_BY,
            self::PORT_USED_BY_ANOTHER_DBMS,
            self::PORT_CHANGED,
            
            // Action Install service
            self::INSTALL_SERVICE_TITLE,
            self::SERVICE_ALREADY_INSTALLED,
            self::SERVICE_INSTALLED,
            self::SERVICE_INSTALL_ERROR,
            
            // Action Remove service
            self::REMOVE_SERVICE_TITLE,
            self::SERVICE_NOT_EXIST,
            self::SERVICE_REMOVED,
            self::SERVICE_REMOVE_ERROR,
            
            // Action Start service
            self::START_SERVICE_TITLE,
            
            // Action Delete alias
            self::DELETE_ALIAS_TITLE,
            self::DELETE_ALIAS,
            self::ALIAS_REMOVED,
            self::ALIAS_REMOVE_ERROR,
            
            // Action Add/Edit alias
            self::ADD_ALIAS_TITLE,
            self::ALIAS_NAME_LABEL,
            self::ALIAS_DEST_LABEL,
            self::ALIAS_EXP_LABEL,
            self::ALIAS_DEST_PATH,
            self::ALIAS_NOT_VALID_ALPHA,
            self::ALIAS_ALREADY_EXISTS,
            self::ALIAS_CREATED,
            self::ALIAS_CREATED_ERROR,
            self::EDIT_ALIAS_TITLE,
            
            // Action Delete vhost
            self::DELETE_VHOST_TITLE,
            self::DELETE_VHOST,
            self::VHOST_REMOVED,
            self::VHOST_REMOVE_ERROR,
            
            // Action Add/Edit vhost
            self::ADD_VHOST_TITLE,
            self::VHOST_SERVER_NAME_LABEL,
            self::VHOST_DOCUMENT_ROOT_LABEL,
            self::VHOST_EXP_LABEL,
            self::VHOST_DOC_ROOT_PATH,
            self::VHOST_NOT_VALID_DOMAIN,
            self::VHOST_ALREADY_EXISTS,
            self::VHOST_CREATED,
            self::VHOST_CREATED_ERROR,
            self::EDIT_VHOST_TITLE,
            
            // Action Change port
            self::CHANGE_PORT_TITLE,
            self::CHANGE_PORT_CURRENT_LABEL,
            self::CHANGE_PORT_NEW_LABEL,
            self::CHANGE_PORT_SAME_ERROR,
            
            // Action Change database root password
            self::CHANGE_DB_ROOT_PWD_TITLE,
            self::CHANGE_DB_ROOT_PWD_CURRENTPWD_LABEL,
            self::CHANGE_DB_ROOT_PWD_NEWPWD1_LABEL,
            self::CHANGE_DB_ROOT_PWD_NEWPWD2_LABEL,
            self::CHANGE_DB_ROOT_PWD_NOTSAME_ERROR,
            self::CHANGE_DB_ROOT_PWD_INCORRECT_ERROR,
            self::CHANGE_DB_ROOT_PWD_TEXT,
            
            // Action Startup
            self::STARTUP_STARTING_TEXT,
            self::STARTUP_ROTATION_LOGS_TEXT,
            self::STARTUP_KILL_OLD_PROCS_TEXT,
            self::STARTUP_REFRESH_HOSTNAME_TEXT,
            self::STARTUP_CHECK_BROWSER_TEXT,
            self::STARTUP_CLEAN_TMP_TEXT,
            self::STARTUP_CLEAN_OLD_BEAHAVIORS_TEXT,
            self::STARTUP_REFRESH_ALIAS_TEXT,
            self::STARTUP_REFRESH_VHOSTS_TEXT,
            self::STARTUP_CHECK_PATH_TEXT,
            self::STARTUP_SCAN_FOLDERS_TEXT,
            self::STARTUP_CHANGE_PATH_TEXT,
            self::STARTUP_REGISTRY_TEXT,
            self::STARTUP_REGISTRY_ERROR_TEXT,
            self::STARTUP_UPDATE_CONFIG_TEXT,
            self::STARTUP_CHECK_SERVICE_TEXT,
            self::STARTUP_INSTALL_SERVICE_TEXT,
            self::STARTUP_START_SERVICE_TEXT,
            self::STARTUP_PREPARE_RESTART_TEXT,
            self::STARTUP_ERROR_TITLE,
            self::STARTUP_SERVICE_ERROR,
            self::STARTUP_SERVICE_CREATE_ERROR,
            self::STARTUP_SERVICE_START_ERROR,
            self::STARTUP_SERVICE_SYNTAX_ERROR,
            self::STARTUP_SERVICE_PORT_ERROR,
            self::STARTUP_REFRESH_GIT_REPOS_TEXT,
            self::STARTUP_START_SERVICE_TEXT,
            
            // Action Quit
            self::EXIT_LEAVING_TEXT,
            self::EXIT_REMOVE_SERVICE_TEXT,
            self::EXIT_STOP_OTHER_PROCESS_TEXT,
            
            // Action Change browser
            self::CHANGE_BROWSER_TITLE,
            self::CHANGE_BROWSER_EXP_LABEL,
            self::CHANGE_BROWSER_OTHER_LABEL,
            self::CHANGE_BROWSER_OK,
            
            // Action About
            self::ABOUT_TITLE,
            self::ABOUT_TEXT,
            
            // Action Debug Apache
            self::DEBUG_APACHE_VERSION_NUMBER,
            self::DEBUG_APACHE_COMPILE_SETTINGS,
            self::DEBUG_APACHE_COMPILED_MODULES,
            self::DEBUG_APACHE_CONFIG_DIRECTIVES,
            self::DEBUG_APACHE_VHOSTS_SETTINGS,
            self::DEBUG_APACHE_LOADED_MODULES,
            self::DEBUG_APACHE_SYNTAX_CHECK,
            
            // Action Debug MySQL
            self::DEBUG_MYSQL_VERSION,
            self::DEBUG_MYSQL_VARIABLES,
            self::DEBUG_MYSQL_SYNTAX_CHECK,
            
            // Action Debug MariaDB
            self::DEBUG_MARIADB_VERSION,
            self::DEBUG_MARIADB_VARIABLES,
            self::DEBUG_MARIADB_SYNTAX_CHECK,
            
            // Action Debug MongoDB
            self::DEBUG_MONGODB_VERSION,
            
            // Action Debug PostgreSQL
            self::DEBUG_POSTGRESQL_VERSION,
            
            // Action Debug SVN
            self::DEBUG_SVN_VERSION,
            
            // Action others...
            self::REGISTRY_SET_ERROR_TEXT,
            
            // Action check version
            self::CHECK_VERSION_TITLE,
            self::CHECK_VERSION_AVAILABLE_TEXT,
            self::CHECK_VERSION_CHANGELOG_TEXT,
            self::CHECK_VERSION_LATEST_TEXT,
                
            // Action gen SSL certificate
            self::GENSSL_TITLE,
            self::GENSSL_PATH,
            self::GENSSL_CREATED,
            self::GENSSL_CREATED_ERROR,
            
            // Action restart
            self::RESTART_TITLE,
            self::RESTART_TEXT,
            
            // Action enable
            self::ENABLE_TITLE,
            self::ENABLE_BUNDLE_NOT_EXIST,
            
            // Windows forms
            self::BUTTON_OK,
            self::BUTTON_DELETE,
            self::BUTTON_SAVE,
            self::BUTTON_FINISH,
            self::BUTTON_CANCEL,
            self::BUTTON_NEXT,
            self::BUTTON_BACK,
            self::BUTTON_BROWSE,
            self::LOADING,
            
            // Homepage
            self::HOMEPAGE_OFFICIAL_WEBSITE,
            self::HOMEPAGE_SERVICE_STARTED,
            self::HOMEPAGE_SERVICE_STOPPED,
            self::HOMEPAGE_ABOUT_HTML,
            self::HOMEPAGE_LICENSE_TEXT,
            self::HOMEPAGE_QUESTIONS_TITLE,
            self::HOMEPAGE_QUESTIONS_TEXT,
            self::HOMEPAGE_POST_ISSUE,
            self::HOMEPAGE_PHPINFO_TEXT,
            self::HOMEPAGE_APC_TEXT,
            self::HOMEPAGE_MAILHOG_TEXT,
            self::HOMEPAGE_BACK_TEXT,
        );
    }
}
