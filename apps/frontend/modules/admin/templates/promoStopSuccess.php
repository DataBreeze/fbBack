<div class="centerText pad10 mar10">
   <?php if($status['error']) : ?>
   <h1 class="red">Removal Error</h1>
      <p class="bold mar10 pad10"><?php echo $status['desc'] ?></p>
   <?php else : ?>
<h1>Removal Success</h1>
<p mar10 pad10>Your email has been added to the <b>no contact</b> list for FishBlab.com marketing campaigns</p>
<p>Thanks!</p>
   <?php endif ?>
<p><a href="http://www.fishblab.com">www.fishblab.com</a></p>
</div>