<?php
  /**
   * manipulate many-to-many tables for fish and activities 
   * @author     Joe Junkin
   * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
   */

class fbFish{
  
  const MAX_FISH = 20;
  
  public $response = array('error' => True, 'desc' => 'None');
  public $parentTableName = 'default';
  public $parentKey = 0;
  public $dateCol = 'date_create';
  public $fishTableName = 'default';
  public $offsetName = 'default';
  public $offset = 0;
  public $fishLimit = 20;
  public $type = 'default';
  public $mailURL = 'www.fishblab.com';
  
  function __construct($req){
    $fbLib = new fbLib();
    $this->user = $fbLib->restoreUser();
  }
  
  public function jsonResponse(){
    return json_encode($this->response);
  }

  private function paramInit(){
    $request = sfContext::getInstance()->getRequest();
    $this->param = array();
    $this->param['user_id'] = intval($this->user['id']);
    if($request->hasParameter('fish_id')){
      $this->param['fish_id'] = intval($request->getParameter('fish_id'));
    }
    if($request->hasParameter('pid')){
      $this->param['pid'] = intval($request->getParameter('pid'));
    }
    if($request->hasParameter('offset')){
      $this->param['offset'] = intval($request->getParameter('offset'));
    }
    if($request->hasParameter('flag')){
      $this->param['flag'] = intval($request->getParameter('flag'));
    }
    return $this->param;
  }
  
  public function validate(){
    if($this->param['user_id']){
      return True;
    }else{
      $this->response['desc'] = 'User not logged in';
    }
    return False;
  }
  
  public function validateParent(){
    if($this->param['pid'] > 0){
      return True;
    }else{
      $this->response['desc'] = 'pKey parameter not found';
    }
    return False;
  }
  
  public function validateFish(){
    if($this->param['fish_id'] > 0){
      return True;
    }else{
      $this->response['desc'] = 'Fish id not found';
    }
    return False;
  }
  
  public function validateEdit(){
    if($this->validate()){
      if($this->validateFish()){
	if($this->validateParent()){
	  if($parent = Doctrine_Core::getTable($this->parentTableName)->getRec($this->param['pid'])){
	    if($parent['username'] != $this->user['username']){
	      $this->response['desc'] = 'User not permitted to edit fish';
	    }else{
	      return True;
	    }
	  }else{
	    $this->response['desc'] = 'Parent not found';
	  }
	}
      }
    }
    return False;
  }
    
  public function validateNew(){
    if($this->validate()){
      if($this->validateFish()){
	if($this->validateParent()){
	  if($fish = $this->getFish($this->param)){
	    $this->response['desc'] = 'Fish already added to ' . $this->type;
	  }else{
	    if($parent = Doctrine_Core::getTable($this->parentTableName)->getRec($this->param['pid'])){
	      if($parent['username'] != $this->user['username']){
		$this->response['desc'] = 'User not permitted to add fish';
	      }else{
		return True;
	      }
	    }else{
	      $this->response['desc'] = 'Parent not found';
	    }
	  }
	}
      }
    }
    return False;
  }
    
  public function create(){
    $this->paramInit();
    if($this->validateNew()){
      if(Doctrine_Core::getTable($this->fishTableName)->addFish($this->param)){
	$this->response['desc'] = 'New Fish for ' . $this->type . ' saved';
	$this->response['error'] = False;
	$this->response['record'] = $this->getFish($this->param);
	return True;
      }else{
	$this->response['desc'] = 'Database Insert Failed for Fish for ' . $this->type;
      }
    }
    return False;
  }

  public function edit(){
    $this->paramInit();
    if($this->validateEdit()){
      if(Doctrine_Core::getTable($this->fishTableName)->editFish($this->param)){
	$this->response['desc'] = 'Fish for ' . $this->type . ' saved';
	$this->response['error'] = False;
	$this->response['record'] = $this->getFish($this->param);
	return True;
      }else{
	$this->response['desc'] = 'Database edit Failed for Fish for ' . $this->type;
      }
    }
    return False;
  }
  
  public function delete(){
    $this->paramInit();
    if($this->validateEdit()){
      $status = $this->deleteFish($this->param);
      $this->response['desc'] = 'Fish deleted';
      $this->response['error'] = False;
      $this->response['pid'] = $this->param['pid'];
      $this->response['fish_id'] = $this->param['fish_id'];
      return True;
    }
    return False;
  }

  public function deleteFish($p){
    $q = Doctrine_Query::create()
      ->delete()
      ->from($this->fishTableName . ' ffa')
      ->where('ffa.fish_id = ?',$p['fish_id'])
      ->andWhere('ffa.pid = ?',$p['pid'])
      ->execute();
  }
  
  public function parentFishAjax(){
    $this->paramInit();
    if($this->validateParent()){
      $this->response['result'] = $this->parentFish($this->param['pid']);
      $this->response['pid'] = $this->param['pid'];
      $this->response['error'] = False;
      $this->response['desc'] = 'Fish fetched';
      return True;
    }
    return False;
  }

  ## use 2 keys - fish_id and pid - to find fish info
  public function getFish($p){
    $q = Doctrine_Query::create()
      ->select('ffa.fish_id,ffa.pid,f.name,f.name_sci,f.detail,f.alias,f.wiki_title')
      ->from('Fish f')
      ->innerJoin('f.' . $this->fishTableName . ' ffa')
      ->innerJoin('f.User u')
      ->where('ffa.fish_id = ?',$p['fish_id'])
      ->andWhere('ffa.pid = ?',$p['pid']);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    if($rows && ($row = $rows[0]) ){
      $rec = array();
      $rec['id'] = intval($row['ffa_fish_id']);
      $rec['pid'] = intval($row['ffa_pid']);
      $rec['name'] = $row['f_name'];
      $rec['name_sci'] = $row['f_name_sci'];
      $rec['detail'] = $row['f_detail'];
      $rec['alias'] = $row['f_alias'];
      $rec['wiki_title'] = $row['f_wiki_title'];
      return $rec;
    }
    return False;
  }

  public function fishCount($pid){
    if( ! $pid){
      return 0;
    }
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS fish_count')
      ->from($this->fishTableName . ' ffa')
      ->where('ffa.pid = ?',$pid);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['ffa_fish_count']);
  }

  ## get all fish for an activity
  public function parentFish($pid){
    $count = $this->fishCount($pid);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => intval($this->offset), 'record_limit' => $this->recordLimit);
    if($count){
      $q = Doctrine_Query::create()
	->select('ffa.fish_id,ffa.pid,f.name,f.name_sci,f.detail,f.alias,f.wiki_title')
	->from('Fish f')
	->innerJoin('f.' . $this->fishTableName . ' ffa')
	->innerJoin('f.User u')
	->where('ffa.pid = ?',$pid);
      if($this->offset > 0){
	$q->offset($this->offset * $this->recordLimit);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $recs = array();
      if($rows && (count($rows) > 0) ){
	foreach($rows as $i => $row){
	  $recs[$i]['id'] = intval($row['ffa_fish_id']);
	  $recs[$i]['pid'] = intval($row['ffa_pid']);
	  $recs[$i]['name'] = $row['f_name'];
	  $recs[$i]['name_sci'] = $row['f_name_sci'];
	  $recs[$i]['detail'] = $row['f_detail'];
	  $recs[$i]['alias'] = $row['f_alias'];
	  $recs[$i]['wiki_title'] = $row['f_wiki_title'];
	}
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
    }
   return $ret;
  }
    
}