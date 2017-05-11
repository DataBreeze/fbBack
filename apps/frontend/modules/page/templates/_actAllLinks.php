<div class="mar5">
<?php if($act['showNew']) : ?>
   <button class="fbButton" onclick="actMapNew('<?php echo $act['key'] ?>')">New <?php echo $act['name'] ?></button>
<?php endif ?>
<?php if($act['showMap']) : ?>
   <button class="fbButton" onclick="actMapInit('<?php echo $act['key'] ?>')">Map all <?php echo $act['namePlural'] ?></button>
<?php endif ?>
</div>
