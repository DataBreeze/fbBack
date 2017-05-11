<?php include_partial('global/header',array('geo' => $geo, 'user' => $user, 'cfg' => $cfg )) ?>
<?php include_partial('global/toolbar',array('geo' => $param['geo']) ) ?>
<?php include_partial('actPath',array('act' => $act,'rec' => $rec, 'loc' => $loc) ) ?>

<div class="centerText fbBorder5 mar10 pad10">

<?php if($mustLogOut) : ?>
<h1 class="pad10 mar10 red"><?php echo $status ?></h1>
<p class="mar10 pad10">
  You are currently logged in to FishBlab.com. If you wish to activate this account you must first logout and reload this page.
</p>

<?php elseif( (! $code) or (! $sent) ) : ?>
<h1 class="pad10 mar10 red"><?php echo $status ?></h1>
<p class="mar10 pad10">
  If you wish to activate this account you must click on the link provided in your email or provide the code below.
</p>
<form name="activateuser" method="get" action="http://user.fishblab.com/<?php echo $user_new['username'] ?>">
  <input type="text" name="code" value="" />
  <input type="submit" name="Activate" value="Activate" />
</form>

<?php else : ?>
<h1 class="pad10 mar10">Activation complete! Please set Your Password</h1>
<p class="mar10 pad10">
  Your Account was activated! Please set your password on your new account.
</p>

<p>
<button class="fbButton" onclick="ownerEditPass();return false;">Change Password</button>
</p>
<p>
<button class="fbButton" onclick="ownerEdit();return false;">Edit Account</button>
</p>
<p>
<button class="fbButton" onclick="userLoadProfile();return false;">Load My Profile</button>
</p>

<?php endif ?>
</div>

<br class="clear" />

<div class="marBot20">
  <?php include_partial('footer',$footer) ?>
</div>
