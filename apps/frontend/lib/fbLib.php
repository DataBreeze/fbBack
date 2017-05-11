<?php

class fbLib{

  ## NEVER CHANGE THIS!
  const PASS_SALT = 'SOW415and241PIGS';
  ## THIS CAN BE CHANGED
  const RAND_SALT = 't9l4Ge7Dyc5Sw1';
  ## the sec code used for group security
  const SEC_PUBLIC = 1;
  const SEC_FRIEND = 5;
  const SEC_PRIVATE = 10;
  const SEC_GROUP = 15;
  const SEC_USER = 20;
  const MAX_FILE_SIZE = 10000000;
  const DEFAULT_PROFILE_PHOTO_ID = 332;
  const RECORD_LIMIT = 50;

  public $state_ok = array('AL' => True, 'AK' => True, 'AZ' => True, 'AR' => True, 'CA' => True, 'CO' => True, 'CT' => True, 'DE' => True, 'FL' => True, 'GA' => True, 'HI' => True, 'ID' => True, 'IL' => True, 'IN' => True, 'IA' => True, 'KS' => True, 'KY' => True, 'LA' => True, 'ME' => True, 'MD' => True, 'MA' => True, 'MI' => True, 'MN' => True, 'MS' => True, 'MO' => True, 'MT' => True, 'NE' => True, 'NV' => True, 'NH' => True, 'NJ' => True, 'NC' => True, 'ND' => True, 'OH' => True, 'OK' => True, 'OR' => True, 'PA' => True, 'RI' => True, 'SC' => True, 'SD' => True, 'TN' => True, 'TX' => True, 'UT' => True, 'VT' => True, 'VA' => True, 'WA' => True, 'WV' => True, 'WI' => True, 'WY' => True);

  public $state_convert = array('alabama' => 'AL', 'alaska' => 'AK', 'arizona' => 'AZ', 'arkansas' => 'AR', 'california' => 'CA', 'colorado' => 'CO', 'connecticut' => 'CT', 'delaware' => 'DE', 'district of columbia' => 'DC', 'washington dc' => 'DC', 'florida' => 'FL', 'georgia' => 'GA', 'hawaii' => 'HI', 'idaho' => 'ID', 'illinois' => 'IL', 'indiana' => 'IN', 'iowa' => 'IA', 'kansas' => 'KS', 'kentucky' => 'KY', 'louisiana' => 'LA', 'maine' => 'ME', 'maryland' => 'MD', 'massachusetts' => 'MA', 'michigan' => 'MI', 'minnesota' => 'MN', 'mississippi' => 'MS', 'missouri' => 'MO', 'montana' => 'MT', 'nebraska' => 'NE', 'nevada' => 'NV', 'new hampshire' => 'NH', 'new jersey' => 'NJ', 'new mexico' => 'NM', 'new york' => 'NY', 'north carolina' => 'NC', 'north dakota' => 'ND', 'ohio' => 'OH', 'oklahoma' => 'OK', 'oregon' => 'OR', 'pennsylvania' => 'PA', 'rhode island' => 'RI', 'south carolina' => 'SC', 'south dakota' => 'SD', 'tennessee' => 'TN', 'texas' => 'TX', 'utah' => 'UT', 'vermont' => 'VT', 'virginia' => 'VA', 'washington' => 'WA', 'west virginia' => 'WV', 'wisconsin' => 'WI', 'wyoming' => 'WY');

  public $userCur = False;
  
  function __construct($req){
  }

  ## sort by unix time stamp
  public function sortByUTS($a, $b){
#return $a['uts'] - $b['uts'];
    if ($a["uts"] == $b["uts"]) {
      return 0;
    }
    return ($a["uts"] < $b["uts"]) ? -1 : 1;
  }
 
  public function stateOk($state){
    $state = strtoupper($state);
    $ok = $this->state_ok[$state];
    if($ok){
      return $state;
    }else{
      return False;
    }
  }

  public function stateConvert($state_full){
    $state_full = strtolower($state_full);
    if($state = $this->state_convert[$state_full]){
      return $state;
    }else{
      return False;
    }
  }

  public function isAdmin(){
    if($user = fbLib::getUser()){
      return $user['jrjSec'];
    }
    return False;
  }
  
  public function imageSize(){
    $image_size = array( array('width'=>16,'height'=>16,'quality'=>50),
			 array('width'=>25,'height'=>25,'quality'=>60),
			 array('width'=>32,'height'=>32,'quality'=>60),
			 array('width'=>50,'height'=>50,'quality'=>60),
			 array('width'=>100,'height'=>100,'quality'=>60),
			 array('width'=>200,'height'=>200,'quality'=>70),
			 array('width'=>300,'height'=>300,'quality'=>80),
			 array('width'=>400,'height'=>400,'quality'=>80),
			 array('width'=>600,'height'=>400,'quality'=>80)
			 );
    return $image_size;
  }

  ## given a numeric id, create a path up to four levels
  public function fsIdPath($id){
    $str_id = strval($id);
    $len = strlen($str_id);
    $path = substr($str_id,0,1) . '/';
    if($len > 1){
      $path .= substr($str_id,1,1) . '/';
      if($len > 2){
	$path .= substr($str_id,2,1) . '/';
	if($len > 3){
	  $path .= substr($str_id,3,1) . '/';
	}
      }
    }
    return $path;
  }
  
