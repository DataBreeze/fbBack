  <div class="gwActOneRec">
    <?php for($i=0; $i < count($act['detailLabels']); $i++): ?>
     <?php $value = $rec[$act['detailNames'][$i]]; ?>
  <?php if(isset($value)) : ?>
     <p>
      <span class="gwLabel">
	<?php echo $act['detailLabels'][$i] ?>:
      </span>
      <span class="gwValue">
	<?php echo ($rec[$act['detailNames'][$i]] ? $rec[$act['detailNames'][$i]] : '&nbsp;') ?>
      </span>
      <br class="clear" />
     </p>
    <?php endif ?>
  <?php endfor ?>
  </div>
