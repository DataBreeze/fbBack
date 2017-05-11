Hello <?php echo $username ?>,

User <?php echo $reply['username'] ?> has commented on the <?php echo $type ?>:
<?php if($parent['caption']) : ?>
"<?php echo $parent['caption'] ?>" 
<?php endif ?>
Date:<?php echo $date ?>


<?php echo $reply['username'] ?>'s Comment:
"<?php echo $reply['content'] ?>"


Click here to view the comment:
http://<?php echo $mailHost ?>/<?php echo $parent['year'] ?>/<?php echo $parent['month'] ?>/<?php echo $parent['day'] ?>/<?php echo $parent['id'] ?>


Thanks for using FishBlab.com!

The FishBlab Team
---------------------------------------------------------

This message was sent to: <?php echo $email ?>

Click this link to manage you FishBlab.com email notifications
http://www.fishblab.com/user/opt/mailManage

Click this link to IMMEDIATELY UNSUBSCRIBE from all FishBlab.com email messages
http://www.fishblab.com/user/opt/mailStop?email=<?php echo urlencode($email) ?>
