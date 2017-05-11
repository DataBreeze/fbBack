<div id="msgDataBox" class="dataMsg">
<div class="fishText">
  <div id="msgDataText" class="dataMsgText"><?php echo $msg['text'] ?></div>
</div>
<div class="floatLeft">
  <form action="http://fish.fishblab.com/" name="fishForm" method="post" onsubmit="fishFindSubmit();return false;">
    <a class="fbButton" href="/" onclick="fishFindSubmit();return false;">Find Fish</a>
    <input class="fFInp" id="fishName" name="fishName" type="text" />
  </form>
</div>
</div>
