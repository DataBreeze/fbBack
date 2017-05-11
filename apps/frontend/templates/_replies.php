<?php $count = count($replies); ?>
<div id="<?php echo $key ?>Replies_<?php echo $pid ?>" class="recCmtBox fbBorder1G">
  <div>
    <div class="recCmtHead">
      <div class="floatLeft">
        <span class="fsz12 bold">Comments(<?php echo $count_total ?>):</span>
        <?php if($count < $count_total): ?>
        <a href="/" class="replyLink" rel="nofollow" onclick="<?php echo $key ?>PopShow(<?php echo $pid ?>);return false;">View all</a>
        <?php endif ?>
      </div>
      <div class="floatRight"><a href='/' rel="nofollow" onclick="<?php echo $key ?>ReplyShow(<?php echo $pid ?>);return false;">Add Comment</a></div>
      <div class="clear"></div>
    </div>
    <div>
      <?php for($j=0; $j<$count; $j++): ?>
      <?php $reply = $replies[$j]; ?>
      <?php if($reply['photo_id']){ $src = fbLib::wwwFilePath($reply['photo_id']) . '16x16.jpg'; }else{ $src = '/images/fb/nophoto.png';} ?>
      <div id="<?php echo $key ?>ReplyRow_<?php echo $reply['id'] ?>" class="replyBox">
        <div class="replyCap2 fbBorder1G">
          <div class="discHeadCell">
            <img class="uImg" src="<?php echo $src ?>" onclick="return userPubShow('<?php echo $reply['username'] ?>');" />
          </div>    
          <div class="discHeadCell">
            <div class="discStatBox">
              <div class="discStatLink"><?php echo $reply['date_create'] ?></div>
              <a href="/"rel="nofollow" onclick="return userPubShow('<?php echo $reply['username'] ?>');" class="discStatLink"><?php echo $reply['username'] ?></a>
              <?php if($reply['username'] == $user['username']): ?>
              <a href="/" onclick="<?php echo $key ?>ReplyEditShow(<?php echo $pid ?>,<?php echo $reply['id'] ?>);return false;" class="discStatLink">Edit</a>
              <?php else: ?>
              <a href="/" onclick="<?php echo $key ?>Flag(<?php echo $rid ?>);return false;" class="discStatLink">Flag</a>
              <?php endif ?>
              <div class="clear"></div>
            </div>
          </div>
          <div class="clear"></div>
        </div>
        <div id="<?php echo $key ?>ReplyRowTxt_<?php echo $reply['id'] ?>" class="replyMsg">
          <?php $str2 = $reply['content'] ?>
          <?php if( strlen($str2) > 400 ) : ?>
          <?php echo substr($str2,0,399) ?> ... <a href="/" rel="nofollow" onclick="moreReply(<?php echo $rid .','. $reply['id'] ?>)">more</a>
          <?php else: ?>
          <?php echo $str2 ?>
          <?php endif ?>
        </div>
      </div>
      <?php endfor ?> 
    </div>
  </div>
</div>
