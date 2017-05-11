<div id="topH1" class="topH">
  <div id="topBox">
    <div class="topCell floatLeft marRight10">
      <div class="menuDiv">
        <a id="fbMenuBut" tabindex="0" href="#fbMenu">Menu</a>
      </div>
    </div>
    <div class="topCell floatLeft">
      <div id="fbMenu" class="floatLeft">
        <a href="http://www.fishblab.com">Home</a>
        <a href="http://photo.fishblab.com">Photo</a>
        <a href="http://photo.fishblab.com/?tab=catch">Catch</a>
        <a href="http://photo.fishblab.com/?tab=report">Report</a>
        <a href="http://discuss.fishblab.com">Discuss</a>
        <a href="http://area.fishblab.com">Spots</a>
        <a href="http://fish.fishblab.com">Fish</a>
      </div>
      <div class="floatLeft">
        &nbsp;
      </div>
      <div class="clear"></div>
    </div>
    <div class="topCell floatRight padTop3 marRight10">
     <?php if($user['id'] > 0): ?>
      <div id="welGrp" class="fbBorder2 topMenuText linkWhite floatRight bg3090C7">
        Hi
        <span id="welUser"></span>
        <a id="welLink" onclick="userEditInit();return false;" href="/"><?php echo $user['username'] ?></a>
      </div>
     <?php else: ?>
      <div id="welGrp" class="fbBorder2 topMenuText linkWhite floatRight bgFFFFCC">
        Hi
        <span id="welUser">Guest</span>
        <a id="welLink" onclick="showLogin();return false;" href="/">Login</a>
      </div>
     <?php endif ?>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>
  <div id="topBox2">
    <div class="floatLeft" id="faceLink">
      <fb:like href="http://www.fishblab.com" layout="button_count" show_faces="false" width="90" font=""></fb:like>
    </div>
    <div class="floatLeft topHeadText">
      <?php echo $cfg['head_text'] ?>
    </div>
    <div class="floatRight marRight10">
      <div class="fbBorder2 topMenuText linkWhite">
        <a id="feedBut" href="/" onclick="feedShow();return false;">Feedback</a>
      </div>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>
</div>