  ## given a user_id calculate the filesystem (fs) user path
  public function fsUserPath($user_id){
    return '/opt/fb/fbFS/U/' . fbLib::fsIdPath($user_id);
  }
  
  ## given a user_id calculate the www user path
  public function wwwUserPath($user_id){
    $str_id = strval($user_id);
    return '/fbFS/U/' . fbLib::fsIdPath($user_id) . 'u' . $user_id . '/';
  }
  
  ## given a file_id calculate the filesystem (fs) file path
  public function fsFilePath($file_id){
    $str_id = strval($file_id);
    return '/opt/fb/fbFS/F/' .  fbLib::fsIdPath($file_id) . 'f' . $str_id . '/';
  }

  ## given a file_id calculate the www file path
  public function wwwFilePath($file_id){
    $str_id = strval($file_id);
    $path =  '/fbfs/F/' .  fbLib::fsIdPath($file_id) . 'f' . $str_id . '/';
    return $path;
  }

  ## given a file_id calculate the remote TO path for rsync additions
  public function rsyncFilePathTo($file_id){
    $str_id = strval($file_id);
    return '/opt/fb/fbFS/./F/' .  fbLib::fsIdPath($file_id) . 'f' . $str_id . '/';
  }
  
  ## given a file_id calculate the remote TO path for rsync for delete
  public function rsyncFilePathToDelete($file_id){
    $str_id = strval($file_id);
    return '/F/' .  fbLib::fsIdPath($file_id) . 'f' . $str_id . '/';
  }

  ## user is deleting a file. Delete from the servers using rsync with delete
  public function deleteServerImages($file_id){
    $file_path = fbLib::rsyncFilePathToDelete($file_id);
    $servers = sfConfig::get('app_servers_front');
    foreach($servers as $server){
      $status = system('rsync -r --delete /opt/fb/fbFS/empty/ '. $server . '::fbFS' . $file_path);
    }
  }

  public function validateFile($file){
    $ret = array('error' => True,'status' => 'fail');
    if($file['error']){
      $ret['error_desc'] = 'system error';
      $ret['desc'] = 'Error: Max file size is 10MB';
    }elseif($file['size'] > fbLib::MAX_FILE_SIZE){
      $ret['error_desc'] = 'over_size_limit';
      $ret['desc'] = 'Uploaded file is larger than the maximum size allowed.';
      $ret['detail'] = 'Maximum File Size is: ' . fbLib::MAX_FILE_SIZE .', Uploaded File is: ' . $file['size'];
      unlink($file['tmp_name']);
    }else if(! preg_match('/(jpeg|jpg|gif|png)/i',$file['type']) ){
      $ret['error_desc'] = 'invalid_type';
      $ret['desc'] = 'Uploaded file is not an allowed image type (jpg, gif or png))';
      $ret['detail'] = 'Accepted Image types are: jpg (or jpeg), png or gif<br />Please ensure your file is in one of the formats and it has the proper extension.';
      unlink($file['tmp_name']);
    }else{
      $ret['error'] = False;
    }
    return $ret;
  }

  public function createAndPushFiles($file,$file_id){
    ## create file store node (directory of files for this user_id hash indexed to 4 levels)
    $file_path = fbLib::fsFilePath($file_id);
    mkdir($file_path,0777,true);
    $ext = 'jpg';
    if(preg_match('/png/i',$file['type'])){
      $ext = 'png';
    }elseif(preg_match('/pjpeg/i',$file['type'])){
      $ext = 'jpg';
      $file['type'] = 'image/jpg';
    }elseif(preg_match('/gif/i',$file['type'])){
      $ext = 'gif';
    }
    $file['name_orig'] = $file_path . 'orig.' . $ext;
    move_uploaded_file($file['tmp_name'],  $file['name_orig']);
    ## done building file node
    $ret['file_type'] = $file['type'];
    $files = fbLib::createImages($file_path,$file,$ext);
    
    ## rsync the images to the front servers
    $file_path_remote = fbLib::rsyncFilePathTo($file_id);
    $servers = sfConfig::get('app_servers_front');
    foreach($servers as $server){
      $status = system('rsync -raR ' . $file_path_remote .' '. $server . '::fbFS');
    }
    ## delete local files
    fbLib::deleteTempImages($file_path);
    rmdir($file_path);
    $ret['status'] = 'ok';
    $ret['status_desc'] = 'Upload Received';
    $ret['error'] = False;
    $ret['desc'] = 'File Upload Finished!';
    return $ret;
  }

  private function createImages($path,$file,$ext){
    ## create new resized images with GD
    $name_orig = $file['name_orig'];
    $type = $file['type'];
    $sizes = fbLib::imageSize();
    foreach($sizes as $sz){
      $img = new sfImage($name_orig, $type);
      $img->thumbnail($sz['width'],$sz['height']);
      $img->setQuality($sz['quality']);
      $name = $path . $sz['width'] . 'x' . $sz['height'] . '.jpg';
      $img->saveAs($name);
    }
  }
  
