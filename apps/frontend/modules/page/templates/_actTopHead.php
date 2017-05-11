<h1>
  <?php if($loc['city']) : ?>
    <?php $href = 'http://' . $act['host'] . '.fishblab.com/' . $loc['state'] . '/' . urlencode($loc['city']) ?>
    <?php $locStr = $loc['city'] ?>
  <?php elseif($loc['state']) : ?>
    <?php $href = 'http://' . $act['host'] . '.fishblab.com/' . $loc['state'] ?>
    <?php $locStr = $loc['state'] ?>
  <?php elseif($loc['country']) : ?>
    <?php $href = 'http://' . $act['host'] . '.fishblab.com/' ?>
    <?php $locStr = $loc['country'] ?>
  <?php else : ?>
    <?php $href = 'http://' . $act['host'] . '.fishblab.com/' ?>
  <?php endif ?>
  <?php $str = $act['namePlural'] .'('. $recs['count_total'] .')' ?>
  <?php if($locStr) : ?>
    <?php $str .= ' in ' . $locStr ?>
  <?php endif ?>
  <a class="white" href="<?php echo $href ?>"><?php echo $str ?></a>
</h1>
