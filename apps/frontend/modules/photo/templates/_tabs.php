<div id="fbTabs">

  <ul>
    <li><a class="tabLab" href="#tabPhoto" rel="nofollow">Photos</a></li>
    <li><a class="tabLab" href="#tabFilter" rel="nofollow">Filter</a></li>
  </ul>

  <div id="tabPhoto" class="fbTab">
    <?php include_partial('global/photos',array('photo' => $photo, 'user' => $user)) ?>
  </div>

  <div id="tabFilter" class="fbTab">
    <?php include_partial('global/filter',array('user' => $user)) ?>
  </div>

</div>