  ## after creating a new file and rsyncing to the front servers, delete the copy on this server
  public function deleteTempImages($file_path){
    $files = scandir($file_path);
    foreach($files as $file){
      unlink($file_path . $file);
    }
  }

  public function resetUser($user_id){
    $this->userCur = False;
    sfContext::getInstance()->getUser()->setAttribute('fbUserId', $user_id);
    $user_new = Doctrine_Core::getTable('User')->activate($user_id);
    return $this->restoreUser();
  }

  public function restoreUser(){
    if( ! $this->userCur){
      $this->userCur = array('id' => False,'username' => 'Guest','fbValid' => False);
      $req = sfContext::getInstance()->getRequest();
      $res = sfContext::getInstance()->getResponse();
      $user = sfContext::getInstance()->getUser();
      $user_id = $user->getAttribute('fbUserId');
      if($user_id){
	$userDB = Doctrine_Core::getTable('User')->getUserById($user_id);
	if(! $userDB){
	  $user->setAttribute('fbUserId',NULL);
	  $res->setCookie('fbValid','false',time() + 31536000,'/','.fishblab.com');
	}else{
	  if($req->getCookie('fbValid') != 'true'){
	    $res->setCookie('fbValid','true',time() + 31536000,'/','.fishblab.com');
	  }
	  $userDB['fbValid'] = True;
	  $this->userCur = $userDB;
	}
      }
    }
    return $this->userCur;
  }

  public function getUser(){
    $user = sfContext::getInstance()->getUser();
    $user_id = $user->getAttribute('fbUserId');
    if($user_id){
      $userDB = Doctrine_Core::getTable('User')->getUserById($user_id);
      if($userDB){
	$userDB['fbValid'] = True;
	return $userDB;
      }
    }
    return False;
  }

  public function userSafe($user){
    $safe = array('username' => 'Guest','name' => 'Guest');
    if($user){
      $safe['id'] = $user['id'];
      $safe['username'] = $user['username'];
      $safe['firstname'] = $user['firstname'];
      $safe['lastname'] = $user['lastname'];
      $safe['name'] = $user['username'];
      if($user['firstname'] or $user['lastname']){
	$safe['name'] = $user['firstname'] .' '. $user['lastname'];
      }
      $safe['title'] = $user['title'];
      $safe['website'] = $user['website'];
      $safe['location'] = $user['location'];
      $safe['utype'] = $user['utype'];
      $safe['utype_text'] = $user['utype_text'];
      $safe['about'] = $user['about'];
      $safe['lat'] = $user['lat'];
      $safe['lon'] = $user['lon'];
      $safe['date_create'] = $user['date_create'];
      $safe['msg_disc'] = $user['msg_disc'];
      $safe['msg_reply'] = $user['msg_reply'];
      $safe['msg_update'] = $user['msg_update'];
      $safe['msg_stop'] = $user['msg_stop'];
      $safe['photo_id'] = $user['photo_id'];
      $safe['jrjSec'] = $user['jrjSec'];
    }
    return $safe;
  }

  ########################
  ## user edit validate
  ########################
  private function usernameExistsEdit($p){
    if($p['oldUsername'] != $p['newUsername']){
      ## username has changed - need to check
      $otherUser = Doctrine_Core::getTable('User')->adminUserByUsername($p['newUsername']);
      if($otherUser){
	if($otherUser['id'] != $p['oldId']){
	  ## username already exists
	  return True;
	}
      }
    }
    return False;
  }

  private function emailExistsEdit($p){
    if($p['oldEmail'] != $p['newEmail']){
      ## email has changed - need to check
      $otherUser = Doctrine_Core::getTable('User')->adminUserByEmail($p['newEmail']);
      if($otherUser){
	if($otherUser['id'] != $p['oldId']){
	  ## email already exists
	  return True;
	}
      }
    }
    return False;
  }

  ## check user values before edit
  public function validateEdit($p){
    $ret = array('error' => True,'desc' => 'No id param');
    if($p['id']){
      if($p['username']){
	if($p['email']){      
	  $oldUser = Doctrine_Core::getTable('User')->adminById($p['id']);
	  if($oldUser){
	    $args = array('oldUsername' => $oldUser['username'],'newUsername' =>$p['username'],'oldEmail' => $oldUser['email'],'newEmail' =>$p['email'],'oldId' => $oldUser['id'],'newId' =>$p['id']);
	    if( fbLib::usernameExistsEdit($args) ){
	      $ret['desc'] = 'Username ' . $p['username'] .' exists, please try another';
	      $ret['field'] = 'username';
	    }else if( fbLib::emailExistsEdit($args) ){
	      $ret['desc'] = 'Email ' . $p['email'] .' exists, please try another';
	      $ret['field'] = 'email';
	    }else{
	      $ret['error'] = False;
	    }
	  }else{
	    $ret['desc'] = "Old User not restored";
	  }
	}else{
	  $ret['desc'] = "No Email param";
	}
      }else{
	$ret['desc'] = "No username param";
      }
    }else{
      $ret['desc'] = "No id param";
    }
    return $ret;
  }
  ## end user edit validate
  #########################

