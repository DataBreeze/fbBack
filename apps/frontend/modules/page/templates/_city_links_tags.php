<div id="hmCity">
   <?php if(count($city_rand) > 0): ?>
  <div>
   <?php foreach ($city_rand as $i => $city): ?>
     <a class="<?php echo $city['class'] ?>" href="http://www.fishblab.com/<?php echo $city['state'] ?>/<?php echo urlencode($city['city']) ?>"><?php echo $city['city'] ?>, <?php echo $city['state'] ?></a>
   <?php endforeach ?>
  </div>
 <?php endif ?>
</div>
