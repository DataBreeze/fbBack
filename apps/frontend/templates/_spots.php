<div>
  <div id="spotHead" class="listHead fbBorder5G">
    <div id="spotHeadText" class="listHeadText floatLeft"></div>
    <div class="listHeadBut floatRight">
      <a class="fbButton  fbButBig" href="/" onclick="spotNew();return false;">New Spot</a>
    </div>
    <div class="clear"></div>
  </div>

  <div id="spotList" class="dataList">
    <?php for($i=0; $i<$data['count']; $i++): ?>
    <?php $sp = $data['records'][$i] ?>
    <?php $sid = $sp['id']; $str = $sp['content']; ?>
    <?php if($sp['photo_id']){ $src = fbLib::wwwFilePath($sp['photo_id']) . '32x32.jpg'; }else{ $src = '/images/fb/nophoto.png';} ?>

    <div class="recRow fbBorder2B" id="spotRow_<?php echo $sid ?>" onmouseover="$(this).addClass('recRowActive');" onmouseout="$(this).removeClass('recRowActive');">

      <div class="discCap fbBorder1G">
        <div class="discHeadCell">
          <img class="uImg" src="<?php echo $src ?>" height="32" width="32" onclick="userPubShow('<?php echo $sp['username'] ?>');">
        </div>
        <div class="discHeadCell">
          <div class="discCapTxt" onclick="spotPopShow(<?php echo $sid ?>);return false;"><?php echo $sp['caption'] ?></div>
          <div class="discStatBox">
            <div class="discStatLink"><?php echo $sp['date_create'] ?></div>
            <a class="discStatLink" href="/" onclick="userPubShow('<?php echo $sp['username'] ?>');return false;"><?php echo $sp['username'] ?></a>
            <a class="discStatLink" href="/" onclick="openSpotWin(<?php echo $sid ?>);return false;">Map</a>
            <a class="discStatLink" href="/" onclick="spotPopShow(<?php echo $sid ?>);return false;">Detail</a>
           <?php if($sp['username'] != $user['username']): ?>
             <a class="discStatLink" href="/" onclick="return spotFlag(<?php echo $sid ?>);">Flag</a>
           <?php endif ?>
            <div class="clear"></div>
          </div>
        </div>

        <div class="floatRight">
         <?php if($sp['username'] == $user['username']): ?>
          <a href="#" onclick="return spotUpload(<?php echo $sid ?>);" class="fbButton">Photo</a>
          <a href="#" onclick="return spotEdit(<?php echo $sid ?>);" class="fbButton">Edit</a>
         <?php else: ?>
          <a href="#" onclick="spotReplyShow(<?php echo $sid ?>);return false;" class="fbButton">Comment</a>
         <?php endif ?>
        </div>
        <div class="clear"></div>
      </div>

      <div class="rec fbBorder1G"> 

        <div class="reRow" onclick="spotPopShow(<?php echo $sid ?>);">
          <div class="recMsg">
            <span class='bigQuote'>"</span>
            <?php if( strlen($str) > 500 ) : ?><?php echo substr($str,0,499) ?> ... <a href="/" onclick="return false;">more</a><?php else: ?><?php echo $str ?><?php endif ?>
            <span class='bigQuote'>"</span>
          </div>
        </div>

      <?php if($sp['url']): ?>
        <div class="spotUrl">
	   Website:
	   <a href="<?php echo fbLib::urlFix($sp['url']) ?>" target="NEW"><?php echo ($sp['url_caption'] ? $sp['url_caption'] : $sp['url']) ?></a>
        </div>
       <?php endif ?>
  
       <?php if($sp['photo_count'] > 0) : ?> 
        <div id="spotImgMore_<?php echo $sid ?>" class="recImgMore">
         <?php for($j=0; $j<$sp['photo_count']; $j++): ?>
	  <?php $img = $sp['photos'][$j]; ?>
          <img class="recImg fbBorder1G" src="<?php echo fbLib::wwwFilePath($img['id']) ?>100x75.jpg" onclick="spotPhotoView(<?php echo $sid ?>,<?php echo $img['id']?>);return false;" />
         <?php endfor ?> 
        </div>
       <?php endif ?>

      </div>

      <?php include_partial('global/replies', array('user' => $user, 'key' => 'spot', 'replies' => $sp['replies'], 'pid' => $sid, 'count_total' => $sp['reply_count'] )) ?>

    </div>
    <?php endfor ?> 
  </div>

</div>
