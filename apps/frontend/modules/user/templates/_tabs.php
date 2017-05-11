<?php $puser = $data; ?>
<div id="fbTabs">

  <ul>
    <li><a href="#tabUser" rel="nofollow">User Info</a></li>
    <li><a href="#tabFriend" rel="nofollow">Friends</a></li>
    <li><a href="#tabGroup" rel="nofollow">Groups</a></li>
  </ul>

  <div id="tabUser" class="fbTab fbBorder2">
    <div class="uRBox fbBorder5">
      <div>
        <span class="uRCap">FishBlab User Profile for <?php echo $puser['username'] ?></span>
        <button class="fbButton marLeft10" onclick="userFriOption('<?php echo $puser['username'] ?>');return false;"><?php echo ($puser['friend_status'] == 'friend' ? 'Remove' : 'Add') ?> Friend</button>
        <button class="fbButton marLeft10" onclick="openUserWinBUN('<?php echo $puser['username'] ?>');return false;">Map</button>
      </div>

     <?php if($puser['photo_id']): ?>
      <div class="uRRow">
       <img src="<?php echo  fbLib::wwwFilePath($puser['photo_id']) ?>200x200.jpg" height="200" width="200" class="uImg"/>
      </div>
     <?php else: ?>
      <div class="uRRow">
       <img src="<?php echo  fbLib::wwwFilePath(332) ?>200x200.jpg" height="200" width="200" class="uImg"/>
      </div>
     <?php endif ?>
      <div class="clear"></div>
      <div id="userPublic">
       <table class="userInfo">
        <tr><td>
          <label>Username:</label>
          <span><?php echo $puser['username'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>Name:</label>
          <span><?php echo $puser['firstname'] ?> <?php echo $puser['lastname'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>Title:</label>
          <span><?php echo $puser['title'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>Website:</label>
          <span><?php echo $puser['website'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>Location:</label>
          <span><?php echo $puser['location'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>User Type:</label>
          <span><?php echo $puser['utype_text'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>Member Since:</label>
          <span><?php echo $puser['date_create'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <?php if($puser['about']): ?>
        <tr><td>
          <div class="userInfoAbout"><?php echo $puser['about'] ?></div>
          <div class="clear"></div>
        </td></tr>
        <?php endif ?>
       </table>
      </div>
    </div>
  </div>

  <div id="tabFriend" class="fbTab">
    <?php include_partial('global/friends',array('friends' => $friends, 'user' => $puser)) ?>
  </div>

  <div id="tabGroup" class="fbTab">
    <?php include_partial('global/groups_for_user',array('groups' => $groups, 'user' => $puser)) ?>
  </div>

</div>
