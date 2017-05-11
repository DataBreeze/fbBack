<?php include_partial('global/header',array('user' => $user, 'cfg' => $cfg )) ?>
<?php include_partial('banner') ?>
<?php include_partial('path',array('geo' => $geo)) ?>
<div id="hmMain">
  <div id="fbTabs">
    <ul>
      <li><a href="#tabs-0">Popular Fish</a></li>
      <li><a href="#tabs-1">Fish List</a></li>
    </ul>
    <div id="tabs-0" class="fbTab">
    <?php include_partial('fish_links_tags',array('fish_rand' => $fish_rand, 'count' => $count, 'count_total' => $count_total) ) ?>
    </div>
    <div id="tabs-1" class="fbTab">
      <?php include_partial('fish_links_list',array('fish_alpha' => $fish_alpha, 'count' => $count, 'count_total' => $count_total) ) ?>
    </div>
  </div>
</div>
<?php include_partial('footer', $footer) ?>