  public function getMonthRange(){
    $req = sfContext::getInstance()->getRequest();
    if ( $req->hasParameter('monthMin') and $req->hasParameter('monthMax') ){
      $month_min = $req->getParameter('monthMin');
      $month_max = $req->getParameter('monthMax');
    }else{
      $month_min = $req->getCookie('fbMonthMin');
      if( ($month_min >= 0) and  ($month_min < 11) ) {
	$month_min = intval($month_min) + 1;
      }
      $month_max = $req->getCookie('fbMonthMax');
      if( ($month_max > 0) and  ($month_max < 12) ){
	$month_max = intval($month_max) + 1;
      }
    }
    if(!$month_min){
      $month_min = 1;
    }
    if(!$month_max){
      $month_max = 12;
    }
    $month_min = intval($month_min);
    $month_max = intval($month_max);
    if($month_min >= $month_max){
      $month_min = 1;
      $month_max = 12;      
    }
    return array($month_min,$month_max);
  }
  
  ## given a lat/lon, compute bounds and return geo obj
  public static function boundsFromPoint($lat=26.7488889,$lon=-82.2619444,$dist=20){
    ## given a center point, determine the bounds
    $lon1 = $lon-$dist/abs(cos(deg2rad($lat))*69);
    $lat1 = $lat-($dist/69);
    $lon2 = $lon+$dist/abs(cos(deg2rad($lat))*69);
    $lat2 = $lat+($dist/69);
    $geo = array( 'distance' => $dist,
		  'lat'  => $lat, 'lon' => $lon,
		  'sw_lat' => $lat1, 'sw_lon' => $lon1,
		  'ne_lat' => $lat2, 'ne_lon' => $lon2
		  );
    return $geo;
  }

  public function boundsFromRequest(){
    $req = sfContext::getInstance()->getRequest();
    $strBounds = $req->getParameter('bounds');
    $centerStr = $req->getParameter('center');
    $loc = $req->getParameter('loc');
    $bounds = explode(',',$strBounds);
    $center = explode(',',$centerStr);
    $geo = array( 'lat'  => $center[0], 'lon' => $center[1],
		  'sw_lat' => $bounds[0], 'sw_lon' => $bounds[1],
		  'ne_lat' => $bounds[2], 'ne_lon' => $bounds[3], 'loc' => $loc
		  );
    $geo['has_bounds'] = false;
    if($geo['sw_lat'] and $geo['sw_lon'] and $geo['ne_lat'] and $geo['ne_lon']){
      $geo['has_bounds'] = true;
    }
    return $geo;
  }

  public function boundsFromCookie(){
    $req = sfContext::getInstance()->getRequest();
    $strBounds = $req->getCookie('fbBounds');
    $centerStr = $req->getCookie('fbCenter');
    $loc = $req->getCookie('fbInput');
    $zoom = $req->getCookie('fbZoom');
    $bounds = explode(',',$strBounds);
    $center = explode(',',$centerStr);
    $geo = array( 'lat'  => $center[0], 'lon' => $center[1],
		  'sw_lat' => $bounds[0], 'sw_lon' => $bounds[1],
		  'ne_lat' => $bounds[2], 'ne_lon' => $bounds[3],
		  'loc' => $loc, 'input' => $loc, 'zoom' => intval($zoom), 'has_bounds' => True
		  );
    return $geo;
  }

  public function hasCookieBounds(){
    $req = sfContext::getInstance()->getRequest();
    if($req->getCookie('fbBounds') and $req->getCookie('fbCenter') ){
      return True;
    }
    return False;
  }

  ## 1 = public only, 5 = friends only, 10 = user (me) only, fbLib::SEC_GROUP = group
  public function getSec(){
    $user = sfContext::getInstance()->getUser();
    $user_id = $user->getAttribute('fbUserId');
    if($user_id){
      $sec = intval(sfContext::getInstance()->getRequest()->getCookie('fbSec'));
      if( ($sec == fbLib::SEC_PUBLIC) or ($sec == fbLib::SEC_FRIEND) or ($sec == fbLIB::SEC_PRIVATE) or ($sec == fbLib::SEC_GROUP) or ($sec == 20) ){
	return $sec;
      }
    }
    return 1;
  }
  
  public function getFishId(){
    $fishId = intval(sfContext::getInstance()->getRequest()->getCookie('fbFishId'));
    if($fishId > 0){
      return $fishId;
    }
    return False;
  }
  
  public function getUserId(){
    $user = sfContext::getInstance()->getUser();
    if($user_id = $user->getAttribute('fbUserId')){
      return $user_id;
    }
    return False;
  }
  
  public function activateLogin($user){
    sfContext::getInstance()->getUser()->setAttribute('fbUserId', $user['id']);
    sfContext::getInstance()->getResponse()->setCookie('fbValid','true',time() + 31536000,'/','.fishblab.com');

    $ret = array();
    $ret['error'] = False;
    $ret['desc'] = "User Logged in";
    $ret['valid'] = True;
    $ret['username'] = $user_new['username'];
    $ret['record'] = fbLib::userSafe($user_new);
    return $ret;
  }
	    
