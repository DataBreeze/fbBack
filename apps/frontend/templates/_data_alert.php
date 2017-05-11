<?php if($alert['text'] and $alert['id']) : ?>
<div id="<? echo $alert['id'] ?>" class="fbBorder2 alertBox">
   <span class="alertTip">Tip:</span> <?php echo $alert['text'] ?>
   <div class="alertDismiss" onclick="alertDismiss('<?php echo $alert['id'] ?>');return false;" rel="nofollow">Dismiss This Tip</div>
</div>
<?php endif ?>