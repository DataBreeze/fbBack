<div class="colmask doublepage">
  <div class="colleft">
    <div class="col1 fbBorder5B">
      <?php include_partial('global/search',array('geo' => $param['geo'])) ?>
      <div id="fbMap"></div>
    </div>
    <div class="col2 fbBorder5B">
      <div class="dataBox">
        <?php include_partial('global/data_msg',array()) ?>
        <?php include_partial($param['template'],array($param)) ?>
      </div>
    </div>
  </div>
</div>