  public function getLoc(){
    $request = sfContext::getInstance()->getRequest();
    $geocoder = new fbGeocode();
    $loc = array( 'input_type'  => 'default' );
    if ( $request->hasParameter('lat') and $request->hasParameter('lon') ){
      $loc['input'] = $request->getParameter('locInput');
      $loc['geo']['lat'] = $request->getParameter('lat');
      $loc['geo']['lon'] = $request->getParameter('lon');
      $loc['geo']['input'] = $loc['input'];
    }elseif ( $request->hasParameter('site') ){
      $loc['site'] = $request->getParameter('site');
      $loc['city'] = $request->getParameter('city');
      $loc['state'] = $request->getParameter('state');
      $loc['input'] = $loc['city'] .','. $loc['state'];
      $loc['geo'] = $geocoder->geocodeLoc($loc['input']);
      $loc['geo']['site'] = $loc['site'];
    }elseif ( $request->hasParameter('city') and $request->hasParameter('state') ){
      $loc['city'] = $request->getParameter('city');
      $loc['state'] = $request->getParameter('state');
      $loc['input'] = $loc['city'] .','. $loc['state'];
      $loc['geo'] = $geocoder->geocodeLoc($loc['input']);
      $loc['geo']['zoom'] = 12;
    }elseif ( $request->hasParameter('state') ){
      $loc['state'] = $request->getParameter('state');
      $loc['state'] = preg_replace('/\//','',$loc['state']);
      $loc['input'] = $loc['state'];
      $loc['input_type'] = 'state';
      $loc['geo'] = $geocoder->geocodeLoc($loc['input']);
      $loc['geo']['zoom'] = 6;
    }elseif ( $request->hasParameter('loc') ){
      $loc['input'] = $request->getParameter('loc');
      $loc['geo'] = $geocoder->geocodeLoc($loc['input']);
    }elseif ( $request->hasParameter('country') ){
      $loc['country'] = $request->getParameter('country');
      $loc['input'] = $loc['country'];
      $loc['input_type'] = 'country';
      $loc['geo'] = $geocoder->geocodeLoc($loc['input']);
      $loc['geo']['zoom'] = 5;
    }elseif( $this->hasCookieBounds() ){
      $loc['geo'] = $this->boundsFromCookie();
      $loc['input'] = $loc['geo']['input'];
    }elseif( $request->getCookie('fbLat') and $request->getCookie('fbLon') ){
      $loc['geo']['lat'] = $request->getCookie('fbLat');
      $loc['geo']['lon'] = $request->getCookie('fbLon');
      $loc['geo']['input'] = $request->getCookie('fbInput');
      $loc['input'] = $request->getCookie('fbInput');
    }
    if(!isset($loc['geo']['lat']) || !isset($loc['geo']['lon'])){
      $loc['geo']['lat'] = 27.9658533;
      $loc['geo']['lon'] = -82.8001026;
      $loc['geo']['input'] = 'Clearwater, FL';
    }
    if(!$loc['geo']['sw_lat'] or !$loc['geo']['sw_lon'] or !$loc['geo']['ne_lat'] or !$loc['geo']['ne_lon']){
      $geo = $loc['geo'];
      $geo_new = fbLib::boundsFromPoint($geo['lat'],$geo['lon'],50);
      $geo_new['input'] = $geo['input'];
      $loc['geo'] = $geo_new;
    }
    if(!isset($loc['geo']['zoom'])){
      $loc['geo']['zoom'] = 12;
    }
    $loc['geo']['lat'] = floatval($loc['geo']['lat']);
    $loc['geo']['lon'] = floatval($loc['geo']['lon']);
    return $loc;
  }

  public function createPasswordHash($password){
    return hash('sha256', fbLib::PASS_SALT . $password);
  }

  public function verifyPassword($new_password_to_hash,$existing_hash){
    $new_hash = hash('sha256', fbLib::PASS_SALT . $new_password_to_hash);
    if($new_hash == $existing_hash){
      return True;
    }
    return False;
  }

  public function generateTempPassword ($length = 8) {
    $password = "";
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
    $maxlength = strlen($possible);
    if ($length > $maxlength) {
      $length = $maxlength;
    }
    $i = 0; 
    while ($i < $length) { 
      $char = substr($possible, mt_rand(0, $maxlength-1), 1);
      // have we already used this character in $password?
      if (!strstr($password, $char)) { 
        // no, so it's OK to add it onto the end of whatever we've already got...
        $password .= $char;
        // ... and increase the counter by one
        $i++;
      }
    }
    return $password;
  }

  public function randomHash($return_length = 30){
    $chars = ’0123456789abcdefghijklmnopqrstuvwxyz’;
    $rand = '';
    $len = strlen($chars);
    for ($i = 0; $i < $len; $i++) {
      $rand .= substr($chars,mt_rand(0, $len-1),1);
    }
    $hash = hash('sha256', fbLib::RAND_SALT . $rand);
    return substr($hash,0,$return_length);
  }

