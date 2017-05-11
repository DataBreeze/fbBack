<?php include_partial('global/header',array('geo' => $geo, 'user' => $user, 'cfg' => $cfg )) ?>
<?php include_partial('actButtons', array('loc'=>$loc, 'geo'=>$geo) ) ?>
<?php include_partial('actPath',array('act' => $act,'rec' => $rec, 'loc' => $loc) ) ?>
<div id="hmMain">

  <div class="gwBox mar5 fbBorder2">
    <?php include_partial('activityHead',array('act' => $act, 'rec' => $rec) ) ?>
    <?php include_partial('rec_gw',array('act' => $act, 'rec' => $rec) ) ?>
  </div>

  <?php if($rec['detail']) : ?>
  <div class="gwBox fbBorder2">
    <h1>About <?php echo $rec['name'] ?></h1>
    <p><?php echo htmlspecialchars_decode($rec['detail']) ?></p>
  </div>
  <?php endif ?>

  <?php if( count($catch) > 0 ) : ?>
  <div class="gwBox fbBorder2">
    <h1>Annual Catch</h1>
    <?php include_partial('fish_one_chart',array('catch' => $catch,'geo' => $geo) ) ?>
  </div>
  <?php endif ?>

  <?php if( count($sites_top) ) : ?>
  <div class="gwBox fbBorder2">
    <?php include_partial('fish_site_top',array('sites_top' => $sites_top, 'rec' => $rec, 'geo' => $geo) ) ?>
  </div>
  <?php endif ?>

  <?php if( count($cities_top) ) : ?>
  <div class="gwBox fbBorder2">
    <?php include_partial('fish_city_top',array('cities_top' => $cities_top, 'rec' => $rec, 'geo' => $geo) ) ?>
  </div>
  <?php endif ?>


  
     <?php if( (! $rec['detail']) and $rec['wiki_text']) : ?>
  <div class="clear"></div>
  <div class="fbBorder2 mar5">
    <?php echo htmlspecialchars_decode($rec['wiki_text']) ?>
  </div>
  <?php endif ?>

  <div class="clear"></div>

</div>
<div class="marBot20">
  <?php include_partial('footer',$footer) ?>
</div>
