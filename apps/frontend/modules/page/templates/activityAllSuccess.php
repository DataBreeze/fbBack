<?php include_partial('global/header',array('geo' => $geo, 'user' => $user, 'cfg' => $cfg )) ?>
<?php include_partial('actButtons', array('loc'=>$loc, 'geo'=>$geo) ) ?>
<?php include_partial('actPath',array('act' => $act,'recs' => $recs, 'loc' => $loc) ) ?>
<div id="hmMain">
  <div id="jrjActActionBar"></div>
  <div id="fbMap" class="rsMap fbBorder2"></div>
  <div class="gwBoxAct fbBorder2">
  <?php include_partial('actCount',array('act' => $act,'recs' => $recs, 'offset' => $param['offset'], 'loc' => $loc) ) ?>
  <?php include_partial('actAllLinks',array('act' => $act,'recs' => $recs, 'loc' => $loc) ) ?>
  <?php if($recs['count'] > 0) : ?>
  <table class="gwActTable fbBorder2">
    <tr>
      <?php for($j=0; $j < count($act['listLabels']); $j++): ?>
	<th><?php echo $act['listLabels'][$j] ?></th>
      <?php endfor ?>
      <?php for($i=0; $i < $recs['count']; $i++): ?>
        <?php $rec = $recs['records'][$i] ?>
        <tr>
	  <?php for($j=0; $j < count($act['listNames']); $j++): ?>
	  <?php $name = $act['listNames'][$j]; $value = $rec[$name]; ?>		
  	  <td>
	    <?php if($name == 'fbmap') : ?>
	      <a href="#" onclick="actMapOne('<?php echo $act['key'] ?>','<?php echo $rec[$act['keyName']] ?>')">Map</a>
	    <?php else : ?>
	      <a href="http://<?php echo $act['host'] ?>.fishblab.com/<?php if($loc['state']){ echo $loc['state'] .'/'; } ?><?php if($loc['city']){ echo urlencode($loc['city']) .'/'; } ?><?php echo $rec[$act['keyName']] ?>"><?php echo $value ?></a>
	    <?php endif ?>
	  </td>
	  <?php endfor ?> 
        </tr>
      <?php endfor ?> 
  </table>
  <?php else : ?>
     <div class="gwIntroTease">Become the leader in this region by posting a new <?php echo $act['name'] ?> here</div>
  <?php endif ?>
  </div>
</div>
<?php include_partial('footer',$footer) ?>
