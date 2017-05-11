<div>
  <div id="photoHead" class="listHead fbBorder5G">
    <div id="photoHeadText" class="listHeadText floatLeft"></div>
    <div class="listHeadBut floatRight">
      <a class="fbButton fbButBig" href="#" onclick="photoUpload();return false;">New Photo</a>
    </div>
    <div class="clear"></div>
  </div>

  <div id="photoList" class="dataList">
   <?php for($i=0; $i<$data['count']; $i++): ?>
    <?php $img = $data['records'][$i] ?>
    <?php $pid = $img['id']; ?>
    <div id="photo_<?php echo $img['id'] ?>" class="photoListImg fbBorder2G">
      <img src="<?php echo fbLib::wwwFilePath($img['id']) ?>100x100.jpg" onclick="photoView(<?php echo $img['id']?>);return false;" />
      <div class="photoLinkBox">
        <a class="marRight5" href="/" onclick="userPubShow('<?php echo $img['username']?>');return false;"><?php echo $img['username']?></a> 
        <a class="marLeft5" href="/" onclick="photoMapWin(<?php echo $img['id']?>);return false;">Map</a> 
      </div>
      <div class="photoLinkBox">
        <a id="photoReplyCount_<?php echo $pid ?>" href="/" onclick="photoView(<?php echo $img['id']?>);return false;">Comments (<?php echo $img['reply_count'] ?>)</a> 
      </div>
      <div class="clear"></div>
    </div>
   <?php endfor ?> 
  </div>

  <div class="clear"></div>

</div>
