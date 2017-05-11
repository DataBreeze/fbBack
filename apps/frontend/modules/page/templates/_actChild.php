<div class="gwBox fbBorder2" id="gwChildBody-<?php echo $act['key'] ?>">
  <h1><?php echo $act['namePlural'] ?> (<span id="gwChildCount-<?php echo $act['key'] ?>"><?php echo $recs['count'] ?></span>)</h1>
 <?php if( $act['showNew'] ) : ?>
  <div class="gwIntroNew centerText">
    <button class="fbButton" onclick="actMapNew('<?php echo $act['key'] ?>');return false;">Create New <?php echo $act['name'] ?></button>
  </div>
 <?php endif ?>
  <?php if($recs['count'] > 0) : ?>
  <table class="jrjTable">
    <tr>
      <?php for($j=0; $j < count($act['listLabels']); $j++): ?>
	    <th><?php echo $act['listLabels'][$j] ?></th>
	    <?php endfor ?>
	    <?php for($i=0; $i < count($recs['records']); $i++): ?>
		  <?php $rec = $recs['records'][$i] ?>
    <tr>
      <?php for($j=0; $j < count($act['listNames']); $j++): ?>
  	    <td><a href="http://<?php echo $act['host'] ?>.fishblab.com/<?php echo $rec[$act['keyName']] ?>"><?php echo $rec[$act['listNames'][$j]] ?></a></td>
	    <?php endfor ?> 
    </tr>
    <?php endfor ?> 
  </table>
  <?php endif ?>
</div>
