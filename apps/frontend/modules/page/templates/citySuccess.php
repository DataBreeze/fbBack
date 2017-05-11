<?php include_partial('global/header',array('user' => $user, 'cfg' => $cfg )) ?>
<?php #include_partial('global/toolbar',array('geo' => $param['geo']) ) ?>
<?php include_partial('path',array('geo' => $geo)) ?>
<div id="hmMainX">
  <?php include_partial('actButtons', array('loc'=>$loc, 'geo'=>$geo) ) ?>
<?php include_partial('activityAll', array('recs' => $activities, 'act' => $act, 'loc'=>$loc, 'geo'=>$geo) ) ?>
  <div id="fbMap" class="rsMap fbBorder2"></div>
  <?php include_partial('city_head',array('catch' => $catch, 'sites_weight' => $sites_weight, 'city' => $city, 'cur_month' => $cur_month)) ?>
  <?php include_partial('fish_top_box',array('catch' => $catch, 'city' => $city, 'geo' => $geo, 'cur_month' => $cur_month) ) ?>
  <?php include_partial('fish_box',array('fish_rand' => $fish_rand,'geo' => $geo, 'city' => $city, 'cur_month' => $cur_month)) ?>
  <?php include_partial('site_box',array('sites_rand' => $sites_rand, 'city' => $city, 'cur_month' => $cur_month) ) ?>
  <?php #include_partial('activity', array('activities' => $activities, 'actAll' => $actAll, 'loc'=>$loc, 'geo'=>$geo) ) ?>

  <div class="clear"></div>

</div>
<?php include_partial('footer',$footer ) ?>
