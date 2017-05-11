    <div class="fbTabCap">Friends</div>
    <div class="loc_pane">
      <table id="loc_table" class="loc" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th>Username</th><th>Name</th><th>Location</th><th>UserType</th>
          </tr>
        </thead>
        <tbody id="site_body">
        <?php foreach ($friends as $i => $friend): ?>
           <tr class="pointer" onclick="userPubShow('<?php echo $friend['username'] ?>');return false;" onmouseover="$(this).addClass('discRowA');" onmouseout="$(this).removeClass('discRowA');">
             <td><a href="http://user.fishblab.com/<?php echo urlencode($friend['username']) ?>" onclick="userPubShow('<?php echo $friend['username'] ?>');return false;"><?php echo $friend['username'] ?></a></td>
             <td><?php echo $friend['firstname'] .' '. $friend['lastname'] ?></td>
             <td><?php echo $friend['location'] ?></td>
             <td><?php echo $friend['utype_text'] ?></td>
          </tr>
        <?php endforeach ?>
        </tbody>
      </table>
    </div>
