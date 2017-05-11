<div>

  <div id="groupHead" class="XlistHead XfbBorder2">
    <div id="groupHeadText" class="listHeadText floatLeft">
        FishBlab Groups this User is a Member of
    </div>
    <div class="clear"></div>
  </div>

  <div id="groupList" class="loc_pane">
    <table id="group_table" class="loc" cellspacing="0" cellpadding="0">
     <thead>
      <tr>
        <th>Name</th><th>Caption</th><th>Location</th><th>Type</th>
      </tr>
     </thead>
     <tbody id="group_body">
      <?php foreach ($groups as $i => $group): ?>
      <?php $gid = $group['id']; ?>
      <tr id="groupRow_<?php echo $gid ?>" class="pointer" onclick="groupLoadProfile('<?php echo urlencode($group['name']) ?>');return false;" onmouseover="$(this).addClass('discRowA');" onmouseout="$(this).removeClass('discRowA');">
        <td><?php echo $group['name'] ?></td>
        <td><?php echo $group['caption'] ?></td>
        <td><?php echo $group['location'] ?></td>
        <td><?php echo $group['gtype_text'] ?></td>
      </tr>
      <?php endforeach ?>
     </tbody>
    </table>
  </div>

</div>