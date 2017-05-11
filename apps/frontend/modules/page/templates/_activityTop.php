<?php $count = $recs['count']; ?>
<div class="gwBox fbBorder2">
  <?php include_partial('actTopHead',array('act' => $act,'recs' => $recs, 'loc' => $loc) ) ?>
  <?php if($count > 0) : ?>
   <h5>Latest:</h5>
  <table class="gwActTable">
    <tr>
      <?php for($j=0; $j < count($act['listLabelsMin']); $j++): ?>
	  <th><?php echo $act['listLabelsMin'][$j] ?></th>
      <?php endfor ?>
      <?php for($i=0; $i < $count; $i++): ?>
        <?php $rec = $recs['records'][$i] ?>
        <tr>
	  <?php for($j=0; $j < count($act['listNamesMin']); $j++): ?>
  	    <td><a href="http://<?php echo $act['host'] ?>.fishblab.com/<?php if($loc['state']){ echo $loc['state'] .'/'; } ?><?php if($loc['city']){ echo urlencode($loc['city']) .'/'; } ?><?php echo $rec[$act['keyName']] ?>"><?php echo $rec[$act['listNamesMin'][$j]] ?></a></td>
	  <?php endfor ?> 
        </tr>
      <?php endfor ?> 
  </table>
  <?php elseif($act['key'] != 'user') : ?>
     <div class="gwIntroTease">Become the leader in this region by posting a new <?php echo $act['name'] ?> here</div>
  <?php endif ?>
    <div class="clear"></div>
    <?php if($act['showNew']) : ?>
    <div class="gwIntroNew">
      <button class="fbButton" onclick="actMapNew('<?php echo $act['key'] ?>');return false;">Create New <?php echo $act['name'] ?></button>
    </div>
    <?php endif ?>
</div>
