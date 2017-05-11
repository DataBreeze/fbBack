<?php include_partial('global/header',array('geo' => $geo, 'user' => $user, 'cfg' => $cfg )) ?>
<?php include_partial('actButtons', array('loc'=>$loc, 'geo'=>$geo) ) ?>
<?php include_partial('actPath',array('act' => $act,'recs' => $recs, 'loc' => $loc) ) ?>
<div id="hmMain">
  <div id="jrjActActionBar"></div>
  <div id="fbMap" class="rsMap fbBorder2"></div>
  <div class="gwBoxMax fbBorder2">
    <?php include_partial('actCount',array('act' => $act,'recs' => $recs, 'offset' => $param['offset'], 'loc' => $loc) ) ?>
    <div class="clear"></div>
    <?php if($recs['count'] > 0) : ?>
    <table class="gwActTable">
      <tr>
	<?php for($j=0; $j < count($act['listLabels']); $j++): ?>
	      <th><?php echo $act['listLabels'][$j] ?></th>
	<?php endfor ?>
	<?php for($i=0; $i < $recs['count']; $i++): ?>
	      <?php $rec = $recs['records'][$i] ?>
          <tr>
	  <?php for($j=0; $j < count($act['listNames']); $j++): ?>
  	    <td>
	      <a href="http://<?php echo $act['host'] ?>.fishblab.com/<?php echo urlencode($rec['name']) ?><?php if($loc['state']){ echo '/' . $loc['state']; } ?><?php if($loc['city']){ echo '/' . urlencode($loc['city']); } ?>"><?php echo $rec[$act['listNames'][$j]] ?></a>
	    </td>
	  <?php endfor ?> 
	  </tr>
	<?php endfor ?> 
    </table>
    <?php else : ?>
    No Records Found
    <?php endif ?>
  </div>
</div>
<?php include_partial('footer',$footer) ?>
