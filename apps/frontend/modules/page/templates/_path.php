<div id="hmPath">
  <a href="/">FishBlab</a>
   &gt;
<?php if( $geo['page'] == 'allFish' ) : ?>
   <span>All Fish</span>
<?php elseif( $geo['page'] == 'allCities' ) : ?>
   <span>All Cities</span>
<?php else : ?>
 <?php if( isset($geo['site']) ) : ?>
  <a href="/<?php echo $geo['state'] ?>/"><?php echo $geo['state'] ?></a>
   &gt;
  <a href="/<?php echo $geo['state'] ?>/<?php echo urlencode($geo['city']) ?>/">
    <?php echo $geo['city'] ?>
  </a>
   &gt;
  <span><?php echo $geo['site'] ?></span>
 <?php elseif( isset($geo['city']) ) : ?>
  <a href="/<?php echo $geo['state'] ?>/"><?php echo $geo['state'] ?></a>
   &gt;<span><?php echo $geo['city'] ?></span>
 <?php elseif( isset($geo['state']) ) : ?>
   <span><?php echo $geo['state'] ?></span>
 <?php endif ?>
<?php endif ?>
</div>
