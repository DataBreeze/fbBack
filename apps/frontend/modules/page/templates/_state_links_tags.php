<div id="hmCity">
   <?php if(count($state_rand) > 0): ?>
  <div>
   <?php foreach ($state_rand as $i => $state): ?>
     <a class="<?php echo $state['class'] ?>" href="/<?php echo $state['state'] ?>/"><?php echo $state['state_full'] ?><?php '('.$state['count'].')' ?></a>
   <?php endforeach ?>
  </div>
 <?php endif ?>

</div>