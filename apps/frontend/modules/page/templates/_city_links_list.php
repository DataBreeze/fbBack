<div id="hmCity">
   <?php if(count($city_alpha) > 0): ?>
  <div>
   <?php $modval = $mod = intval($count/5) ?>
   <?php foreach ($city_alpha as $i => $city): ?>
     <?php if($i == 0): ?>
       <div class="pgRow">
     <?php elseif($i > $modval): ?>
       <?php $modval += $mod ?>
       </div><div class="pgRow">
     <?php endif ?>
       <a class="pgCell" href="/<?php echo $city['state'] ?>/<?php echo urlencode($city['city']) ?>"><?php echo $city['city'] ?>, <?php echo $city['state'] ?></a>
     <?php if($i == ($count-1) ): ?>
       </div>   
     <?php endif ?>
   <?php endforeach ?>
  </div>
 <?php endif ?>
</div>
