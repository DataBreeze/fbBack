<?php include_partial('global/header',array('geo' => $geo, 'user' => $user, 'cfg' => $cfg )) ?>
<div class="mhMain">
  <div class="pad5 centerText">
    <a class="gwStateSelect" href="http://www.fishblab.com/allStates">Select a State</a>
    <?php include_partial('stateSelect', array('states' => $states) ) ?>
  </div>
  <?php include_partial('actButtons', array('loc'=>$loc, 'geo'=>$geo) ) ?>
</div>
<?php include_partial('activityAll', array('recs' => $activities, 'act' => $act, 'loc'=>$loc, 'geo'=>$geo) ) ?>
<div class="gwBox fbBorder3">
  <h1>Hot Fish in <?php echo $cur_month ?></h1>
  <?php include_partial('fish_top',array('catch' => $catch) ) ?>
</div>

<div class="gwBox fbBorder2">
  <h1>
    Hot Cities
    <i><a class="white" href="http://www.fishblab.com/allCities">more &gt;</a></i>
  </h1>
  <?php include_partial('city_links_tags',array('city_rand' => $city_rand) ) ?>
  </div>

<div class="gwBox fbBorder3">
  <h1>
    <a class="white" href="http://fish.fishblab.com/">Popular Fish</a>
    <i><a class="white" href="http://fish.fishblab.com/">more &gt;</a></i>
  </h1>
  <?php include_partial('fish_links_tags',array('fish_rand' => $fish_rand) ) ?>
  </div>

<div class="clear"></div>

<?php include_partial('footer',$footer) ?>
