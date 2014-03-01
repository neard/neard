<a class="anchor" name="nodejs"></a>
<div class="row">
  <div class="col-lg-12">
    <h1><img src="<?php echo Util::imgToBase64($neardHomepage->getPath() . '/img/nodejs.png'); ?>" /> <?php echo $neardLang->getValue(Lang::NODEJS); ?> <small></small></h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-4">
    <div class="list-group">
      <span class="list-group-item">
        <?php foreach ($neardBins->getNodejs()->getVersionList() as $version) {
            if ($version != $neardBins->getNodejs()->getVersion()) {
                ?><span style="float:right;font-size:12px;margin-left:2px;" class="label label-default"><?php echo $version; ?></span><?php
            }
        } ?>
        <span style="float:right;font-size:12px;margin-left:2px;" class="label label-primary"><?php echo $neardBins->getNodejs()->getVersion(); ?></span>
        <i class="fa fa-puzzle-piece"></i> <?php echo $neardLang->getValue(Lang::VERSION); ?>
      </span>
    </div>
  </div>
</div>