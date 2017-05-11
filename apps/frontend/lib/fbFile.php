<?php
  /**
   * base file class used for all parent photos
   * @author     Joe Junkin
   * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
   */

class fbFile{
  
  const MAX_FILE = 20;
  
  public $response = array('error' => True, 'desc' => 'None');
  public $parentTableName = 'default';
  public $parentKey = 0;
  public $dateCol = 'date_create';
  public $fileTableName = False;
  public $offsetName = 'default';
  public $offset = 0;
  public $recordLimit = 20;
  public $type = 'default';
  public $mailURL = 'www.fishblab.com';
  
  function __construct($req){
    $fbLib = new fbLib();
    $this->user = $fbLib->restoreUser();
  }
  
  public function jsonResponse(){
    return json_encode($this->response);
  }

  ## params used in all files
  private function paramInit(){
    $request = sfContext::getInstance()->getRequest();
    $this->param = array();
    if($request->hasParameter('id')){
      $this->param['id'] = intval($request->getParameter('id'));
    }
    if($request->hasParameter('pid')){
      $this->param['pid'] = intval($request->getParameter('pid'));
    }
    if($request->hasParameter('sec')){
      $this->param['sec'] = intval($request->getParameter('sec'));
      if($this->param['sec'] == fbLib::SEC_GROUP){
	$this->param['group_id'] = intval($request->getParameter('group_id'));
      }
    }
    if($request->hasParameter('caption')){
      $this->param['caption'] = $request->getParameter('caption');
    }
    if($request->hasParameter('keyword')){
      $this->param['keyword'] = $request->getParameter('keyword');
    }
    if($request->hasParameter('detail')){
      $this->param['detail'] = $request->getParameter('detail');
    }
    if($request->hasParameter('date_create')){
      $this->param['date_create'] = $request->getParameter('date_create');
    }
    if($request->hasParameter('lat')){
      $this->param['lat'] = $request->getParameter('lat');
    }
    if($request->hasParameter('lon')){
      $this->param['lon'] = $request->getParameter('lon');
    }
    if($request->hasParameter('offset')){
      $this->param['offset'] = intval($request->getParameter('offset'));
    }
    if($request->hasParameter('flag')){
      $this->param['flag'] = intval($request->getParameter('flag'));
    }
    return $this->param;
  }

  public function validateUser(){
    if($this->user['id']){
      return True;
    }else{
      $this->response['desc'] = 'User not logged in';
    }
    return False;
  }
  
  public function validateParent(){
    if($pid = intval($this->param['pid'])){
      if($pid > 0){
	return True;
      }else{
	$this->response['desc'] = 'pid parameter not valid';
      }
    }else{
      $this->response['desc'] = 'pid parameter not found';
    }
    return False;
  }
  
  public function validateEdit(){
    if($this->validateUser()){
      if($this->validateParent()){
	if($this->param['id']){
	  $file_id = $this->param['id'];
	  if($file_id > 0){
	    if($file = $this->getPhoto($file_id)){
	      if($file['username'] != $this->user['username']){
		$this->response['desc'] = 'User not owner of photo';
	      }else{
		return True;
	      }
	    }else{
	      $this->response['desc'] = 'Photo not found';
	    }
	  }else{
	    $this->response['desc'] = 'photo id parameter not valid';
	  }
	}else{
	  $this->response['desc'] = 'photo id parameter not found';
	}
      }
    }
    return False;
  }
  
  ## restore the parent object and verify the user has permission to add photos
  public function validateCreate(){
    if($this->validateUser()){
      if($this->validateParent()){
	if($parent = Doctrine_Core::getTable($this->parentTableName)->getRec($this->param['pid'])){
	  if($parent['username'] == $this->user['username']){
	    return True;
	  }elseif($parent['sec_photo'] == 1){
	    return True;
	  }else{
	    $this->response['desc'] = 'User not permitted to add photos';
	  }
	}else{
	  $this->response['desc'] = 'Parent record not restored';
	}
      }
    }
    return False;
  }
  
  public function create(){
    $request = sfContext::getInstance()->getRequest();
    $this->paramInit();
    if($this->validateCreate()){
      $file = $request->getFiles('file');
      if(! $file){
	$this->response['desc'] = 'Uploaded file was not recognized';
      }else{
	$ret = fbLib::validateFile($file);
	if($ret['error']){
	  $this->response['desc'] = $ret['desc'];
	}else{
	  $this->param['user_id'] = $this->user['id'];
	  $this->param['size'] = $file['size'];
	  $this->param['ftype'] = ($this->fileType ? $this->fileType : 1);
	  $file_db = Doctrine_Core::getTable('File')->createFile($this->param);
	  if( ! $file_db){
	    $this->response['desc'] = 'New file record not created in DB';
	  }else{
	    $this->param['id'] = $file_db['id'];
	    if( ! $this->createParent()){
	      $this->response['desc'] = 'Parent portion of photo insert failed';
	    }else{
	      $ret = fbLib::createAndPushFiles($file,$file_db['id']);
	      $file_db['pid'] = $this->param['pid'];
	      $this->response['desc'] = 'New photo saved';
	      $this->response['error'] = False;
	      $this->response['record'] = $file_db;
	      return True;
	    }
	  }
	}
      }
    }
    return False;
  }