  public function urlRecStat(){
    $req = sfContext::getInstance()->getRequest();
    $param = array();
    if($req->hasParameter('type')){
      $param['type'] = intval($req->getParameter('type'));
    }
    if($req->hasParameter('year')){
      $param['year'] = intval($req->getParameter('year'));
      if($req->hasParameter('month')){
	$param['month'] = intval($req->getParameter('month'));
	if($req->hasParameter('day')){
	  $param['day'] = intval($req->getParameter('day'));
	  if($req->hasParameter('id')){
	    $param['id'] = intval($req->getParameter('id'));
	    $param['path'] = $param['year'] .'/'. $param['month'] .'/'. $param['day'] .' - '. $param['id'];
	    $param['desc'] = $param['month'] .'/'. $param['day'] .'/'. $param['year'] .' - '. $param['id'];
	    return $param;
	  }
	}
      }
    }
    return False;
  }

  ## take in a doctrine query object and build proper where clause for permissions
  ## designed to work with all objects for multi-selection
  public function addSecSQL($q,$alias){
    $sec = fbLib::getSec();
    $user_id = fbLib::getUserId();
    if($user_id){
      if($sec == fbLib::SEC_USER){ # current user data - all
	$q->andWhere($alias . '.user_id = ?',$user_id);
      }elseif($sec == fbLib::SEC_PRIVATE){ # current user data - only private
	$q->andWhere($alias . '.user_id = ?',$user_id);
	$q->andWhere($alias . '.sec = ?',fbLib::SEC_PRIVATE);
      }elseif($sec == fbLib::SEC_FRIEND){ # user friend data BOTH public and friend only
	if($friends = Doctrine_Core::getTable('UserForFriend')->getFriendIds($user_id)){
	  $q->andWhereIN($alias . '.sec',array(fbLib::SEC_PUBLIC,fbLib::SEC_FRIEND));
	  $q->andWhereIN($alias . '.user_id',$friends);
	}else{
	  return False;
	}
      }elseif($sec == fbLib::SEC_GROUP){ # user groups
	$group_id = intval(sfContext::getInstance()->getRequest()->getCookie('fbSecGid'));
	if($group_id){
	  if(Doctrine_Core::getTable('UserForGroup')->isMember($group_id,$user_id)){
	    $q->andWhere($alias . '.group_id = ?',$group_id);
	    $q->andWhere($alias . '.sec = ?',fbLib::SEC_GROUP);
	  }else{
	    return False;
	  }
	}else{
	  return False;
	}
      }else{ ## default is public
	$q->andWhere($alias . '.sec = 1');
      }
    }else{ ## default is public
      $q->andWhere($alias . '.sec = 1');
    }
    return $q;
  }

  public function addBoundsSQL($q,$source){
    $geo = fbLib::boundsFromRequest();
    if($geo['has_bounds']){
      $q->andWhere($source . '.lat > ? AND ' . $source . '.lat < ?', array($geo['sw_lat'],$geo['ne_lat']));
      if($geo['sw_lon'] > $geo['ne_lon']){
	$q->andWhere($source . '.lon > ? AND ' . $source . '.lon < ?', array($geo['ne_lon'],$geo['sw_lon']));
      }else{
	$q->andWhere($source . '.lon > ? AND ' . $source . '.lon < ?', array($geo['sw_lon'],$geo['ne_lon']));
      }
    }
    return $q;
  }
  
  public function addDateRangeSQL($q,$sql){
    $month_range = fbLib::getMonthRange();
    if( ($month_range[0] == 1) and ($month_range[1] == 12) ){
      return false;
    }
    $q->andWhere($sql . ' >= ? AND ' .$sql. ' <= ?', $month_range);
    return $sql . ' >= ? AND ' .$sql. ' <= ?' . $month_range[0] . ' - '.$month_range[1];
  }

  public function addSearchSQL($q,$cols){  
    $names = array();
    $values = array();
    foreach($cols as $i => $col){
      if($col['value'] and (strlen($col['value']) > 0) ){
	array_push($names,$col['table'] .'.'. $col['name'] . ' LIKE ?');
	if($col['type'] == 'equal'){
	  array_push($values, $col['value']);
	}elseif($col['type'] == 'contain'){
	  array_push($values, '%' . $col['value'] . '%');
	}else{ # default is starts with
	  array_push($values, $col['value'] . '%');
	}
      }
    }
    if(count($values) > 0){
      $q->andWhere(implode(' AND ',$names),$values);
    }
    return $q;
  }

  public function addFishSQL($q,$p){
    if( $fishId = fbLib::getFishId() ){
      if( ! $p['fish_table']){
	## special case for fish catch (report table) not many-to-many : one-to-many
	$q->andWhere($p['source'] . '.fish_id = ?',$fishId);
      }else{
	$q->innerJoin($p['source'] . '.' . $p['fish_table'] .' '. $p['fish_table_alias']);
	$q->andWhere($p['fish_table_alias'] . '.fish_id = ?',$fishId);
      }
    }
    return $q;
  }
  
