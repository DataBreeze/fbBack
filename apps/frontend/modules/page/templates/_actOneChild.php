<div id="actOneChildBox" class="gwActOneChild">
  <?php if($act['showReply']) : ?>
   <?php include_partial('actChildRead', array('recs' => $rec['replies'], 'act' => $actAll['reply'], 'actParent' => $act, 'recParent' => $rec) ) ?>
  <?php endif ?>
  
  <?php if($act['showFish']) : ?>
    <?php include_partial('actChild', array('recs' => $rec['fishes'], 'act' => $actAll['fish']) ) ?>
  <?php endif ?>
  
  <?php if($act['showPhoto']) : ?>
    <?php include_partial('actChildPhoto', array('recs' => $rec['photos'], 'act' => $actAll['photo']) ) ?>
  <?php endif ?>
</div>