  public function createParent(){
    if(Doctrine_Core::getTable($this->fileTableName)->addFile(array('pid' => $this->param['pid'], 'id' => $this->param['id']))){
      return True;
    }
    return False;
  }
  
  public function edit(){
    $request = sfContext::getInstance()->getRequest();
    $this->paramInit();
    if($this->validateEdit()){
      $this->param['user_id'] = $this->user['id'];
      $file = Doctrine_Core::getTable('File')->updateFile($this->param);
      if($file['id'] > 0){
	$file['pid'] = $this->param['pid'];
	$this->response['desc'] = 'File saved';
	$this->response['error'] = False;
	$this->response['record'] = $file;
	return True;
      }else{
	$this->response['desc'] = 'Database Edit Failed for photo';
      }
    }
    return False;
  }

  public function editGeo(){
    $request = sfContext::getInstance()->getRequest();
    $this->paramInit();
    if($this->validateEdit()){
      $geo = fbLib::boundsFromRequest();
      if( ( ! $geo['lat']) || (! $geo['lon']) ){
	$this->response['desc'] = 'Lat/Lon value missing';
      }else{
	$this->param['geo'] = $geo;
	$file = Doctrine_Core::getTable('File')->editGeo($this->param);
	if($file['id'] > 0){
	  $this->response['desc'] = 'Geo updated';
	  $this->response['error'] = False;
	  $this->response['record'] = $file;
	  return True;
	}
      }
    }
    return False;
  }
  
  public function delete(){
    $request = sfContext::getInstance()->getRequest();
    $this->paramInit();
    if($this->validateEdit()){
      $file_id = $this->param['id'];
      $status = $this->deleteParentFile($this->param);
      Doctrine_Core::getTable('File')->deleteFile($file_id);
      fbLib::deleteServerImages($file_id);
      $this->response['desc'] = 'Photo deleted';
      $this->response['error'] = False;
      $this->response['pid'] = $this->param['pid'];
      $this->response['id'] = $file_id;
      return True;
    }
    return False;
  }

  public function deleteParentFile($p){
    if($p['id'] and $p['pid']){
      $q = Doctrine_Query::create()
	->delete()
	->from($this->fileTableName . ' f')
	->where('f.pid = ?',$p['pid'])
	->andWhere('f.file_id = ?',$p['id'])
	->execute();
      return True;
    }
    return False;
  }

  public function flag(sfWebRequest $request){
    $request = sfContext::getInstance()->getRequest();
    if($this->validateUser()){
      $file_id = $this->param['id'];
      $user_id = $this->user['id'];
      if($file_id <= 0){
	$this->response['desc'] = 'photo id parameter not found';
      }elseif($this->param['flag'] <= 0){
	$this->response['desc'] = 'flag parameter not found';
      }else{
	$flag_id = $this->param['flag'];
	$file = Doctrine_Core::getTable('File')->getFile($file_id);
	if( ! $file){
	  $this->response['desc'] = 'file not found';
	}else{
	  $this->response['error'] = False;
	  if($flag = Doctrine_Core::getTable('FileForFlag')->getFileFlag($file_id,$user_id)){
	    ## flag exists, update
	    $flag_new = Doctrine_Core::getTable('FileForFlag')->updateFileFlag($file_id,$user_id,$flag_id);
	    $this->response['desc'] = 'existing flag updated';
	  }else{
	    ## create a new flag
	    $flag_new = Doctrine_Core::getTable('FileForFlag')->addFileFlag($file_id,$user_id,$flag_id);
	    $this->response['desc'] = 'new flag created';
	  }
	  return True;
	}
      }
    }
    return False;
  }

  public function getPhotosAjax(){
    $request = sfContext::getInstance()->getRequest();
    $this->paramInit();
    if($this->validateParent()){
      $this->response['result'] = $this->getPhotos($this->param['pid']);
      $this->response['pid'] = $this->param['pid'];
      $this->response['error'] = False;
      $this->response['desc'] = 'Photos fetched';
      return True;
    }
    return False;
  }

