<div id="fbTabs">

  <ul>
    <li><a class="tabLab" href="#tabCatch" rel="nofollow">Fish Catch</a></li>
    <li><a class="tabLab" href="#tabFilter" rel="nofollow">Filter</a></li>
  </ul>

  <div id="tabCatch" class="fbTab">
    <?php include_partial('global/reports',array('report' => $report, 'user' => $user)) ?>
  </div>

  <div id="tabFilter" class="fbTab">
    <?php include_partial('global/filter',array('user' => $user)) ?>
  </div>

</div>
