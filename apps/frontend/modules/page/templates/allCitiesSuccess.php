<?php include_partial('global/header',array('user' => $user, 'cfg' => $cfg  )) ?>
<?php include_partial('path',array('geo' => $geo)) ?>
<div id="hmMain">

  <div id="fbTabs">
    <ul>
      <li><a href="#tabs-0">Popular Fishing Cities</a></li>
      <li><a href="#tabs-1">Fishing Cities by List</a></li>
    </ul>
    <div id="tabs-0" class="fbTab">
    <?php include_partial('city_links_tags',array('city_rand' => $city_rand, 'count' => $count, 'count_total' => $count_total)) ?>
    </div>
    <div id="tabs-1" class="fbTab">
      <?php include_partial('city_links_list',array('city_alpha' => $city_alpha, 'count' => $count, 'count_total' => $count_total) ) ?>
    </div>
  </div>
</div>
<?php include_partial('footer', $footer) ?>
