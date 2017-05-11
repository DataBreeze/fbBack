<div class="colmask doublepage">
  <div class="colleft">
    <div class="col1 fbBorder5B">
      <?php include_partial('global/search',array('geo' => $geo)) ?>
      <div id="fbMap"></div>
    </div>
    <div class="col2 fbBorder5B">
      <div class="dataBox">
        <?php include_partial('data_msg',array()) ?>
        <?php include_partial($data_template, array('data' => $data, 'friends' => $friends, 'groups' => $groups, 'user' => $user)) ?>
      </div>
    </div>
  </div>
</div>

