<div id="fbTabs">

  <ul>
    <li><a href="#tabGroup" rel="nofollow">Public</a></li>
    <li><a href="#tabMember" rel="nofollow">Members</a></li>
    <li><a href="#tabPhoto" rel="nofollow">Photo</a></li>
  </ul>

  <div id="tabGroup" class="fbTab fbBorder2">
    <div class="uRBox fbBorder5">
      <div>
        <span class="uRCap">FishBlab Public Group Information</span>
        <?php if($member['sec'] > 49) : ?>
        <button class="fbButton marLeft10" onclick="groupEditInit();return false;">Edit</button>
        <?php endif ?>
      </div>
      <div id="groupPublic">
       <table class="userInfo">
        <tr><td>
          <label>Name:</label>
          <span><?php echo $group['name'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>Website:</label>
          <span><?php echo $group['website'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>Location:</label>
          <span><?php echo $group['location'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>Group Type:</label>
          <span><?php echo $group['gtype_text'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>Created:</label>
          <span><?php echo $group['date_create'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <?php if($group['about']): ?>
        <tr><td>
          <div class="userInfoAbout"><?php echo $group['about'] ?></div>
          <div class="clear"></div>
        </td></tr>
        <?php endif ?>
       </table>
      </div>
    </div>

    <div class="uRBox fbBorder5">
      <div>
        <span class="uRCap">Current Location</span>
        <button class="fbButton mar10" onclick="groupOpenWin();return false;">Show</button>
        <button class="fbButton mar10" onclick="groupEditLoc();return false;">Change</button>
      </div>
    </div>
  </div>

  <div id="tabMember" class="fbTab">
    <?php include_partial('global/members',array('members' => $members, 'user' => $user)) ?>
  </div>

  <div id="tabPhoto" class="fbTab">
    <div id="userPhoto" class="uRBox fbBorder5">
     <?php if($group['photo_id']): ?>
      <div uRRow>
        <span class="uRCap">Public Profile Photo</span>
        <button class="fbButton marLeft10" onclick="userPhotoDelete(<?php echo $group['photo_id'] ?>);return false;">Remove Photo</button>
      </div>
      <div class="uRRow">
       <img src="<?php echo  fbLib::wwwFilePath($group['photo_id']) ?>300x300.jpg" height="300" width="300" class="uImg"/>
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
  </div>

</div>
