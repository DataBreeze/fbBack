<?php $ccount = count($catch); if($ccount > 5){ $ccount = 5; } ?>
<?php $scount = count($sites_weight); if($scount > 5){ $scount = 5; } ?>
<?php if( ($ccount > 0) or ($scount > 0) ) : ?>
<div class="gwBox fbBorder2">
  <?php if($ccount > 0) : ?>
  <p>
    The most popular fish caught in the City of <?php echo $city['city'] ?> in <?php echo $cur_month ?> are
    <?php for($i=0;$i<$ccount;$i++): ?><?php $fish = $catch[$i]; if($i != 0){ echo ', '; } if( $i == ($count - 1)){ echo ' and ' . $fish['name'] . '.'; }else{ echo $fish['name']; } ?><?php endfor ?>
  </p>
  <?php endif ?>
  <?php if($scount > 0) : ?>
  <p>
    Popular fishing sites in <?php echo $city['city'] ?> during <?php echo $cur_month ?> are
    <?php for($i=0; $i<$scount; $i++): ?><?php $site = $sites_weight[$i]; if($i != 0){ echo ', '; } if( ($i !=0) and $i == ($count - 1) ){ echo ' and ' . $site['name'] . '.'; }else{ echo $site['name']; } ?><?php endfor ?>
  </p>
  <?php endif ?>
</div>
<?php endif ?>
