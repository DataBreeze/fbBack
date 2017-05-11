<?php $catch_count = count($catch); if($catch_count > 5){ $catch_count = 5; } ?>
<?php $city_count = count($city_weight); if($city_count > 5){ $city_count = 5; } ?>
<?php $site_count = count($sites_weight); if($site_count > 5){ $site_count = 5; } ?>

  <?php if($catch_count or $city_count or $site_count) : ?>
  <div class="gwBox fbBorder2">
    <?php if($catch_count > 0) : ?>
    <p>
      The top recreational fish caught in the state of <?php echo $state['state_full'] ?> in <?php echo $cur_month ?> are
      <?php for($i=0;$i<$catch_count;$i++): ?><?php $fish = $catch[$i]; if($i != 0){ echo ', '; } if( $i == ($catch_count - 1)){ echo ' and ' . $fish['name'] . '.'; }else{ echo $fish['name']; } ?><?php endfor ?>
    </p>
    <?php endif ?>
    <?php if($city_count > 0) : ?>
    <p>
      The top cities for coastal fishing in <?php echo $state['state_full'] ?> in <?php echo $cur_month ?> are
      <?php for($i=0; $i<$city_count; $i++): ?><?php $city = $city_weight[$i]; if($i != 0){ echo ', '; } if( ($i !=0) and  $i == ($city_count - 1) ){ echo ' and ' . $city['city'] . '.'; }else{ echo $city['city']; } ?><?php endfor ?>
    </p>
   <?php endif ?>
   <?php if($site_count > 0) : ?>
    <p>
      The most popular fishing sites in <?php echo $state['state_full'] ?> in <?php echo $cur_month ?> are
      <?php for($i=0; $i<$site_count; $i++): ?><?php $site = $sites_weight[$i]; if($i != 0){ echo ', '; } if( $i == ($site_count - 1) ){ echo ' and ' . $site['name'] .' in '. $site['city'] . '.'; }else{ echo $site['name'].' in '.$site['city']; } ?><?php endfor ?>
    </p>
   <?php endif ?>
  </div>
  <?php endif ?>

