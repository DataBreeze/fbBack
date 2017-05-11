<div id="fbTabs">

  <ul>
    <li><a class="tabLab" href="#tabDiscuss" rel="nofollow">Discuss</a></li>
    <li><a class="tabLab" href="#tabFilter" rel="nofollow">Filter</a></li>
  </ul>

  <div id="tabDiscuss" class="fbTab">
    <div class="fbTabCap">Local Fishing Discussion</div>
    <?php include_partial('global/discussion',array('discuss' => $discuss, 'user' => $user)) ?>
  </div>

  <div id="tabFilter" class="fbTab">
    <?php include_partial('global/filter',array('user' => $user)) ?>
  </div>

</div>
