<div>
  <div id="repHead" class="listHead fbBorder5G">
    <div id="repHeadText" class="listHeadText floatLeft"></div>
    <div class="listHeadBut floatRight">
      <button class="fbButton fbButBig" onclick="repNew();return false;">New Catch</button>
    </div>
    <div class="clear"></div>
  </div>

  <div id="repList" class="dataList">
    <?php for($i=0; $i<$data['count']; $i++): ?>
    <?php $rep = $data['records'][$i]; $rid = $rep['id']; $str = $rep['content']; ?>
    <?php $is_owner = ($rep['username'] == $user['username']); ?>
    <?php if($rep['photo_id']){ $src = fbLib::wwwFilePath($rep['photo_id']) . '32x32.jpg'; }else{ $src = '/images/fb/nophoto.png';} ?>

    <div class="recRow fbBorder2B" id="repRow_<?php echo $rid ?>" onmouseover="$(this).addClass('recRowActive');" onmouseout="$(this).removeClass('recRowActive');">

      <div class="discCap fbBorder1G">
        <div class="discHeadCell">
          <img class="uImg" src="<?php echo $src ?>" height="32" width="32" onclick="userPubShow('<?php echo $rep['username'] ?>');">
        </div>
        <div class="discHeadCell">
          <div class="discCapTxt" onclick="openRepWin(<?php echo $rid ?>);return false;"><?php echo $rep['caption'] ?></div>
          <div class="discStatBox">
            <div class="discStatLink"><?php echo $rep['date_catch'] ?></div>
            <a class="discStatLink" href="/" onclick="userPubShow('<?php echo $rep['username'] ?>');return false;"><?php echo $rep['username'] ?></a>
            <a class="discStatLink" href="/" onclick="openRepWin(<?php echo $rid ?>);return false;">Map</a>
            <a class="discStatLink" href="/" onclick="reportPopShow(<?php echo $rid ?>);return false;">Detail</a>
           <?php if($rep['username'] != $user['username']): ?>
             <a class="discStatLink" href="/" onclick="return repFlag(<?php echo $rid ?>);">Flag</a>
           <?php endif ?>
            <div class="clear"></div>
          </div>
        </div>
        <div class="floatRight">
         <?php if($rep['username'] == $user['username']): ?>
          <a href="#" onclick="repUpload(<?php echo $rid ?>);return false;" class="fbButton">Photo</a>
          <a href="#" onclick="repEdit(<?php echo $rid ?>);return false;" class="fbButton">Edit</a>
         <?php else: ?>
          <a href="#" onclick="reportReplyShow(<?php echo $rid ?>);return false;" class="fbButton">Comment</a>
         <?php endif ?>
        </div>
        <div class="clear"></div>
      </div>

      <div class="rec fbBorder1G"> 

        <div class="reRow">
          <div class="recCap floatLeft" onclick="openRepWin(<?php echo $rid ?>);">
            <?php echo $rep['fish_name'] ?>
          </div>
          <div class="clear"></div>
        </div>

        <div class="recRowUL" onclick="openRepWin(<?php echo $rid ?>);">
          <div class="reLab">Length:</div><div class="reVal"><?php echo $rep['length'] ?> in</div>
          <div class="reLab">Weight:</div><div class="reVal"><?php echo $rep['weight'] ?> lbs</div>
          <div class="clear"></div>
        </div>

        <div class="reRow" onclick="openRepWin(<?php echo $rid ?>);">
          <div class="recMsg">
            <span class='bigQuote'>"</span>
            <?php if( strlen($str) > 500 ) : ?><?php echo substr($str,0,199) ?> ... <a href="/" onclick="moreReport(<?php echo $rid ?>)">more</a><?php else: ?><?php echo $str ?><?php endif ?>
            <span class='bigQuote'>"</span>
          </div>
          <div class="clear"></div>
        </div>

        <div class="clear"></div>

       <?php if($rep['photo_count'] > 0) : ?> 
        <div id="rptImgMore_<?php echo $rid ?>" class="recImgMore">
         <?php for($j=0; $j<$rep['photo_count']; $j++): ?>
	  <?php $img = $rep['photos'][$j]; ?>
          <img class="recImg fbBorder1G" src="<?php echo fbLib::wwwFilePath($img['id']) ?>100x100.jpg" onclick="repViewPhoto(<?php echo $rid ?>,<?php echo $img['id']?>);return false;" />
          <?php endfor ?> 
        </div>
       <?php endif ?>

      </div>

      <?php include_partial('global/replies', array('user' => $user, 'key' => 'report', 'replies' => $rep['replies'], 'pid' => $rid, 'count_total' => $rep['reply_count'] )) ?>

    </div>
    <?php endfor ?> 
  </div>
</div>
