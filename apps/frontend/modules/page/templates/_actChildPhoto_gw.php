<div class="gwChild fbBorder1">
  <div class="gwChildHead"><?php echo $act['namePlural'] ?> (<?php echo $recs['count'] ?>)</div>
  <?php if($recs['count'] > 0) : ?>
    <?php for($i=0; $i<count($recs['records']); $i++): ?>
    <?php $rec = $recs['records'][$i] ?>
    <div class="fbBorder2G gwActCell">
      <a href="http://photo.fishblab.com/<?php echo $rec['id'] ?>">
	<img src="<?php echo fbLib::wwwFilePath($rec['id']) ?>200x200.jpg" border="0" />
      </a>
    </div>
    <?php endfor ?>
    <div class="clear"></div>
  </div>
  <?php endif ?>
</div>
