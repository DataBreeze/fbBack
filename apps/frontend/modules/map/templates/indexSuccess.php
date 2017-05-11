<?php include_partial('global/header',array('geo' => $geo, 'user' => $user, 'cfg' => $cfg )) ?>
<?php include_partial('global/toolbar',array('geo' => $param['geo']) ) ?>
<div id="hmMain">
  <div id="jrjActActionBar"></div>
  <div id="fbMap" class="appMap"></div>
</div>
<div id="jrjDialog"></div>
<div id="jrjDialog2"></div>
<div id="jrjDialog3"></div>
<?php include_partial('global/js_links') ?>
<?php include_partial('js_links') ?>
<?php include_partial('global/js',array('json'=>$json)) ?>
