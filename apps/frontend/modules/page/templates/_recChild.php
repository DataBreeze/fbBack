  <div class="gwActOneChild">
	<?php if($act['showReply']) : ?>
	<div class="gwChild fbBorder1">
	  <div class="gwChildHead">Comments (<?php echo $rec['reply_count'] ?>)</div>
	  <?php if($rec['reply_count'] > 0) : ?>
	  <?php include_partial('global/replies_gw', array('replies' => $rec['replies']) ) ?>      
	  <?php endif ?>
	</div>
        <?php endif ?>

	<?php if($act['showFish']) : ?>
	<div class="gwChild fbBorder1">
	  <div class="gwChildHead">Fish (<?php echo $rec['fish_count'] ?>)</div>
	  <?php if($rec['fish_count'] > 0) : ?>
  	  <?php include_partial('global/fish_gw', array('fishes' => $rec['fishes']) ) ?>      
	  <?php endif ?>
	</div>
	<?php endif ?>

	<?php if($act['showPhoto']) : ?>
	<div class="gwChild fbBorder1">
	  <div class="gwChildHead">Photos (<?php echo $rec['photo_count'] ?>)</div>
	  <?php if($rec['photo_count'] > 0) : ?>
          <?php include_partial('global/photos_gw', array('photos' => $rec['photos']) ) ?>      
	  <?php endif ?>
	</div>
        <?php endif ?>
  </div>
