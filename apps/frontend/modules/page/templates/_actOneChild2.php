
  <?php if($act['showFish']) : ?>
    <?php include_partial('actChild', array('recs' => $rec['fishes'], 'act' => $actAll['fish']) ) ?>
  <?php endif ?>

  <?php if($act['showReport']) : ?>
    <?php include_partial('actChild', array('recs' => $rec['reports'], 'act' => $actAll['report']) ) ?>
  <?php endif ?>

  <?php if($act['showBlog']) : ?>
    <?php include_partial('actChild', array('recs' => $rec['blogs'], 'act' => $actAll['blog']) ) ?>
  <?php endif ?>

  <?php if($act['showSpot']) : ?>
    <?php include_partial('actChild', array('recs' => $rec['spots'], 'act' => $actAll['spot']) ) ?>      
  <?php endif ?>

  <?php if($act['showDisc']) : ?>
    <?php include_partial('actChild', array('recs' => $rec['discs'], 'act' => $actAll['disc']) ) ?>      
  <?php endif ?>

  <?php if($act['showGroup']) : ?>
    <?php include_partial('actChild', array('recs' => $rec['groups'], 'act' => $actAll['group']) ) ?>      
  <?php endif ?>

  <?php if($act['showUser']) : ?>
    <?php include_partial('actChild', array('recs' => $rec['users'], 'act' => $actAll['user']) ) ?>      
  <?php endif ?>

  <?php if($act['showPhoto']) : ?>
    <?php include_partial('actChildPhoto', array('recs' => $rec['photos'], 'act' => $actAll['photo']) ) ?>
  <?php endif ?>
