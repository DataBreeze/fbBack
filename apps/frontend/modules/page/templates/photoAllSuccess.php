<?php include_partial('global/header',array('geo' => $geo, 'user' => $user, 'cfg' => $cfg )) ?>
<?php include_partial('actButtons', array('loc'=>$loc, 'geo'=>$geo) ) ?>
<?php include_partial('actPath',array('act' => $act,'recs' => $recs, 'loc' => $loc) ) ?>
<div class="gwActAll fbBorder2">
  <?php include_partial('actCount',array('act' => $act,'recs' => $recs,'offset' => $param['offset']) ) ?>
  <?php $count = $recs['count']; ?>
  <?php if($count > 0) : ?>
   <?php for($i=0; $i<$count; $i++): ?>
   <?php $rec = $recs['records'][$i] ?>
  <div class="fbBorder2G gwActCell">
    <a href="http://<?php echo $act['host'] ?>.fishblab.com/<?php if($loc['state']){ echo $loc['state'] .'/'; } ?><?php if($loc['city']){ echo urlencode($loc['city']) .'/'; } ?><?php echo $rec[$act['keyName']] ?>">
      <img src="<?php echo fbLib::wwwFilePath($rec['id']) ?>200x200.jpg" border="0" />
    </a>
    <div>
      <span><?php echo $rec['date_create'] ?></span>
      <span>Comments (<?php echo $rec['reply_count'] ?>)</span>
    </div>
  </div>
  <?php endfor ?> 
  <div class="clear"></div>
  <?php else : ?>
  No Photos Found
  <?php endif ?>
</div>
<?php include_partial('footer',$footer) ?>
