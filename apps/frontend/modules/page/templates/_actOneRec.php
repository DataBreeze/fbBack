<div class="gwActOneRec">
    <?php for($i=0; $i < count($act['detailLabels']); $i++): ?>
    <p>
      <span class="gwLabel">
	<?php echo $act['detailLabels'][$i] ?>:
      </span>
      <span class="gwValue">
	<?php echo ($rec[$act['detailNames'][$i]] ? $rec[$act['detailNames'][$i]] : '&nbsp;') ?>
      </span>
      <br class="clear" />
    </p>
    <?php endfor ?>
</div>
