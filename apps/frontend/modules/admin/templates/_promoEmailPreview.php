<h2>To: <?php echo $p['promo_email'] ?></h2>
<h2>From: <?php echo $p['email_from'] ?></h2>
<h2>Subject: <?php echo $p['subject'] ?></h2>
<div class="fbBorder5 pad5 mar5">
<?php include_partial('promoEmail1',array('p' => $p, 'status' => $status )) ?>
</div>
<form name="promoSendSubmit" action="">
<input type="submit" name="Send" value="Send" />
</form>