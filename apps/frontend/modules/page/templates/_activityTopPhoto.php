<div class="gwBox fbBorder2">
  <?php include_partial('actTopHead',array('act' => $act,'recs' => $recs, 'loc' => $loc) ) ?>
  <?php if($recs['count'] > 0) : ?>
  <h5>Latest:</h5>
  <?php for($i=0; $i<$recs['count']; $i++): ?>
  <?php $rec = $recs['records'][$i] ?>
  <div class="photoListImg fbBorder1 hmAct">
    <a href="http://photo.fishblab.com/<?php if($loc['state']){ echo $loc['state'] .'/'; } ?><?php if($loc['city']){ echo urlencode($loc['city']) .'/'; } ?><?php echo $rec['id'] ?>">
      <img src="<?php echo fbLib::wwwFilePath($rec['id']) ?>50x50.jpg" border="0" />
    </a>
  </div>
  <?php endfor ?>
  <?php else : ?> 
    <div class="gwIntroTease">Become the leader in this region by posting a new <?php echo $act['name'] ?> here</div>
  <?php endif ?>
  <div class="clear"></div>
       <?php if($act['showNew']) : ?>
  <div class="gwIntroNew">
    <button class="fbButton" onclick="actMapNew('<?php echo $act['key'] ?>');return false;">Create New <?php echo $act['name'] ?></button>
  </div>
     <?php endif ?>
</div>
