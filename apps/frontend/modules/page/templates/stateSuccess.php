<?php include_partial('global/header',array('user' => $user, 'cfg' => $cfg )) ?>
<?php #include_partial('global/toolbar',array('geo' => $param['geo']) ) ?>
<?php include_partial('path',array('geo' => $geo)) ?>
<?php $catch_count = count($catch); if($catch_count > 5){ $catch_count = 5; } ?>
<?php $city_count = count($city_weight); if($city_count > 5){ $city_count = 5; } ?>
<?php $site_count = count($sites_weight); if($site_count > 5){ $site_count = 5; } ?>

<div id="hmMainX">
  <?php include_partial('actButtons', array('loc'=>$loc, 'geo'=>$geo) ) ?>
<?php include_partial('activityAll', array('recs' => $activities, 'act' => $act, 'loc'=>$loc, 'geo'=>$geo) ) ?>
  <div id="fbMap" class="rsMap fbBorder2"></div>
  
  <?php include_partial('state_head',array('catch' => $catch, 'sites_weight' => $sites_weight, 'state' => $state, 'cur_month' => $cur_month)) ?>
  <?php if($catch_count) : ?>
  <div class="gwBox fbBorder2">
    <h1>Hot Fish in <?php echo $state['state'] ?> in <?php echo $cur_month ?></h1>
    <?php include_partial('fish_top',array('catch' => $catch,'geo' => $geo) ) ?>
  </div>
  <?php endif ?>

  <?php if(count($fish_rand) > 0) : ?>
  <div class="gwBox fbBorder2">
    <h1>Fish in <?php echo $state['state_full'] ?></h1>
    <?php include_partial('fish_links_tags',array('fish_rand' => $fish_rand,'geo' => $geo) ) ?>
  </div>
  <?php endif ?>

  <?php if($city_count > 0) : ?>
  <div class="gwBox fbBorder2">
    <h1>Cities in <?php echo $state['state_full'] ?></h1>
    <?php include_partial('city_links_tags',array('city_rand' => $city_rand) ) ?>
  </div>
  <?php endif ?>

  <div class="clear"></div>
</div>
<?php include_partial('footer',$footer ) ?>
