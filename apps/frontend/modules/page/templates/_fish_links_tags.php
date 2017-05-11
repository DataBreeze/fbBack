<div id="hmFishTag">
  <?php if(count($fish_rand) > 0): ?>
  <div>
    <?php foreach ($fish_rand as $i => $fish): ?>
    <a class="<?php echo $fish['class'] ?>" href="http://fish.fishblab.com/<?php echo urlencode($fish['name']) ?><?php if( isset($geo['state']) ){ echo '/' . $geo['state']; } ?><?php if( isset($geo['city']) ){ echo '/' . urlencode($geo['city']); } ?>">
      <?php echo $fish['name'] ?>
    </a>
    <?php endforeach ?>
  </div>
  <?php endif ?>
</div>
