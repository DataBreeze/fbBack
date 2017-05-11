<?php include_partial('global/header',array('geo' => $geo, 'user' => $user, 'cfg' => $cfg )) ?>
<?php include_partial('actButtons', array('loc'=>$loc, 'geo'=>$geo) ) ?>
<?php include_partial('actPath',array('act' => $act,'rec' => $rec, 'loc' => $loc) ) ?>
<div id="hmMain">
  <div id="jrjActActionBar"></div>
  <div id="fbMap" class="rsMap fbBorder2"></div>
  <div id="gwOne" class="gwBoxAct fbBorder2">
   <?php if($rec) : ?>
    <?php include_partial('activityHead',array('act' => $act, 'rec' => $rec, 'user' => $user) ) ?>
    <?php include_partial('actOneRec',array('act' => $act, 'rec' => $rec) ) ?>
    <div class="gwActOneChild">
      <img class="gwImg" src="<?php echo fbLib::wwwFilePath($rec['id']) ?>400x400.jpg" border="0" />
    </div>
    <?php include_partial('actOneChild',array('act' => $act, 'actAll' => $actAll, 'rec' => $rec) ) ?>
    <div class="clear"></div>
   <?php else: ?>
    <h1 class="red"><?php echo $act['name'] ?> Not Found!</h1>
   <?php endif ?>
  </div>
  <div class="clear"></div>
</div>
<?php include_partial('footer',$footer) ?>
