<div>

  <div id="groupHead" class="listHead fbBorder2">
    <div id="groupHeadText" class="listHeadText floatLeft">
      <div>
        <?php echo $groups['count_total'] ?> FishBlab Groups in current map
      </div>
    </div>
    <div class="floatRight">
      <button class="fbButton fbButBig" onclick="groupNewShow();return false;">New Group</button>
    </div>
    <div class="clear"></div>
  </div>

  <div id="groupList" class="loc_pane">
    <table id="group_table" class="loc" cellspacing="0" cellpadding="0">
     <thead>
      <tr>
        <th>Map</th><th>Name</th><th>Caption</th><th>Profile</th><th>Type</th>
      </tr>
     </thead>
     <tbody id="group_body">
      <?php foreach ($groups['records'] as $i => $group): ?>
      <?php $gid = $group['id']; ?>
      <tr id="groupRow_<?php echo $gid ?>" class="pointer" onmouseover="$(this).addClass('discRowA');" onmouseout="$(this).removeClass('discRowA');">
        <td class="blue" onclick="groupMapShow(<?php echo $gid ?>);return false;">Map</td>
        <td onclick="groupPopShowById(<?php echo $gid ?>);return false;"><?php echo $group['name'] ?></td>
        <td onclick="groupPopShowById(<?php echo $gid ?>);return false;"><?php echo $group['caption'] ?></td>
        <td class="blue" onclick="groupLoadProfile('<?php echo urlencode($group['name']) ?>');return false;">Detail</td>
        <td onclick="groupPopShowById(<?php echo $gid ?>);return false;"><?php echo $group['gtype_text'] ?></td>
      </tr>
      <?php endforeach ?>
     </tbody>
    </table>
  </div>

</div>