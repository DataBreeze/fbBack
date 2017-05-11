<?php include_partial('global/top_header_no_search',array('user' => $user )) ?>
<?php include_partial('banner') ?>
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
<?php include_partial('footer',$footer ) ?>
