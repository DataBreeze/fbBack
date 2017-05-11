<div id="fbFooterLinks">
  <a class="gHeadBut" href="http://www.fishblab.com">Home</a>
  <a class="gHeadBut" href="/" onclick="ownerFeed();return false;" rel="nofollow">Feedback</a>
  <a class="gHeadBut" href="http://www.fishblab.com" onclick="pageShow('fishblab');return false;" rel="nofollow">About</a>
  <a class="gHeadBut" href="http://www.fishblab.com" onclick="pageShow('contact');return false;" rel="nofollow">Contact</a>
  <a class="gHeadBut" href="http://www.fishblab.com" onclick="pageShow('terms');return false;" rel="nofollow">Terms</a>
  <a class="gHeadBut" href="http://www.fishblab.com" onclick="pageShow('privacy');return false;" rel="nofollow">Privacy</a>

  <?php if (False) : ?>
  <a class="gHeadBut" id="footLinkProfile" href="http://user.fishblab.com/<?php echo ($user['id'] ? urlencode($user['username']) : '') ?>" rel="nofollow">My Profile</a>
  <a class="gHeadBut" id="footLinkNew" href="http://www.fishblab.com" onclick="showCreate();return false;" rel="nofollow">New Account</a>  
  <a class="gHeadBut" id="footLinkLogin" href="http://www.fishblab.com" onclick="showLogin();return false;" rel="nofollow">Login</a>
  <?php endif ?>
</div>
