<?php include '../core/bootstrap.php'; ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="<?php echo $neardHomepage->getResourcesUrl(); ?>/libs/jquery/jquery-1.10.2.js"></script>
    <script src="<?php echo $neardHomepage->getResourcesUrl(); ?>/libs/jquery/jquery-migrate-1.2.1.js"></script>
    <script src="<?php echo $neardHomepage->getResourcesUrl(); ?>/libs/bootstrap/bootstrap.min.js"></script>
    <link href="<?php echo $neardHomepage->getResourcesUrl(); ?>/libs/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $neardHomepage->getResourcesUrl(); ?>/libs/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo $neardHomepage->getResourcesUrl(); ?>/css/app.css" rel="stylesheet">
    <link href="<?php echo Util::imgToBase64($neardCore->getResourcesPath() . '/neard.ico'); ?>" rel="icon" />
    <title><?php echo APP_TITLE . ' ' . $neardCore->getAppVersion(); ?></title>
  </head>

  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav navbar-header">
          <li><a href="<?php echo $neardBs->getLocalUrl(); ?>"><img alt="<?php echo APP_TITLE . ' ' . $neardCore->getAppVersion(); ?>" src="<?php echo Util::imgToBase64($neardHomepage->getPath() . '/img/logo.png'); ?>" /></a></li>
        </ul>
        <ul style="margin-right:0;" class="nav navbar-nav navbar-right">
          <li><a title="<?php echo $neardLang->getValue(Lang::GITHUB); ?>" target="_blank" href="<?php echo APP_GITHUB_HOME; ?>"><img src="<?php echo Util::imgToBase64($neardHomepage->getPath() . '/img/github.png'); ?>" /></a></li>
        </ul>
      </div>
    </nav>
    
    <div id="page-wrapper">
        <?php include 'tpls/hp.latestversion.php'; ?>
        <?php include 'tpls/hp.' . $neardHomepage->getPage() . '.php'; ?>
    </div>
    
    <script type="text/javascript">
    $('.navbar-nav a[title]').tooltip({ html: true, placement: 'bottom' });
    $('a[title]').tooltip({ html: true });
    </script>
    
  </body>
</html>