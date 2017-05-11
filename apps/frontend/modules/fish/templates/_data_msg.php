<div id="msgDataBox" class="dataMsg2">
  <div class="floatLeft">
    <a class="fbButton" href="http://fish.fishblab.com">Show All Fish</a>
  </div>
  <div class="fishText floatLeft">
    <div id="msgDataText" class="dataMsgText"><?php echo $msg['text'] ?></div>
  </div>
  <div class="floatLeft">
    <form action="http://fish.fishblab.com/" name="fishForm" method="post" onsubmit="fishFindSubmit();return false;">
      <a class="fbButton" href="/" onclick="fishFindSubmit();return false;">Find Fish</a>
      <input class="fFInp" id="fishName" name="fishName" type="text" />
    </form>
  </div>
  <div class="clear"></div>
  </div>
</div>
