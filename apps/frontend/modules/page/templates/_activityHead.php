<div class="discCap fbBorder1">
  <div class="floatLeft">
    <div class="discHeadCell">
      <img class="uImg" src="<?php echo fbLib::wwwFilePath(($rec['photo_id'] ? $rec['photo_id'] : fbLib::DEFAULT_PROFILE_PHOTO_ID) ) ?>32x32.jpg" height="32" width="32">
    </div>
    <div class="discHeadCell">
      <div class="discCapTxt">
	<span class="floatLeft marRight5"><?php echo $act['name'] ?>: </span>
	<span class="floatLeft darkGreen"><?php echo $rec[$act['displayName']] ?></span>
	<div class="clear"></div>
      </div>
      <div class="discStatBox">
	<div class="discStatLink"><?php echo $rec['date_create'] ?></div>
	<div class="discStatLink">Public</div>
	<a href="http://user.fishblab.com/<?php echo urlencode($rec['username']) ?>" class="discStatLink"><?php echo $rec['username'] ?></a>
	<div class="clear"></div>
      </div>
    </div>
    <div class="clear">
    </div>
   </div>
   <?php if($act['showEdit'] and ($rec['username'] == $user['username']) ) : ?>
  <div class="floatRight marRight5">
    <a class="fbButton" onclick="actMapEdit('<?php echo $act['key'] ?>','<?php echo $rec[$act['keyName']] ?>');return false;" href="http://map.fishblab.com/<?php echo $act['jsParam'] ?>/<?php echo $rec[$act['keyName']]?>">Edit</a>
    <div class="clear"></div>
  </div>   
  <?php endif ?>
  <?php if($act['showMap']) : ?>
  <div class="floatRight">
    <a class="fbButton" onclick="actMapOne('<?php echo $act['key'] ?>','<?php echo $rec[$act['keyName']] ?>');return false;" href="http://map.fishblab.com/<?php echo $act['jsParam'] ?>/<?php echo $rec[$act['keyName']]?>">Map</a>
    <div class="clear"></div>
  </div>
  <?php endif ?>

  <div class="clear"></div>

</div>
