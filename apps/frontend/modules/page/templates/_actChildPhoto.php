<div class="gwBox fbBorder2">
  <h1><?php echo $act['namePlural'] ?> (<?php echo $recs['count'] ?>)</h1>
  <?php if( $act['showNew'] ) : ?>
  <div class="gwIntroNew centerText">
    <button class="fbButton" onclick="actMapNew('<?php echo $act['key'] ?>');return false;">Create New <?php echo $act['name'] ?></button>
  </div>
  <?php endif ?>

  <?php if($recs['count'] > 0) : ?>
  <?php for($i=0; $i<count($recs['records']); $i++): ?>
	<?php $rec = $recs['records'][$i] ?>
	<div class="gwActCell">
	  <a class="uImg" href="http://photo.fishblab.com/<?php echo $rec['id'] ?>">
	    <img src="<?php echo fbLib::wwwFilePath($rec['id']) ?>200x200.jpg" border="0" />
	  </a>
	</div>
	<?php endfor ?>
	<div class="clear"></div>
	<?php endif ?>
</div>