  public function photoCount($p){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS photo_count')
      ->from($p['tableName'] . ' f')
      ->where('f.pid = ?',$p['key']);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['f_photo_count']);
  }

  # get all photos for an activity
  public function getPhotos($p){
    if( ! $p['limit']){ $p['limit'] = 20; }
    $count = fbLib::photoCount($p);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs, 
		 'record_offset' => intval($p['offset']), 'record_limit' => $p['limit']);
    if($count){
      $q = Doctrine_Query::create()
	->select('ffa.file_id,f.sec,f.caption,f.keyword,f.detail,DATE_FORMAT(f.date_create,\'%c/%e/%y\') AS date_create,f.ts,f.status,u.username,u.photo_id')
	->from($p['tableName'] . ' ffa')
	->innerJoin('ffa.File f')
	->innerJoin('f.User u')
	->where('ffa.pid = ?',$p['key'])
	->orderBy('f.date_create DESC')
	->limit($p['limit']);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $recs = array();
      if($rows && (count($rows) > 0) ){
	foreach($rows as $i => $row){
	  $file_id = intval($row['ffa_file_id']);
	  $recs[$i]['id'] = $file_id;
	  $recs[$i]['sec'] = intval($row['f_sec']);
	  $recs[$i]['caption'] = trim($row['f_caption']);
	  $recs[$i]['keyword'] = trim($row['f_keyword']);
	  $recs[$i]['detail'] = trim($row['f_detail']);
	  $recs[$i]['date_create'] = $row['f_date_create'];
	  $recs[$i]['ts'] = $row['f_ts'];
	  $recs[$i]['status'] = trim($row['f_status']);
	  $recs[$i]['username'] = $row['u_username'];
	  $recs[$i]['photo_id'] = $row['u_photo_id'];
	  $recs[$i]['replies'] = array();
	}
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
    }
   return $ret;
  }

  ## given a record, make sure the user has permission to view it
  public function getRecAllow($rec){
    $sec = $rec['sec'];
    if($sec == 1){ # rec is public
      return $rec;
    }else{
      $user = fbLib::getUser();
      $user_id = $user['id'];
      if($user_id){
	if($user['username'] == $rec['username']){ # user is owner
	  return $rec;
	}elseif($sec == 5){ # rec is for friends only
	  if($rec_user = Doctrine_Core::getTable('User')->getUser($rec['username'])){
	    if(Doctrine_Core::getTable('UserForFriend')->isFriend($user_id,$rec_user['id'])){
	      return $rec;
	    }
	  }
	}elseif($rec['sec'] == fbLib::SEC_GROUP){ # rec is for group members only
	  if($group_id = intval($rec['group_id'])){
	    if(Doctrine_Core::getTable('UserForGroup')->isMember($group_id,$user_id)){
	      return $rec;
	    }
	  }
	}
      }
    }
  }

  private function getActAllParam($a){
    $p = array('limit' => fbLib::RECORD_LIMIT,'source_limit' => 10,'offset' => 0);
    if($a){
      if($a['limit']){
	$p['limit'] = $a['limit'];
      }
      if($a['source_limit']){
	$p['source_limit'] = $a['source_limit'];
      }
      if($a['offset']){
	$p['offset'] = $a['offset'];
      }
    }
    return $p;
  }

  private function getActAllRecs($p){
    $act = array();
    $act['spots'] = Doctrine_Core::getTable('Spot')->getSpots($p);
    $act['catch'] = Doctrine_Core::getTable('Report')->getReports($p);
    $act['reports'] = Doctrine_Core::getTable('Blog')->getBlogs($p);
    $act['discs'] = Doctrine_Core::getTable('Disc')->getDiscs($p);
    $act['photos'] = Doctrine_Core::getTable('File')->getPhotos($p);    
    $all = array_merge($act['spots']['records'],$act['catch']['records'],$act['reports']['records'],$act['discs']['records'],$act['photos']['records']);
    return $all;
  }

  private function getActAllRecsBB($p){
    $act = array();
    $act['spots'] = Doctrine_Core::getTable('Spot')->getSpotsBBGW($p);
    $act['catch'] = Doctrine_Core::getTable('Report')->getReportsBBGW($p);
    $act['reports'] = Doctrine_Core::getTable('Blog')->getBlogsBBGW($p);
    $act['discs'] = Doctrine_Core::getTable('Disc')->getDiscsBBGW($p);
    $act['photos'] = Doctrine_Core::getTable('File')->getPhotosBBGW($p);    
    $all = array_merge($act['spots']['records'],$act['catch']['records'],$act['reports']['records'],$act['discs']['records'],$act['photos']['records']);
    return $all;
  }
  
  private function getActAllRecsBBGW($p){
    $act = array();
    $act['spots'] = Doctrine_Core::getTable('Spot')->getSpotsBBGW($p);
    $act['catch'] = Doctrine_Core::getTable('Report')->getReportsBBGW($p);
    $act['reports'] = Doctrine_Core::getTable('Blog')->getBlogsBBGW($p);
    $act['discs'] = Doctrine_Core::getTable('Disc')->getDiscsBBGW($p);
    $act['photos'] = Doctrine_Core::getTable('File')->getPhotosBBGW($p);    
    $all = array_merge($act['spots']['records'],$act['catch']['records'],$act['reports']['records'],$act['discs']['records'],$act['photos']['records']);
    return $all;
  }
  
  private function getActAllProcess($p,$all){
    $count_total = count($all);
    usort($all,function($a,$b){ return $b['uts'] - $a['uts']; });
    if($count_total > $p['limit']){
      $all = array_slice($all, ($p['offset'] * $p['limit']), $p['limit']);
    }
    $count = count($all);
    for($i=0; $i < $count; $i++){
      $rec = $all[$i];
      if($all[$i]['fb_source'] == 'catch'){
	$all[$i]['jrjIcon'] = '/images/map/catch24.png';
	$all[$i]['jrjHost'] = 'catch';
      }elseif($all[$i]['fb_source'] == 'report'){
	$all[$i]['jrjIcon'] = '/images/map/reportMed.png';
	$all[$i]['jrjHost'] = 'report';	
      }elseif($all[$i]['fb_source'] == 'disc'){
	$all[$i]['jrjIcon'] = '/images/map/blab24.png';
	$all[$i]['jrjHost'] = 'discuss';	
      }elseif($all[$i]['fb_source'] == 'photo'){
	$all[$i]['jrjIcon'] = fbLib::wwwFilePath($all[$i]['id']) . '25x25.jpg';	
	$all[$i]['jrjHost'] = 'photo';
      }else{
	$all[$i]['jrjIcon'] = '/images/map/spotMed.png';
	$all[$i]['jrjHost'] = 'spot';		
      }
    }
    $ret = array('count_total' => $count_total, 'count' => $count, 'records' => $all,
		 'record_offset' => intval($p['offset']), 'record_limit' => $p['limit']);
    return $ret;
  }
  
  public function getActAll($a){
    $p = $this->getActAllParam($a);
    $all = $this->getActAllRecs($p);
    $ret = $this->getActAllProcess($p,$all);
    return $ret;
  }

  public function getActAllBB($a){
    $p = $this->getActAllParam($a);
    $all = $this->getActAllRecsBB($p);
    $ret = $this->getActAllProcess($p,$all);
    return $ret;
  }
  
  public function isMobile(){
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    if(preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
      return True;
    }else{
      return False;
    }
  }

  function fetchPano($geo,$limit = 10){
    $from = 0;
    $to = $limit;
    $size = 'medium';
    $url = 'http://www.panoramio.com/map/get_panoramas.php';
    $r = new HttpRequest($url, HttpRequest::METH_GET);
    $query = array( 'set' => 'full','from' => $from,'to' => $to,'minx' => $geo['sw_lon'],'miny' => $geo['sw_lat'],'maxx' => $geo['ne_lon'],'maxy' => $geo['ne_lat'], 'size' => $size,'tag' => 'fishing','mapfilter' => 'false');
    $r->addQueryData($query);
    $result = $r->send()->getBody();
#    $result = $r->getUrl() .'?'.$r->getQueryData();
    return $result;
  }

  function getWiki($title){
    $ret = array('error' => True,'desc' => 'No Title','content' => False);
    if($title){
      if($cache = Doctrine_Core::getTable('WikiCache')->getByTitle($title)){   
	if($cache['expired'] == 1){
	  $ret = fbLib::getWikiHttp($title);
	  if( ! $ret['error']){
	    if($ret['content']){
	      if($cache = Doctrine_Core::getTable('WikiCache')->update(array('id' => $cache['id'],'content' => $ret['content']))){
		$ret['content'] = $cache['content'];
		$ret['error'] = False;
		$ret['desc'] = 'Wiki cache update';
	      }
	    }else{
	      $ret['desc'] = 'No content from cache update';
	    }
	  }
	}elseif( ! $cache['content']){
	  $ret['desc'] = 'No content in cache';
	}else{
	  $ret['content'] = $cache['content'];
	  $ret['error'] = False;
	  $ret['desc'] = 'Wiki cache hit ';
	}
      }else{
	$ret = fbLib::getWikiHttp($title);
	if( ! $ret['error']){
	  if($ret['content']){
	    if($cache = Doctrine_Core::getTable('WikiCache')->newRec(array('title' => $title,'content' => $ret['content']))){
	      $ret['content'] = $cache['content'];
	      $ret['error'] = False;
	      $ret['desc'] = 'Wiki cache load';
	    }
	  }else{
	    $ret['desc'] = 'No content from cache load';
	  }
	}
      }
    }
    return $ret;
  }

  function getWikiHttp($title){
    $ret = array('error' => True,'desc' => 'No Title','content' => False);
    $url = 'http://en.wikipedia.org/w/index.php';
    try{
      $r = new HttpRequest($url, HttpRequest::METH_GET);
      $query = array( 'action' => 'render', 'title' => $title);
      $r->addQueryData($query);
      $r->send();
      if ($r->getResponseCode() == 200){
	$ret['content'] = $r->getResponseBody();
	if($ret['content']){
	  $ret['error'] = False;
	}else{
	  $ret['desc'] = 'Content not retreived from getResponseBody()';
	}
      }else{
	$ret['desc'] = 'Response: ' . $r->getResponseCode();
      }
    }
    catch (Exception $ex) {
      if (isset($ex->innerException)){
        $ret['desc'] = $ex->innerException->getMessage();
      } else {
        $ret['desc'] = $ex;
      }
    }
    return $ret;
  }
  
  public function urlFix($url){
    if( ! preg_match('/^http:\/\//i',$url)){
      $url = 'http://' . $url;
    }
    return $url;
  }

}