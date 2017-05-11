<div class="gwPath">
  <a href="http://www.fishblab.com/">FishBlab</a>
  <?php if($loc['city']) : ?>
    <?php $path = '/' . $loc['state'] . '/' . urlencode($loc['city']) ?>
  <?php elseif($loc['state']) : ?>
    <?php $path = '/' . $loc['state'] ?>
  <?php else : ?>
    <?php $path = False ?>
  <?php endif ?>
  <?php if($path) : ?>
  &gt; <a href="http://www.fishblab.com<?php echo $path ?>"><?php echo $loc['input'] ?></a>
  <?php endif ?>
  <?php if($rec) : ?>
    &gt;
    <a href="http://<?php echo $act['host'] ?>.fishblab.com<?php echo $path ?>"><?php echo $act['namePlural'] ?></a>
    &gt; <?php echo $rec[$act['displayName']] ?>
  <?php elseif($recs) : ?>
    &gt; <?php echo $act['namePlural'] ?>
  <?php endif ?>
  <span id="jrjMapToggle"></span>
  <br class="clear" />
</div>
