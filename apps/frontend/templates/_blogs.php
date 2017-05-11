<div>
  <div id="blogHead" class="listHead fbBorder5G">
    <div id="blogHeadText" class="listHeadText floatLeft"></div>
    <div class="listHeadBut floatRight">
      <a class="fbButton  fbButBig" href="/" onclick="blogNew();return false;">New Report</a>
    </div>
    <div class="clear"></div>
  </div>

  <div id="blogList" class="dataList">
    <?php for($i=0; $i<$data['count']; $i++): ?>
    <?php $bg = $data['records'][$i] ?>
    <?php $bid = $bg['id']; $str = $bg['content']; ?>
    <?php if($bg['photo_id']){ $src = fbLib::wwwFilePath($bg['photo_id']) . '32x32.jpg'; }else{ $src = '/images/fb/nophoto.png';} ?>

    <div class="recRow fbBorder2B" id="blogRow_<?php echo $bid ?>" onmouseover="$(this).addClass('recRowActive');" onmouseout="$(this).removeClass('recRowActive');">

      <div class="discCap fbBorder1G">
        <div class="discHeadCell">
          <img class="uImg" src="<?php echo $src ?>" height="32" width="32" onclick="userPubShow('<?php echo $bg['username'] ?>');">
        </div>
        <div class="discHeadCell">
          <div class="discCapTxt" onclick="blogPopShow(<?php echo $bid ?>);return false;"><?php echo $bg['caption'] ?></div>
          <div class="discStatBox">
            <div class="discStatLink"><?php echo $bg['date_blog'] ?></div>
            <a class="discStatLink" href="/" onclick="userPubShow('<?php echo $bg['username'] ?>');return false;"><?php echo $bg['username'] ?></a>
            <a class="discStatLink" href="/" onclick="openBlogWin(<?php echo $bid ?>);return false;">Map</a>
           <?php if($bg['username'] != $user['username']): ?>
             <a class="discStatLink" href="/" onclick="return blogFlag(<?php echo $bid ?>);">Flag</a>
           <?php endif ?>
            <div class="clear"></div>
          </div>
        </div>
        <div class="floatRight">
         <?php if($bg['username'] == $user['username']): ?>
          <a href="/" rel="nofollow" onclick="return blogUpload(<?php echo $bid ?>);" class="fbButton">Add Photo</a>
          <a href="/" rel="nofollow" onclick="return blogEdit(<?php echo $bid ?>);" class="fbButton">Edit</a>
         <?php else: ?>
          <a href="/" rel="nofollow" onclick="blogReplyShow(<?php echo $bid ?>);return false;" class="fbButton">Comment</a>
         <?php endif ?>
        </div>
        <div class="clear"></div>
      </div>

      <div class="recRow"> 

        <div class="reRow" onclick="blogPopShow(<?php echo $bid ?>);">
          <div class="recMsg">
            <span class='bigQuote'>"</span>
            <?php if( strlen($str) > 500 ) : ?><?php echo substr($str,0,499) ?> ... <a href="/" onclick="return false;">more</a><?php else: ?><?php echo $str ?><?php endif ?>
            <span class='bigQuote'>"</span>
          </div>
        </div>

        <div class="clear"></div>

       <?php if($bg['url']): ?>
        <div class="spotUrl">
	   Link:
	   <a href="<?php echo fbLib::urlFix($bg['url']) ?>" target="NEW"><?php echo ($bg['url_caption'] ? $bg['url_caption'] : $bg['url']) ?></a>
        </div>
       <?php endif ?>

       <?php if($bg['photo_count'] > 0) : ?> 
        <div id="blogImgMore_<?php echo $bid ?>" class="recImgMore">
        <?php for($j=0; $j<$bg['photo_count']; $j++): ?>
	 <?php $img = $bg['photos'][$j]; ?>
          <img class="recImg fbBorder1G" src="<?php echo fbLib::wwwFilePath($img['id']) ?>100x75.jpg" onclick="blogViewPhoto(<?php echo $bid ?>,<?php echo $img['id']?>);return false;" />
         <?php endfor ?> 
        </div>
       <?php endif ?>

      </div>

      <?php include_partial('global/replies', array('user' => $user, 'key' => 'blog', 'replies' => $bg['replies'], 'pid' => $bid, 'count_total' => $bg['reply_count'] )) ?>

    </div>
    <?php endfor ?> 
  </div>

</div>
