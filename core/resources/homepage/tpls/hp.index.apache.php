<a class="anchor" name="apache"></a>
<div class="row">
  <div class="col-lg-12">
    <h1><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/apache.png'; ?>" /> <?php echo $neardLang->getValue(Lang::APACHE); ?> <small></small></h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
    <div class="list-group">
      <span class="list-group-item apache-checkport">
        <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
        <i class="fa fa-bar-chart-o"></i> <?php echo $neardLang->getValue(Lang::STATUS); ?>
      </span>
      <span class="list-group-item apache-versions">
        <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
        <i class="fa fa-puzzle-piece"></i> <?php echo $neardLang->getValue(Lang::VERSIONS); ?>
      </span>
      <span class="list-group-item apache-modulescount">
        <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
        <i class="fa fa-gear"></i> <?php echo $neardLang->getValue(Lang::MODULES); ?>
      </span>
      <span class="list-group-item apache-aliasescount">
        <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
        <i class="fa fa-link"></i> <?php echo $neardLang->getValue(Lang::ALIASES); ?>
      </span>
      <span class="list-group-item apache-vhostscount">
        <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
        <i class="fa fa-globe"></i> <?php echo $neardLang->getValue(Lang::VIRTUAL_HOSTS); ?>
      </span>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <h3><i class="fa fa-gear"></i> <?php echo $neardLang->getValue(Lang::MODULES); ?> <small></small></h3>
  </div>
</div>
<div class="row">
  <div class="col-lg-12 apache-moduleslist">
    <span class="loader"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <h3><i class="fa fa-link"></i> <?php echo $neardLang->getValue(Lang::ALIASES); ?> <small></small></h3>
  </div>
</div>
<div class="row">
  <div class="col-lg-12 apache-aliaseslist">
    <span class="loader"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <h3><i class="fa fa-folder"></i> <?php echo $neardLang->getValue(Lang::MENU_WWW_DIRECTORY); ?> <small></small></h3>
  </div>
</div>
<div class="row">
  <div class="col-lg-12 apache-wwwdirectory">
    <span class="loader"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <h3><i class="fa fa-globe"></i> <?php echo $neardLang->getValue(Lang::VIRTUAL_HOSTS); ?> <small></small></h3>
  </div>
</div>
<div class="row">
  <div class="col-lg-12 apache-vhostslist">
    <span class="loader"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
  </div>
</div>