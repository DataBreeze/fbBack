<h3 id="gwChildHead-<?php echo $act['key'] ?>"><?php echo $act['namePlural'] ?> (<?php echo $recs['count'] ?>)</h3>
<div id="gwChildBody-<?php echo $act['key'] ?>">
  <div>
    <a class="fbButton mar5" onclick="actReplyGW('<?php echo $actParent['key'] ?>','<?php echo $recParent[$actParent['keyName']] ?>');return false;" href="http://map.fishblab.com/<?php echo $act['jsParam'] ?>/<?php echo $rec[$act['keyName']]?>">Add New Comment</a>
  </div>
  <?php if($recs['count'] > 0) : ?>
    <?php for($i=0; $i<count($recs['records']); $i++): ?>
      <?php $rec = $recs['records'][$i] ?>
      <?php if($rec['photo_id']) : ?>
        <?php $src = fbLib::wwwFilePath($rec['photo_id']) . '16x16.jpg' ?>
      <?php else: ?>
        <?php $src = '/images/fb/nophoto.png' ?>
      <?php endif ?>
  <div class="gwReplyBox fbBorder1 mar5">
    <div class="replyHead fbBorder1G">
      <div class="discHeadCell">
        <a href="http://user.fishblab.com/<?php echo $rec['username'] ?>">
          <img class="uImg" src="<?php echo $src ?>" />
	</a>
      </div>
      <div class="discHeadCell">
        <div class="discStatBox">
          <div class="discStatLink"><?php echo $rec['date_create'] ?></div>
	    <a href="http://user.fishblab.com/<?php echo $rec['username'] ?>"><?php echo $rec['username'] ?></a>
	  <div class="clear"></div>
        </div>
      </div>
      <div class="clear"></div>
    </div>
    <div class="gwReplyText">
      <?php echo $rec['content'] ?>
    </div>
  </div>
    <?php endfor ?>
  <div class="clear"></div>
  <?php endif ?>
</div>
