<div class="fbBorder1 pad5 mar5">
<h2>To: <?php echo $p['promo_email'] ?></h2>
<h2>From: <?php echo $p['email_from'] ?></h2>
<h2>Subject: <?php echo $p['subject'] ?></h2>
<form name="promoSendSubmit" action="http://admin.fishblab.com/promoSend/<?php echo $p['id'] ?>" method="post">
<input type="submit" name="Send" value="Send" />
</form>
</div>
<div class="">
<?php include_partial('promoEmail1',array('p' => $p)) ?>
</div>
