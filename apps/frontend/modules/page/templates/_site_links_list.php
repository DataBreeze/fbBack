<div id="hmCity">
   <?php if(count($site_alpha) > 0): ?>
  <div>
   <?php $modval = $mod = intval($count/5) ?>
   <?php foreach ($site_alpha as $i => $site): ?>
     <?php if($i == 0): ?>
       <div class="pgRow">
     <?php elseif($i > $modval): ?>
       <?php $modval += $mod ?>
       </div><div class="pgRow">
     <?php endif ?>
       <a class="pgCell" href="/area/<?php echo $site['state'] ?>/<?php echo $site['city'] ?>/<?php $site['name'] ?>"><?php echo $site['name'] ?> <?php echo $site['city'] ?>, <?php echo $site['state'] ?><?php '('.$site['count'].')' ?></a>
     <?php if($i == ($count-1) ): ?>
       </div>   
     <?php endif ?>
   <?php endforeach ?>
  </div>
 <?php endif ?>
</div>
