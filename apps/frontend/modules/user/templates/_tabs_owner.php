<div id="fbTabs">

  <ul>
    <li><a href="#tabUser" rel="nofollow">Public</a></li>
    <li><a href="#tabFriend" rel="nofollow">Friends</a></li>
    <li><a href="#tabGroup" rel="nofollow">Groups</a></li>
    <li><a href="#tabOption" rel="nofollow">Options</a></li>
    <li><a href="#tabPhoto" rel="nofollow">Photo</a></li>
  </ul>

  <div id="tabUser" class="fbTab fbBorder2">
    <div class="uRBox fbBorder5">
      <div>
        <span class="uRCap">FishBlab Public User Information</span>
        <button class="fbButton marLeft10" onclick="userEditInit();return false;">Edit</button>
      </div>
      <div id="userPublic">
       <table class="userInfo">
        <tr><td>
          <label>Username:</label>
          <span><?php echo $user['username'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>Name:</label>
          <span><?php echo $user['firstname'] ?> <?php echo $user['lastname'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>Title:</label>
          <span><?php echo $user['title'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>Website:</label>
          <span><?php echo $user['website'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>Location:</label>
          <span><?php echo $user['location'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>User Type:</label>
          <span><?php echo $user['utype_text'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>Member Since:</label>
          <span><?php echo $user['date_create'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <?php if($user['about']): ?>
        <tr><td>
          <div class="userInfoAbout"><?php echo $user['about'] ?></div>
          <div class="clear"></div>
        </td></tr>
        <?php endif ?>
       </table>
      </div>
    </div>

    <div class="uRBox fbBorder5">
      <div>
        <span class="uRCap">Current Location</span>
        <button class="fbButton mar10" onclick="openUserWin();return false;">Show</button>
        <button class="fbButton mar10" onclick="userEditLoc();return false;">Change</button>
      </div>
    </div>
    <?php include_partial('tabs_owner_buttons',array( 'user' => $user )) ?>
  </div>

  <div id="tabFriend" class="fbTab">
    <?php include_partial('global/friends',array('friends' => $friends, 'user' => $user)) ?>
    <?php include_partial('tabs_owner_buttons',array( 'user' => $user )) ?>
  </div>

  <div id="tabGroup" class="fbTab">
    <?php include_partial('global/groups_for_user',array('groups' => $groups, 'user' => $user)) ?>
    <?php include_partial('tabs_owner_buttons',array( 'user' => $user )) ?>
  </div>

  <div id="tabOption" class="fbTab">
    <div class="uRBox fbBorder5">
      <div>
        <span class="uRCap">FishBlab Account Settings</span>
        <button class="fbButton mar10" onclick="userEditInit();return false;">Edit</button>
      </div>
      <div class="uRRow">
        <div class="uRLab">Email:</div>
        <div class="uRVal"><?php echo $user['email'] ?></div>
        <div class="clear"></div>
      </div>
    </div>
    <div class="uRBox fbBorder5">
      <div>
        <span class="uRCap">FishBlab notification settings</span>
        <button class="fbButton mar10" onclick="msgManage();return false;">Edit</button>
      </div>
      <div id="msgOptions">
        <div class="uRRow">
          <div class="uRLab2">Notify when someone replies to my Post:</div>
          <div class="uRVal2<?php echo ($user['msg_disc'] == True ? ' green' : ' red') ?>"><?php echo ($user['msg_disc'] == 1 ? 'Yes' : 'No') ?></div>
        </div>
        <div class="uRRow">
          <div class="uRLab2">Notify when someone replies after I Reply to any Post:</div>
          <div class="uRVal2<?php echo ($user['msg_disc'] == True ? ' green' : ' red') ?>"><?php echo ($user['msg_reply'] == 1 ? 'Yes' : 'No') ?></div>
        </div>
        <div class="uRRow">
          <div class="uRLab2">Send me weekly FishBlab.com Updates:</div>
          <div class="uRVal2<?php echo ($user['msg_disc'] == True ? ' green' : ' red') ?>"><?php echo ($user['msg_update'] == 1 ? 'Yes' : 'No') ?></div>
        </div>
        <div class="uRRow">
          <div class="uRLab2 red">Stop ALL FishBlab.com emails:</div>
          <div class="uRVal2<?php echo ($user['msg_disc'] == True ? ' red' : ' green') ?>"><?php echo ($user['msg_stop'] == 1 ? 'Yes' : 'No') ?></div>
        </div>
      </div>
    </div>
    <?php include_partial('tabs_owner_buttons',array( 'user' => $user )) ?>
  </div>

  <div id="tabPhoto" class="fbTab">
    <div id="userPhoto" class="uRBox fbBorder5">
     <?php if($user['photo_id']): ?>
      <div uRRow>
        <span class="uRCap">Public Profile Photo</span>
        <button class="fbButton marLeft10" onclick="userPhotoDelete(<?php echo $user['photo_id'] ?>);return false;">Remove Photo</button>
      </div>
      <div class="uRRow">
       <img src="<?php echo  fbLib::wwwFilePath($user['photo_id']) ?>300x300.jpg" height="300" width="300" class="uImg"/>
      </div>
     <?php else: ?>
      <div>
        <span class="uRCap red">
          No Profile photo
        </span>
        <button class="fbButton marLeft10" onclick="userPhotoUpload();return false;">Upload Photo</button>
      </div>
      <div class="uEImgRow">
       <img src="<?php echo  fbLib::wwwFilePath(332) ?>300x300.jpg" height="300" width="300" class="uImg"/>
      </div>
     <?php endif ?>
      <div class="clear"></div>
    </div>
    <?php include_partial('tabs_owner_buttons',array( 'user' => $user )) ?>
  </div>

</div>
