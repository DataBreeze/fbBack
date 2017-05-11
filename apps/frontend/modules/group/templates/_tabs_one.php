<?php $group = $groups['records'][0]; ?>
<?php if($group['is_member']){$but_text = 'Your Status';}elseif($group['sec'] == 1){$but_text = 'Join Group';}elseif($group['sec'] == 5){$but_text = 'Request Membership';}else{$but_text = 'Closed Membership';} ?>
<div id="fbTabs">
  <ul>
    <li><a href="#tabGroup" rel="nofollow">Group Info</a></li>
    <li><a href="#tabMember" rel="nofollow">Members</a></li>
  </ul>

  <div id="tabGroup" class="fbTab fbBorder2">

    <div id="memberHead" class="listHead fbBorder2">
      <div id="memberHeadText" class="listHeadText floatLeft">
        FishBlab Group Info
      </div>
      <div class="floatRight" id="memberStatusBox">
        <button class="fbButton fbButBig" onclick="groupStatusShow(<?php echo $group['id'] ?>);return false;"><?php echo $but_text ?></button>
      </div>
      <div class="clear"></div>
    </div>

    <div class="uRBox fbBorder5">
      <div>
        <?php if($group['is_admin']) : ?>
        <button class="fbButton marLeft10" onclick="groupEditShow(<?php echo $group['id'] ?>);return false;">Edit</button>
        <?php endif ?>
        <div class="marTop10">
        </div>
      </div>
      <div id="groupPublic">
       <table class="userInfo">
        <tr><td>
          <label>Name:</label>
          <span><?php echo $group['name'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>Caption:</label>
          <span><?php echo $group['caption'] ?></span>
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
          <label>Fish Species:</label>
          <span><?php echo $group['fish'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>Created:</label>
          <span><?php echo $group['date_create'] ?></span>
          <div class="clear"></div>
        </td></tr>
        <tr><td>
          <label>About:</label>
          <div class="userInfoAbout"><?php echo $group['about'] ?></div>
          <div class="clear"></div>
        </td></tr>
       </table>
      </div>
    </div>

  </div>

  <div id="tabMember" class="fbTab">
	   <?php include_partial('global/members',array('members' => $members, 'group' => $group, 'user' => $user)) ?>
  </div>


</div>
