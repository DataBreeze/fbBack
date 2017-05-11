<?php include_partial('global/header',array('geo' => $geo, 'user' => $user, 'cfg' => $cfg )) ?>
<?php include_partial('actButtons', array('loc'=>$loc, 'geo'=>$geo) ) ?>
<?php include_partial('actPath',array('act' => $act,'rec' => $rec, 'loc' => $loc) ) ?>
<div id="hmMain">
  <div id="jrjActActionBar"></div>
  <div id="fbMap" class="rsMap fbBorder2"></div>
  <div id="gwOne" class="gwBoxAct fbBorder2">
   <?php if( $rec and count($rec) ) : ?>
    <?php include_partial('activityHead',array('act' => $act, 'rec' => $rec, 'user' => $user) ) ?>
    <?php include_partial('actOneRec',array('act' => $act, 'rec' => $rec) ) ?>
    <div class="gwActOneChild floatRight">
      <img class="gwImg" src="<?php echo fbLib::wwwFilePath(($rec['photo_id'] ? $rec['photo_id'] : fbLib::DEFAULT_PROFILE_PHOTO_ID)) ?>200x200.jpg" border="0" />
    </div>
   <?php else: ?>
    <h1 class="red"><?php echo $act['name'] ?> Not Found!</h1>
   <?php endif ?>
  </div>
  <div class="clear"></div>
  <?php include_partial('actOneChild2',array('act' => $act, 'actAll' => $actAll, 'rec' => $rec) ) ?>
  <div class="clear"></div>
</div>
<?php include_partial('footer',$footer) ?>
