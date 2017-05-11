<ul data-role="listview"<?php if($data-inset) : ?> data-inset="true" <?php endif ?>data-theme="c" data-dividertheme="<?php echo $data-dividertheme ?>">
  <li data-role="list-divider">Fishing Activity</li>
  <li<?php if($select[1]) : ?> data-theme="a"<?php endif ?>><a href="http://m.fishblab.com/photo">Fishing Photos</a></li>
  <li<?php if($select[2]) : ?> data-theme="a"<?php endif ?>><a href="http://m.fishblab.com/catch">Fish Catch</a></li>
  <li<?php if($select[3]) : ?> data-theme="a"<?php endif ?>><a href="http://m.fishblab.com/report">Fishing Reports</a></li>
  <li<?php if($select[4]) : ?> data-theme="a"<?php endif ?>><a href="http://m.fishblab.com/spot">Fishing Spots</a></li>
  <li<?php if($select[5]) : ?> data-theme="a"<?php endif ?>><a href="http://m.fishblab.com/discuss">Discussion</a></li>
</ul>
