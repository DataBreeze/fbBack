<?php include_partial('global/header',array('user' => $user, 'cfg' => $cfg )) ?>
<?php #include_partial('global/toolbar',array('geo' => $param['geo']) ) ?>
<?php include_partial('path',array('geo' => $geo)) ?>
<div id="hmMainX">
  <?php include_partial('actButtons', array('loc'=>$loc, 'geo'=>$geo) ) ?>
<?php include_partial('activityAll', array('recs' => $activities, 'act' => $act, 'loc'=>$loc, 'geo'=>$geo) ) ?>
  <div id="fbMap" class="rsMap fbBorder2"></div>

  <div class="gwBox fbBorder2">
    <?php $count = count($fish_weight); if($count > 5){ $count = 5; } ?>
    <p>
      The most popular fish caught at <?php echo $site['name'].', '.$site['city'] ?> in <?php echo $cur_month ?> are
      <?php for($i=0;$i<$count;$i++): ?><?php $fish = $fish_weight[$i]; if($i != 0){ echo ', '; } if( $i == ($count - 1)){ echo ' and ' . $fish['name'] . '.'; }else{ echo $fish['name']; } ?><?php endfor ?>
	    </p>
  </div>

  <div class="gwBox fbBorder2">
    <h1>Fish in <?php echo $site['name'] ?></h1>
    <?php include_partial('fish_links_tags',array('fish_rand' => $fish_rand,'geo' => $geo) ) ?>
  </div>
  
  <div class="gwBox fbBorder2">
    <h1>Hot Fish in <?php echo $site['name'] ?> in <?php echo $cur_month ?></h1>
    <?php include_partial('fish_top',array('catch' => $catch) ) ?>
  </div>

  <?php #include_partial('activity', array('activities' => $activities, 'actAll' => $actAll, 'loc'=>$loc, 'geo'=>$geo) ) ?>

  <div class="clear"></div>

</div>
<?php include_partial('footer',$footer) ?>
