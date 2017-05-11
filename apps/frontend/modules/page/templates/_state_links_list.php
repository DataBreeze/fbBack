<div id="hmList">
   <?php if(count($state_all) > 0): ?>
  <div>
   <?php $modval = $mod = intval($count/5) ?>
   <?php foreach ($city as $i => $state): ?>
     <?php if($i == 0): ?>
       <div class="pgRow">
     <?php elseif($i > $modval): ?>
       <?php $modval += $mod ?>
       </div><div class="pgRow">
     <?php endif ?>
       <a class="pgCell" href="http://www.fishblab.com/<?php echo $city['state'] ?>/"><?php echo $city['city'] ?></a>
     <?php if($i == ($count-1) ): ?>
       </div>   
     <?php endif ?>
   <?php endforeach ?>
  </div>
 <?php endif ?>
</div>
