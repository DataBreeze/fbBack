<div id="fbTabs">

  <ul>
    <li><a class="tabLab" href="#tabReport" rel="nofollow">Report</a></li>
    <li><a class="tabLab" href="#tabFilter" rel="nofollow">Filter</a></li>
  </ul>

  <div id="tabReport" class="fbTab">
    <?php include_partial('global/blogs',array('blog' => $blog, 'user' => $user)) ?>
  </div>

  <div id="tabFilter" class="fbTab">
    <?php include_partial('global/filter',array('user' => $user)) ?>
  </div>

</div>
