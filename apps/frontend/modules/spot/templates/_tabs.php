<div id="fbTabs">

  <ul>
    <li><a class="tabLab" href="#tabSpot" rel="nofollow">Spots</a></li>
    <li><a class="tabLab" href="#tabFilter" rel="nofollow">Filter</a></li>
  </ul>

  <div id="tabSpot" class="fbTab">
    <?php include_partial('global/spots',array('spot' => $spot, 'user' => $user)) ?>
  </div>

  <div id="tabFilter" class="fbTab">
    <?php include_partial('global/filter',array('user' => $user)) ?>
  </div>

</div>
