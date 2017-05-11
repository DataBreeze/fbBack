Hello <?php echo $username ?>,

User <?php echo $reply['username'] ?> has replied to the $type:
"<?php echo $parent['caption'] ?>" Date:<?php echo $parent['date_create'] ?>


<?php echo $reply['username'] ?>'s Comment:
"<?php echo $reply['content'] ?>"


Click here to view the discussion:
http://discuss.fishblab.com/<?php echo $parent['year'] ?>/<?php echo $parent['month'] ?>/<?php echo $parent['day'] ?>/<?php echo $parent['id'] ?>


Thanks for using FishBlab.com!

The FishBlab Team
---------------------------------------------------------

This message was sent to: <?php echo $email ?>

Click this link to manage you FishBlab.com email notifications
http://www.fishblab.com/user/msgManage

Click this link to IMMEDIATELY UNSUBSCRIBE from all FishBlab.com email messages
http://www.fishblab.com/user/msgStop?email=<?php echo urlencode($email) ?>
