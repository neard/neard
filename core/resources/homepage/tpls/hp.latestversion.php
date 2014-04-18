<?php

$neardCurrentVersion = $neardConfig->getAppVersion();
$neardLatestVersion =  Util::getLatestVersion();

if ($neardLatestVersion != null && version_compare($neardCurrentVersion, $neardLatestVersion, '<')) {
    $fullVersionUrl = Util::getVersionUrl($neardLatestVersion);
    $patchVersionUrl = Util::getPatchUrl($neardCurrentVersion, $neardLatestVersion);
?>

<div class="alert alert-dismissable alert-success">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <h4><?php echo $neardLang->getValue(Lang::CHECK_VERSION_AVAILABLE_TEXT); ?></h4>
    <p>
      <a role="button" class="btn btn-success" href="<?php echo $patchVersionUrl; ?>" target="_blank"><i class="fa fa-download"></i> <?php echo $neardLang->getValue(Lang::DOWNLOAD); ?> <strong>Neard <?php echo $neardCurrentVersion; ?>-<?php echo $neardLatestVersion; ?> Patch</strong><br /><small>neard-<?php echo $neardCurrentVersion; ?>-<?php echo $neardLatestVersion; ?>.zip (<?php echo Util::getRemoteFilesize($patchVersionUrl); ?>)</small></a>
      <a role="button" class="btn btn-success" href="<?php echo $fullVersionUrl; ?>" target="_blank"><i class="fa fa-download"></i> <?php echo $neardLang->getValue(Lang::DOWNLOAD); ?> <strong>Neard <?php echo $neardLatestVersion; ?> Full</strong><br /><small>neard-<?php echo $neardLatestVersion; ?>.zip (<?php echo Util::getRemoteFilesize($fullVersionUrl); ?>)</small></a>
    </p>
    <p><?php echo sprintf($neardLang->getValue(Lang::READ_CHANGELOG), '<a href="#" data-toggle="modal" data-target=".modal-changelog">', '</a>'); ?></p>
</div>
<div class="modal fade modal-changelog" tabindex="-1" role="dialog" aria-labelledby="modal-changelog-title" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="modal-changelog-title"><?php echo $neardLang->getValue(Lang::CHANGELOG); ?></h4>
      </div>
      <div class="modal-body"><?php echo Util::getLatestChangelog(); ?></div>
    </div>
  </div>
</div>

<?php
}
?>
