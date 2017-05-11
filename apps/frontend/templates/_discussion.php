<div>
  <div id="discHead" class="listHead fbBorder5G">
    <div id="discHeadText" class="listHeadText floatLeft"></div>
    <div class="listHeadBut floatRight">
      <button class="fbButton fbButBig" onclick="discNew();">New Discussion</button>
    </div>
    <div class="clear"></div>
  </div>

  <div id="discList" class="dataList">
   <?php for($i=0; $i<$data['count']; $i++): ?>
    <?php $disc = $data['records'][$i]; $did = $disc['id']; $str = $disc['content']; ?>
    <?php $is_owner = ($disc['username'] == $user['username']); ?>
    <?php if($disc['photo_id']){ $src = fbLib::wwwFilePath($disc['photo_id']) . '32x32.jpg'; }else{ $src = '/images/fb/nophoto.png';} ?>
    <div class="recRow fbBorder2B" id="discRow_<?php echo $did ?>" onmouseover="$(this).addClass('recRowActive');" onmouseout="$(this).removeClass('recRowActive');">
      <div class="discCap fbBorder1G">
        <div class="discHeadCell">
          <img class="uImg" src="<?php echo $src ?>" onclick="return userPubShow('<?php echo $disc['username'] ?>');" />
        </div>    
        <div class="discHeadCell">
          <div class="discCapTxt" onclick="discPopShow(<?php echo $did ?>);">
            <?php echo $disc['caption'] ?>
          </div>
          <div class="discStatBox">
            <div class="discStatLink"><?php echo $disc['date_create'] ?></div>
            <a href="/" onclick="return userPubShow('<?php echo $disc['username'] ?>');" class="discStatLink" rel="nofollow"><?php echo $disc['username'] ?></a>
           <?php if($is_owner): ?>

           <?php else: ?>
            <a href="/" onclick="discFlag(<?php echo $did ?>);return false;" class="discStatLink">Flag</a>
           <?php endif ?>
            <a href="/" onclick="openDiscWin(<?php echo $did ?>);return false;" class="discStatLink">Map</a>
            <a class="discStatLink" href="/" onclick="discPopShow(<?php echo $did ?>);return false;">Detail</a>
            <div class="clear"></div>
          </div>
        </div>
        <div class="floatRight">
          <a href="/" class="fbButton" onclick="discReplyShow(<?php echo $did ?>);return false;">Comment</a>
         <?php if($is_owner): ?>
          <a href="/" class="fbButton" onclick="discEdit(<?php echo $did ?>);return false;">Edit</a>
         <?php endif ?>
        </div>
        <div class="clear"></div>
      </div>
      <div class="recMsg" onclick="discPopShow(<?php echo $did ?>);">
       <?php if( strlen($str) > 500 ) : ?>
        <span class='bigQuote'>"</span><?php echo substr($str,0,499) ?><span class='bigQuote'>"</span>
        ... <span>more</span>
       <?php else: ?>
        <span class='bigQuote'>"</span><?php echo $str ?><span class='bigQuote'>"</span>
       <?php endif ?>
      </div>
      <div class="clear"></div>
     <?php if($disc['photo_count'] > 0) : ?> 
      <div class="recImgMore">
       <?php for($j=0; $j<$disc['photo_count']; $j++): ?>
        <?php $img = $disc['photos'][$j]; ?>
          <img class="recImg fbBorder1G" src="<?php echo fbLib::wwwFilePath($img['id']) ?>100x100.jpg" onclick="discPhotoView(<?php echo $did ?>,<?php echo $img['id']?>);return false;" />
       <?php endfor ?> 
        <div class="clear"></div>
      </div>
     <?php endif ?>
      <?php include_partial('global/replies', array('user' => $user, 'key' => 'disc', 'replies' => $disc['replies'], 'pid' => $did, 'count_total' => $disc['reply_count'] )) ?>
    </div>
    <?php endfor ?> 
  </div>

</div>
