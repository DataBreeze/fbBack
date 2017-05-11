Hello <?php echo $to_username ?>,

FishBlab User '<?php echo $from_username ?>' has requested to be Friends with you on FishBlab.com.

If you choose to allow '<?php echo $from_username ?>' to be your FishBlab.com Friend, they will be able to view all of your photos and posts configured as 'Friend Only'.

To view and respond to this request use this link:
http://m.fishblab.com/#mobForm?s=userReq&k=<?php echo urlencode($from_username) ?>

<?php if($note) : ?>
<?php echo $from_username ?> sent this note:
<?php echo "\n" . $note . "\n" ?>
<?php endif ?>

Thanks for using FishBlab.com!

The FishBlab Team
---------------------------------------------------------

This message was sent to: <?php echo $email ?>

Click this link to manage you FishBlab.com email notifications
http://m.fishblab.com/#mobForm?s=userEditEmail

Click this link to IMMEDIATELY UNSUBSCRIBE from all FishBlab.com email messages
http://m.fishblab.com/#mobForm?s=userMailStop&email=<?php echo urlencode($email) ?>
