<a class="anchor" name="php"></a>
<div class="row">
  <div class="col-lg-12">
    <h1><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/php.png'; ?>" /> <?php echo $neardLang->getValue(Lang::PHP); ?> <small></small></h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
    <div class="list-group">
      <span class="list-group-item php-status">
        <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
        <i class="fa fa-bar-chart-o"></i> <?php echo $neardLang->getValue(Lang::STATUS); ?>
      </span>
      <span class="list-group-item php-versions">
        <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
        <i class="fa fa-puzzle-piece"></i> <?php echo $neardLang->getValue(Lang::VERSIONS); ?>
      </span>
      <span class="list-group-item php-extscount">
        <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
        <i class="fa fa-gear"></i> <?php echo $neardLang->getValue(Lang::EXTENSIONS); ?>
      </span>
      <span class="list-group-item php-pearversion">
        <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
        <i class="fa fa-puzzle-piece"></i> <?php echo $neardLang->getValue(Lang::PEAR); ?>
      </span>
      <span class="list-group-item">
        <i class="fa fa-info-circle"></i> <a href="<?php echo $neardHomepage->getPageQuery(Homepage::PAGE_PHPINFO); ?>"><?php echo $neardLang->getValue(Lang::HOMEPAGE_PHPINFO_TEXT); ?></a>
      </span>
      <?php if (function_exists('apc_add') && function_exists('apc_exists')) {
    ?>
      <span class="list-group-item">
        <i class="fa fa-info-circle"></i> <a href="<?php echo $neardHomepage->getPageQuery(Homepage::PAGE_STDL_APC); ?>" target="_blank"><?php echo $neardLang->getValue(Lang::HOMEPAGE_APC_TEXT); ?></a>
      </span>
      <?php 
} ?>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <h3><i class="fa fa-gear"></i> <?php echo $neardLang->getValue(Lang::EXTENSIONS); ?> <small></small></h3>
  </div>
</div>
<div class="row">
  <div class="col-lg-12 php-extslist">
    <span class="loader"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
  </div>
</div>