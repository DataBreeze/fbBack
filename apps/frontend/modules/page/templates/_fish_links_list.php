<div id="hmFishList">
   <?php if($count > 0): ?>
  <div>
   <?php $modval = $mod = intval($count/5) ?>
   <?php foreach ($fish_alpha as $i => $fish): ?>
     <?php if($i == 0): ?>
       <div class="pgRow">
     <?php elseif($i > $modval): ?>
       <?php $modval += $mod ?>
       </div><div class="pgRow">
     <?php endif ?>
     <a class="pgCell" href="http://fish.fishblab.com/<?php echo urlencode($fish['name']) ?><?php if(isset($geo['state'])){ echo '/' . $geo['state']; } ?><?php if(isset($geo['city'])){ echo '/' . urlencode($geo['city']); }; ?>">
       <?php echo $fish['name'] ?>
     </a>
     <?php if($i == ($count-1) ): ?>
       </div>   
     <?php endif ?>
   <?php endforeach ?>
  </div>
 <?php endif ?>
</div>
