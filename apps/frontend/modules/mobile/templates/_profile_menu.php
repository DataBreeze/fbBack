<ul data-role="listview"<?php if($data-inset) : ?> data-inset="true" <?php endif ?>data-theme="c" data-dividertheme="<?php echo $data-dividertheme ?>">
  <li data-role="list-divider">FishBlab Profiles</li>
  <li<?php if($select[1]) : ?> data-theme="a"<?php endif ?>><a href="http://m.fishblab.com/user">Users</a></li>
  <li<?php if($select[2]) : ?> data-theme="a"<?php endif ?>><a href="http://m.fishblab.com/group">Groups</a></li>
  <li<?php if($select[3]) : ?> data-theme="a"<?php endif ?>><a href="http://m.fishblab.com/profile">Your Profile</a></li> 
</ul>