  public function getPhoto($file_id){
    $q = Doctrine_Query::create()
      ->select('ffa.file_id,ffa.pid,f.sec,f.caption,f.keyword,f.detail,DATE_FORMAT(f.date_create,\'%c/%e/%y\') AS date_create,f.ts,f.status,u.username,u.photo_id')
      ->from($this->fileTableName . ' ffa')
      ->innerJoin('ffa.File f')
      ->innerJoin('f.User u')
      ->where('ffa.file_id = ?',$file_id);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $recs = array();
    $reply = new fbPhotoReply();
    $fish = new fbPhotoFish();
    if($row = $rows[0]){
      $id = intval($row['ffa_file_id']);
      $rec['id'] = $id;
      $rec['pid'] = intval($row['ffa_pid']);
      $rec['sec'] = intval($row['f_sec']);
      $rec['caption'] = trim($row['f_caption']);
      $rec['keyword'] = trim($row['f_keyword']);
      $rec['detail'] = trim($row['f_detail']);
      $rec['date_create'] = $row['f_date_create'];
      $rec['ts'] = $row['f_ts'];
      $rec['status'] = trim($row['f_status']);
      $rec['username'] = $row['u_username'];
      $rec['photo_id'] = $row['u_photo_id'];
      $rec['reply_count'] = $reply->replyCount($id);
      $rec['fish_count'] = $fish->fishCount($rec['id']);
      return $rec;
    }
    return False;
  }

  ## just the file with no parent
  public function getPhoto2($file_id){
    $q = Doctrine_Query::create()
      ->select('f.id,f.sec,f.caption,f.keyword,f.detail,DATE_FORMAT(f.date_create,\'%c/%e/%y\') AS date_create,f.ts,f.status,u.username,u.photo_id')
      ->from('File f')
      ->innerJoin('f.User u')
      ->where('f.id = ?',$file_id);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $recs = array();
    $reply = new fbPhotoReply();
    $fish = new fbPhotoFish();
    if($row = $rows[0]){
      $rec['id'] = intval($row['f_id']);
      $rec['sec'] = intval($row['f_sec']);
      $rec['caption'] = trim($row['f_caption']);
      $rec['keyword'] = trim($row['f_keyword']);
      $rec['detail'] = trim($row['f_detail']);
      $rec['date_create'] = $row['f_date_create'];
      $rec['ts'] = $row['f_ts'];
      $rec['status'] = trim($row['f_status']);
      $rec['username'] = $row['u_username'];
      $rec['photo_id'] = $row['u_photo_id'];
      $rec['reply_count'] = $reply->replyCount($rec['id']);
      $rec['fish_count'] = $fish->fishCount($rec['id']);
      return $rec;
    }
    return False;
  }

  public function photoCount($pid){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS photo_count')
      ->from($this->fileTableName . ' ffa')
      ->innerJoin('ffa.File f')
      ->innerJoin('f.User u')
      ->where('ffa.pid = ?',$pid);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['ffa_photo_count']);
  }

  ## get all photos for an activity
  public function getPhotos($pid){
    $count = $this->photoCount($pid);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => intval($this->offset), 'record_limit' => $this->recordLimit);
    if($count){
      $q = Doctrine_Query::create()
	->select('ffa.file_id,ffa.pid,f.sec,f.caption,f.keyword,f.detail,DATE_FORMAT(f.date_create,\'%c/%e/%y\') AS date_create,f.ts,f.status,u.username,u.photo_id')
	->from($this->fileTableName . ' ffa')
	->innerJoin('ffa.File f')
	->innerJoin('f.User u')
	->where('ffa.pid = ?',$pid)
	->andWhere('f.ftype < 10')
	->orderBy('f.date_create DESC')
	->limit($this->recordLimit);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $recs = array();
      $reply = new fbPhotoReply();
      $fish = new fbPhotoFish();
      if($rows && (count($rows) > 0) ){
	foreach($rows as $i => $row){
	  $file_id = intval($row['ffa_file_id']);
	  $recs[$i]['id'] = $file_id;
	  $recs[$i]['pid'] = intval($row['ffa_pid']);
	  $recs[$i]['sec'] = intval($row['f_sec']);
	  $recs[$i]['caption'] = trim($row['f_caption']);
	  $recs[$i]['keyword'] = trim($row['f_keyword']);
	  $recs[$i]['detail'] = trim($row['f_detail']);
	  $recs[$i]['date_create'] = $row['f_date_create'];
	  $recs[$i]['ts'] = $row['f_ts'];
	  $recs[$i]['status'] = trim($row['f_status']);
	  $recs[$i]['username'] = $row['u_username'];
	  $recs[$i]['photo_id'] = $row['u_photo_id'];
	  $recs[$i]['reply_count'] = $reply->replyCount($file_id);
	  $recs[$i]['fish_count'] = $fish->fishCount($file_id);
	}
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
    }
   return $ret;
  }
 
  ## direct php file upload stuff, NOT USED
  public function getFile(){
    $file = array('error' => True);
    if ($_FILES["file"]["error"] > 0){
      $file['desc'] = $_FILES["file"]["error"];
    }else{
      $file['name'] = $_FILES["file"]["name"];
      $file['type'] = $_FILES["file"]["type"];
      $file['size']  = $_FILES["file"]["size"];
      $file['tmp_name'] = $_FILES["file"]["tmp_name"];
      $file['error'] = False;
    }
    return $file;
  }
   
}