<?php $count = $recs['count']; ?>
<div class="fbBorder2 actAll">
  <h1>Latest Fishing Activity:</h1>
  <table class="loc">
    <tr>
      <th class="listIcon">Type</th>
      <th>Date</th>
      <th>User</th>
      <th>Caption</th>
    </tr>
    <?php for($i=0; $i < $count; $i++): ?>
          <?php $rec = $recs['records'][$i]; ?>
    <tr class="bgEFEFEF" onmouseover="this.className='jrjActive';" onmouseout="this.className='jrjInactive';" onclick="location.href='http://<?php echo $rec['jrjHost'] ?>.fishblab.com/<?php if($loc['state']){ echo $loc['state'] .'/'; } ?><?php if($loc['city']){ echo urlencode($loc['city']) .'/'; } ?><?php echo $rec[$act['keyName']] ?>';">
      <td class="listIcon"><a href="http://<?php echo $rec['jrjHost'] ?>.fishblab.com/<?php if($loc['state']){ echo $loc['state'] .'/'; } ?><?php if($loc['city']){ echo urlencode($loc['city']) .'/'; } ?><?php echo $rec[$act['keyName']] ?>"><span class="gwListIcon"><img src="<?php echo $rec['jrjIcon'] ?>" alt="<?php echo $rec['jrjHost'] ?>"/></span></a><b><?php echo $rec['fb_source'] ?></b></td>
      <td><a href="http://<?php echo $rec['jrjHost'] ?>.fishblab.com/<?php if($loc['state']){ echo $loc['state'] .'/'; } ?><?php if($loc['city']){ echo urlencode($loc['city']) .'/'; } ?><?php echo $rec[$act['keyName']] ?>"><?php echo $rec['date'] ?></a></td>
      <td><a href="http://<?php echo $rec['jrjHost'] ?>.fishblab.com/<?php if($loc['state']){ echo $loc['state'] .'/'; } ?><?php if($loc['city']){ echo urlencode($loc['city']) .'/'; } ?><?php echo $rec[$act['keyName']] ?>"><?php echo $rec['username'] ?></a></td>
      <td><a href="http://<?php echo $rec['jrjHost'] ?>.fishblab.com/<?php if($loc['state']){ echo $loc['state'] .'/'; } ?><?php if($loc['city']){ echo urlencode($loc['city']) .'/'; } ?><?php echo $rec[$act['keyName']] ?>"><?php echo $rec['caption'] ?></a></td>
    </tr>
    <?php endfor ?> 
  </table>
  <div class="clear"></div>
    <?php if($act['showNew']) : ?>
    <div class="gwIntroNew">
      <button class="fbButton" onclick="actMapNew('<?php echo $act['key'] ?>');return false;">Create New <?php echo $act['name'] ?></button>
    </div>
    <?php endif ?>
</div>
