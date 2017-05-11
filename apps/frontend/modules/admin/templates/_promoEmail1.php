<html>
  <head>
    <style>
      html {
      height: 100%;
      }
      body {
      height: 100%;
      font-size:12px;
      margin:10px;
      padding:5px;
      font-family:verdana,arial,Helvetica,sans-serif;
      }
      .mailImg{
      float:right;
      }
      h1{
      font-weight:bold;
      font-size:16px;
      padding:5px;
      margin:5px;
      color:darkblue;
      }
      h2{
      font-weight:bold;
      font-size:14px;
      padding:5px;
      margin:5px;
      color:darkblue;
      }
      h3{
      font-weight:bold;
      font-size:14px;
      padding:5px 5px 2px 5px;
      margin:5px 5px 0 5px;
      color:darkblue;
      }
      p{
      font-weight:normal;
      font-size:14px;
      padding:2px 5px 2px 5px;
      margin:3px 5px 10px 5px;
      color:darkblue;
      }
      .unsubscribe{
      font-weight:normal;
      font-size:14px;
      padding:5px;
      margin:10px 5px 10px 5px;
      color:black;
      }
      .fbUrl{
      font-weight:bold;
      color:blue;
}
      .floatLeft{
      float:left;
      }
      .head{
      width:100%;
      border:0px solid gray;
      margin:0 0 10px 0;
      }
      .colLeft{
      float:left;
      }
      .colRight{
      float:right;
      border:0px solid gray;
      }
      .clear{
      width: 100%;
      height: 1px;
      margin: 0 0 -1px;
      clear: both
      }
      .fbBorder5{
      border:5px solid #728FCE;
      -moz-border-radius:8px;
      -webkit-border-radius:8px;
      -opera-border-radius:8px;
      -khtml-border-radius:8px;
      border-radius:8px;
      padding:5px;
      margin:5px;
      }
      .fbBorder1{
      border:1px solid #728FCE;
      -moz-border-radius:8px;
      -webkit-border-radius:8px;
      -opera-border-radius:8px;
      -khtml-border-radius:8px;
      border-radius:8px;
      padding:5px;
      margin:5px;
      }
      .bgGray{
      background-color:#EFEFEF;
      }
    </style>
  </head>
  <body>
    <div class="fbBorder5">

    <div class="head">
      
      <div class="colRight">
	<img class="mailImg" src="http://admin.fishblab.com/images/fb/fishLogo54x50.png" />
	<h1>Hello from <a href="http://www.fishblab.com">www.fishblab.com</a></h1>
      </div>
      
      <div class="colLeft">
	<p>
	<?php if($p['promo_name']) : ?>
	Dear <?php echo $p['promo_name'] ?>,
	<?php else : ?>
	Dear Fishing Professional,
	<?php endif ?>
	</p>

	<h1>Would you like free advertising and more customers?</h1>
      </div>

      <div class="clear"></div>

    </div>

    <p>
      <a class="fbUrl" href="http://www.fishblab.com">www.fishblab.com</a> is a <b>high traffic</b> Fishing website that contains User generated Fishing Reports, Photos, Spots, Discussion and more. Thousands of Users visit FishBlab.com every day.
    </p>

    <h3>We need you</h3>
    <p>
      We need you to post your Fishing experiences to help us build content. The more Fishing content we have, the more internet traffic we will gain. We want FishBlab to become the best place to find current fishing information. Please help us grow by providing your Fishing Photos and Reports.
    </p>

    <h3>You need Us</h3>
    <p>
      You will gain free exposure each time you share your content. In many areas of the United States FishBlab.com is on the first page of search results, or will be soon. <b>This is your opportunity to drive customers to your website or have them contact you directly.</b> If you join and post during this promotion you will receive extra points that will automatically boost your future posts popularity.
    </p>

    <div class="fbBorder1 mar10 pad10 bgGray">
      <p>
      Use this link to 
      <a href="http://user.fishblab.com/<?php echo urlencode($p['promo_username']) ?>?code=<?php echo $p['code'] ?>">Activate Your Free Account Now!</a>
      </p>
      OR
    <p>
      1: Go to your profile page: <b><a href="http://user.fishblab.com/<?php echo urlencode($p['promo_username']) ?>">user.fishblab.com/<?php echo urlencode($p['promo_username']) ?></a></b>
      <br />
      2: Enter your activation code: <b><?php echo $p['code'] ?></b>
    </div>

    <h3>Be a Leader in Your area</h3>
    <p>
      FishBlab.com scores each User by various metrics including join date (seniority), quality and number of posts and ratings by other Users. By signing up and contributing quality content to your regional area your posts will rate higher and be more visible to your potential customers.
    </p>

    <h3>We have already created your account!</h3>
    <p>
      As part of this promotion, your <a href="http://user.fishblab.com/<?php echo urlencode($p['promo_username']) ?>?code=<?php echo $p['code'] ?>">user.FishBlab.com/<?php echo urlencode($p['promo_username']) ?></a> Account page has been created already. All you need to is click on the link above to activate your account. Please be sure to set your password!
    </p>

    <h3>We need Your feedback</h3>
    <p>
      Have a question, suggestion or problem with FishBlab.com? Let us know by sending us feedback to support@fishblab.com. We appreciate your feedback as we refine our website.
    </p>
    
    <p>
      As a Fishing Professional, You are the most valuable of our Users. We appreciate your taking the time to read this email. If we have reached you in error or you do not wish to receive marketing emails from FishBlab.com you can unsubscribe using the following link: <a href="http://user.fishblab.com/promoStop/<?php echo urlencode($p['promo_email']) ?>">Unsubscribe</a>
    </p>
    
    <h2>
      <a href="http://user.fishblab.com/<?php echo urlencode($p['promo_username']) ?>?code=<?php echo $p['code'] ?>">Activate Your Free Account Now!</a>
    </h2>

    <p>
      Thanks,
      <br /><br />
      <?php if($p['name_from']) : ?>
      <?php echo $p['name_from'] ?>
      <?php if($p['title_from']) : ?>
      <br /><?php echo $p['title_from'] ?>
      <?php endif ?>
      <?php else : ?>
      The FishBlab.com Team
      <?php endif ?>
    </p>
    
    <div class="unsubscribe">
      If you wish to unsubscribe from FishBlab.com Promotions, please use this link:
      <a href="http://user.fishblab.com/promoStop/<?php echo urlencode($p['promo_email']) ?>">Unsubscribe</a>
    </div>
    </div>
  </body>
</html>
