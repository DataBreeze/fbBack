<div id="hmTags">
   <?php if(count($sites_rand) > 0): ?>
  <div>
   <?php foreach ($sites_rand as $i => $site): ?>
     <a class="<?php echo $site['class'] ?>" href="/<?php echo $site['state'] ?>/<?php echo $site['city'] ?>/<?php echo $site['name'] ?>"><?php echo $site['name'] ?> <?php echo $site['city'] ?>, <?php echo $site['state'] ?><?php '('.$site['count'].')' ?></a>
   <?php endforeach ?>
  </div>
 <?php endif ?>

</div>