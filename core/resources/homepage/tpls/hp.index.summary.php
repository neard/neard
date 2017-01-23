<div class="row summary">
  <div class="col-lg-4">
    <div class="list-group">
      <div class="list-group-item" style="min-height:150px">
        <h4 class="list-group-item-heading"><?php echo $neardLang->getValue(Lang::ABOUT); ?></h4>
        <p class="list-group-item-text"><?php echo sprintf($neardLang->getValue(Lang::HOMEPAGE_ABOUT_HTML), APP_WEBSITE . '/' . Util::getUtmSource(), Util::getGithubUrl()); ?></p>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="list-group">
      <div class="list-group-item" style="min-height:150px">
        <h4 class="list-group-item-heading"><?php echo $neardLang->getValue(Lang::LICENSE); ?></h4>
        <p class="list-group-item-text"><?php echo $neardLang->getValue(Lang::HOMEPAGE_LICENSE_TEXT); ?></p>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="list-group">
      <div class="list-group-item" style="min-height:150px">
        <h4 class="list-group-item-heading"><?php echo $neardLang->getValue(Lang::HOMEPAGE_QUESTIONS_TITLE); ?></h4>
        <div class="list-group-item-text">
          <p><?php echo $neardLang->getValue(Lang::HOMEPAGE_QUESTIONS_TEXT); ?></p>
          <p><a target="_blank" href="<?php echo Util::getGithubUrl('issues'); ?>" class="btn btn-primary" role="button"><i class="fa fa-github"></i> <?php echo $neardLang->getValue(Lang::HOMEPAGE_POST_ISSUE); ?></a></p>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row" style="margin-top:20px;">
  <div class="col-lg-4">
    <div style="min-height:250px;" class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-gear"></i> <?php echo $neardLang->getValue(Lang::BINS); ?></h3>
      </div>
      <div class="panel-body panel-summary">
        <div class="list-group" style="margin-bottom:0;">
          <span class="list-group-item summary-binapache">
            <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
            <a href="#apache"><?php echo $neardLang->getValue(Lang::APACHE); ?></a>
          </span>
          <span class="list-group-item summary-binphp">
            <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
            <a href="#php"><?php echo $neardLang->getValue(Lang::PHP); ?></a>
          </span>
          <span class="list-group-item summary-binmysql">
            <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
            <a href="#mysql"><?php echo $neardLang->getValue(Lang::MYSQL); ?></a>
          </span>
          <span class="list-group-item summary-binmariadb">
            <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
            <a href="#mariadb"><?php echo $neardLang->getValue(Lang::MARIADB); ?></a>
          </span>
          <span class="list-group-item summary-binpostgresql">
            <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
            <a href="#postgresql"><?php echo $neardLang->getValue(Lang::POSTGRESQL); ?></a>
          </span>
          <span class="list-group-item summary-binnodejs">
            <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
            <a href="#nodejs"><?php echo $neardLang->getValue(Lang::NODEJS); ?></a>
          </span>
          <span class="list-group-item summary-binmailhog">
            <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
            <a href="#mailhog"><?php echo $neardLang->getValue(Lang::MAILHOG); ?></a>
          </span>
          <span class="list-group-item summary-binmemcached">
            <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
            <a href="#memcached"><?php echo $neardLang->getValue(Lang::MEMCACHED); ?></a>
          </span>
          <span class="list-group-item summary-binfilezilla">
            <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
            <a href="#filezilla"><?php echo $neardLang->getValue(Lang::FILEZILLA); ?></a>
          </span>
          <span class="list-group-item summary-binsvn">
            <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
            <a href="#svn"><?php echo $neardLang->getValue(Lang::SVN); ?></a>
          </span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div style="min-height:250px;" class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-wrench"></i> <?php echo $neardLang->getValue(Lang::TOOLS); ?></h3>
      </div>
      <div class="panel-body panel-summary">
        <div class="list-group" style="margin-bottom:0;">
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/tools/composer/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardTools->getComposer()->getVersion(); ?></span>
            <span><?php echo $neardLang->getValue(Lang::COMPOSER); ?></span>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/tools/console/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardTools->getConsole()->getVersion(); ?></span>
            <span><?php echo $neardLang->getValue(Lang::CONSOLE); ?></span>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/tools/drush/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardTools->getDrush()->getVersion(); ?></span>
            <span><?php echo $neardLang->getValue(Lang::DRUSH); ?></span>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/tools/git/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardTools->getGit()->getVersion(); ?></span>
            <span><?php echo $neardLang->getValue(Lang::GIT); ?></span>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/tools/hostseditor/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardTools->getHostsEditor()->getVersion(); ?></span>
            <span><?php echo $neardLang->getValue(Lang::HOSTSEDITOR); ?></span>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/tools/imagemagick/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardTools->getImageMagick()->getVersion(); ?></span>
            <span><?php echo $neardLang->getValue(Lang::IMAGEMAGICK); ?></span>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/tools/notepad2mod/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardTools->getNotepad2Mod()->getVersion(); ?></span>
            <span><?php echo $neardLang->getValue(Lang::NOTEPAD2MOD); ?></span>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/tools/phpmetrics/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardTools->getPhpMetrics()->getVersion(); ?></span>
            <span><?php echo $neardLang->getValue(Lang::PHPMETRICS); ?></span>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/tools/phpunit/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardTools->getPhpUnit()->getVersion(); ?></span>
            <span><?php echo $neardLang->getValue(Lang::PHPUNIT); ?></span>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/tools/python/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardTools->getPython()->getVersion(); ?></span>
            <span><?php echo $neardLang->getValue(Lang::PYTHON); ?></span>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/tools/ruby/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardTools->getRuby()->getVersion(); ?></span>
            <span><?php echo $neardLang->getValue(Lang::RUBY); ?></span>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/tools/wpcli/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardTools->getWpCli()->getVersion(); ?></span>
            <span><?php echo $neardLang->getValue(Lang::WPCLI); ?></span>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/tools/xdc/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardTools->getXdc()->getVersion(); ?></span>
            <span><?php echo $neardLang->getValue(Lang::XDC); ?></span>
          </span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div style="min-height:250px;" class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-asterisk"></i> <?php echo $neardLang->getValue(Lang::APPS); ?></h3>
      </div>
      <div class="panel-body panel-summary">
        <div class="list-group" style="margin-bottom:0;">
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/apps/adminer/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardApps->getAdminer()->getVersion(); ?></span>
            <a href="adminer" target="_blank"><?php echo $neardLang->getValue(Lang::ADMINER); ?></a>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/apps/gitlist/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardApps->getGitlist()->getVersion(); ?></span>
            <a href="gitlist" target="_blank"><?php echo $neardLang->getValue(Lang::GITLIST); ?></a>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/apps/phpmemadmin/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardApps->getPhpmemadmin()->getVersion(); ?></span>
            <a href="phpmemadmin" target="_blank"><?php echo $neardLang->getValue(Lang::PHPMEMADMIN); ?></a>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/apps/phpmyadmin/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardApps->getPhpmyadmin()->getVersion() . ' (' . $neardApps->getPhpmyadmin()->getVersionsStr() . ')'; ?></span>
            <a href="phpmyadmin" target="_blank"><?php echo $neardLang->getValue(Lang::PHPMYADMIN); ?></a>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/apps/phppgadmin/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardApps->getPhppgadmin()->getVersion(); ?></span>
            <a href="phppgadmin" target="_blank"><?php echo $neardLang->getValue(Lang::PHPPGADMIN); ?></a>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/apps/webgrind/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardApps->getWebgrind()->getVersion(); ?></span>
            <a href="webgrind" target="_blank"><?php echo $neardLang->getValue(Lang::WEBGRIND); ?></a>
          </span>
          <span class="list-group-item">
            <a href="<?php echo APP_WEBSITE; ?>/apps/websvn/<?php echo Util::getUtmSource(); ?>#releases" target="_blank" title="<?php echo $neardLang->getValue(Lang::DOWNLOAD_MORE); ?>"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>
            <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardApps->getWebsvn()->getVersion(); ?></span>
            <a href="websvn" target="_blank"><?php echo $neardLang->getValue(Lang::WEBSVN); ?></a>
          </span>
        </div>
      </div>
    </div>
  </div>
</div>