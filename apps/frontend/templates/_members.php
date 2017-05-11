<div>
  <div id="memberHead" class="listHead fbBorder2">
    <div id="memberHeadText" class="listHeadText floatLeft">
      Group Members
    </div>
    <div class="floatRight">
     <?php if($group['is_admin']): ?>
      <button class="fbButton fbButBig" onclick="groupMemFind(<?php echo $group['id'] ?>);return false;">Add Member</button>
     <?php endif ?>
    </div>
    <div class="clear"></div>
  </div>

  <div class="loc_pane">
    <table id="member_table" class="loc" cellspacing="0" cellpadding="0">
      <thead>
        <tr>
          <th>Username</th><th>Name</th><th>Location</th><th>Member Type</th>
        </tr>
      </thead>
      <tbody id="member_body">
        <?php foreach ($members['records'] as $i => $member): ?>
        <tr class="pointer" onclick="groupMemShow(<?php echo $group['id'] ?>,'<?php echo $member['username'] ?>');return false;" onmouseover="$(this).addClass('discRowA');" onmouseout="$(this).removeClass('discRowA');">
          <td><?php echo $member['username'] ?></td>
          <td><?php echo $member['firstname'] .' '. $member['lastname'] ?></td>
          <td><?php echo $member['location'] ?></td>
	 <td><?php echo ($member['member_status'] == 'owner' ? 'Owner' : ($member['member_status'] == 'admin' ? 'Admin' : ($member['member_status'] == 'member' ? 'Member' : 'Unknown') ) ) ?></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>

</div>
