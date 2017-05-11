<div>

  <div id="userHead" class="listHead fbBorder2">
    <div id="userHeadText" class="listHeadText floatLeft">
      <div>
        <?php echo $users['count_total'] ?> FishBlab Users in current map
      </div>
    </div>
    <div class="floatRight">
     <?php if($user['id']): ?>
      <a class="fbButton" href="http://user.fishblab.com/<?php echo urlencode($user['username']) ?>">View My Profile</a>
     <?php endif ?>
    </div>
    <div class="clear"></div>
  </div>

  <div id="userList" class="loc_pane">
    <table id="user_table" class="loc" cellspacing="0" cellpadding="0">
      <tr>
        <th>Username</th><th>Name</th><th>Location</th><th>UserType</th>
      </tr>
      <?php foreach ($users['records'] as $i => $puser): ?>
      <tr id="userRow_<?php echo $puser['id'] ?>" class="pointer" onclick="userPubShow('<?php echo $puser['username'] ?>');return false;" onmouseover="$(this).addClass('discRowA');" onmouseout="$(this).removeClass('discRowA');">
        <td><a href="http://user.fishblab.com/<?php echo urlencode($puser['username']) ?>" onclick="userPubShow('<?php echo $puser['username'] ?>');return false;"><?php echo $puser['username'] ?></a></td>
        <td><?php echo $puser['firstname'] .' '. $puser['lastname'] ?></td>
        <td><?php echo $puser['location'] ?></td>
        <td><?php echo $puser['utype_text'] ?></td>
      </tr>
      <?php endforeach ?>
    </table>
  </div>

</div>