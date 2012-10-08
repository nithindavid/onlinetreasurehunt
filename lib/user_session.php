<?php
  require_once 'src/facebook.php';
  
  class UserSession {
    private $_mysql_host = ''; 
    private $_mysql_user = ''; 
    private $_mysql_pwd = ''; 
    private $_mysql_db = '';
	    // private $_mysql_host = 'localhost'; 
    // private $_mysql_user = 'root'; 
    // private $_mysql_pwd = ''; 
    // private $_mysql_db = 'connect';
    private $_mysql_table = 'users';
    private $_mysql_data = 'questions';
    private $_mysql_log = 'log';
    
    private $facebook_app_id = ''; 
    private $facebook_secret = ''; 
    private $facebook_extended_permission = 'email,status_update,publish_stream'; 
    

    private $facebook;   
    private $facebook_uid = null;
    private $facebook_user = null;
    private $facebook_na=null;
    public $oauth_provider = 'normal';
    public $level = null;
    
    public function __construct(){
      if (!session_start())
        session_start(); 
      $this->facebook = new Facebook(array(
      'appId'  => $this->facebook_app_id,
      'secret' => $this->facebook_secret 
      ));
    }
    
    private function connect_to_db(){
      mysql_connect($this->_mysql_host,$this->_mysql_user,$this->_mysql_pwd) or die("can't connect to database"); 
      mysql_select_db($this->_mysql_db) or die("can't connect to database");
    }
    
    private function close_db(){
      mysql_close(); 
    }
     
    public function is_login(){
      if (isset($_SESSION['username'])){
        $this->detect_user();
        return true; 
      }
      return false; 
    }
    
    private function detect_user(){
      $this->oauth_provider =  $_SESSION['oauth_provider'];   
      if ($this->oauth_provider =='facebook'){
        $this->facebook_uid = $_SESSION['oauth_id'];      
      }
    }
    
    public function get_facebook_login_url(){
      $loginUrl = $this->facebook->getLoginUrl(
    	array(
    		'scope' => $this->facebook_extended_permission
    	)
      );   
      return $loginUrl;    
    }
    
    private function get_user_facebook(){
     $this->facebook_uid = $this->facebook->getUser();  
      if ($this->facebook_uid){
        try {          
          $this->facebook_user = $this->facebook->api('/me'); 
        }catch(Exception $e){}         
      }
    }
    
    public function get_question(){
      if ($this->is_login())
      {
	$checker = $_SESSION['level'];
	$query = "SELECT question,answer,image,title FROM %s WHERE id = '%s'"; 
        $sql = sprintf($query,$this->_mysql_data,$checker); 
        $this->connect_to_db();
        $query = mysql_query($sql) or die(mysql_error()); 
        $result = mysql_fetch_array($query);}
	if (empty($result)){
	  $this->logout(); 
        
	}
	return $result;
    }
    
    private function save_new_fb_user_or_session(){
      if (!empty($this->facebook_user)){
        $query = "SELECT id,level FROM %s WHERE oauth_provider ='facebook' AND oauth_id = '%s'"; 
        $sql = sprintf($query,$this->_mysql_table,$this->facebook_uid); 
        $this->connect_to_db();
        $query = mysql_query($sql) or die(mysql_error()); 
        $result = mysql_fetch_array($query);

	if (empty($result)){
          $query = "INSERT INTO %s (oauth_provider,oauth_id,username,level,password) VALUES('facebook','%s','%s','1','%s')"; 
          $sql = sprintf($query,$this->_mysql_table,$this->facebook_user['id'],$this->facebook_user['username'],$this->facebook_user['name'] );
          mysql_query($sql)or die(mysql_error());
        }
	$this->level=$result['level']=1;
        $_SESSION['username'] = $this->facebook_user['username']; 
	$_SESSION['name']=$this->facebook_user['name']; 
        $_SESSION['oauth_provider'] = 'facebook'; 
        $_SESSION['oauth_id'] = $this->facebook_user['id']; 
        $_SESSION['level'] = $this->level;
	$this->close_db();
      }
    }
    
    public function login_from_facebook($redirect =''){
      $this->get_user_facebook();
      $this->save_new_fb_user_or_session();
      if ($this->facebook_user){
        if ($redirect)
          header('location:'.$redirect); 
          die();
      }
    }
    
    private function get_facebook_logout($oauth_provider){
      if ($oauth_provider =='facebook'){
        $this->get_user_facebook();
        if ($this->facebook_user){
          return $this->facebook->getLogoutUrl(); 
        }        
      }
    }
    
    public function logout(){
      if ($this->is_login()){
        $oauth_provider = $_SESSION['oauth_provider']; 
        session_destroy(); 
        $logout_fb = $this->get_facebook_logout($oauth_provider); 
        if ($logout_fb){
          header('location:'.$logout_fb); 
        }        
      }
    }
    
    public function update_level(){
        $ok = false;
        $query = "UPDATE users SET level= '%s', lvl_time = CURRENT_TIMESTAMP  WHERE oauth_id='%s'"; 
        $this->connect_to_db();
	$sql = sprintf($query,$_SESSION['level'],$_SESSION['oauth_id']); 
        mysql_query($sql) or die(mysql_error()); 
        $this->close_db();
	$blahh=$_SESSION['level']-1;
	$slel = sprintf("I Cleared level %s in Drishti BrainStrain.",$blahh); 
	try{
					$publishStream = $this->facebook->api("/" . $this->facebook_user . "/feed", 'post', array(
						'message'		=> $slel,
						'link'			=> 'http://cetdrishti.com/brainstrain',
						'picture'		=> 'http://cetdrishti.com/img/logo.png',
						'name'			=> 'Brain Strain | Online Treasure Hunt | Drishti 2012',
						'caption'		=> 'cetdrishti.com/brainstrain',
						'description'	=> 'Drishti 2012 presents to you BrainStrain, an epic hunt for logic that does not exist and answers that contain absolutely no discernible meaning at all to the untrained eye. Join to make a dent in your desk the shape of your head. Awesome prizes and millions of facepalms awaits you inside.',
						));
				}catch(FacebookApiException $e){
				}
	
    }
    public function get_board(){
	$query = "SELECT oauth_id,level,password FROM %s ORDER BY level DESC, lvl_time ASC"; 
        $sql = sprintf($query,$this->_mysql_table); 
        $this->connect_to_db();
        $query = mysql_query($sql) or die(mysql_error()); 
        $result = mysql_fetch_array($query);
	$rank =1;
	while($result){
	  echo " <tr class=\"c12\">
		      <td class=\"c2\">$rank</td>
	              <td class=\"c2 s2\"><a href=\"http://www.facebook.com/$result[oauth_id]\">$result[password]</td>
		      <td class=\"c2 s4\">$result[level]</td>

	        </tr>";
		$rank++;
          $result = mysql_fetch_array($query);
	}
    }
    public function get_rank(){
      $query = "SELECT username,level FROM %s ORDER BY level DESC, lvl_time ASC"; 
        $sql = sprintf($query,$this->_mysql_table); 
        $this->connect_to_db();
        $query = mysql_query($sql) or die(mysql_error()); 
        $result = mysql_fetch_array($query);
	$rank =1;
	while($result){
	  if($result['username']==$_SESSION['username'])
	   { echo $rank;
	   break;}
	  $rank++;
	  $result = mysql_fetch_array($query);
	}
    }
    public function update_log($vert=null){
       $this->connect_to_db();
       $query = "INSERT INTO %s (level,ans,username) VALUES('%s','%s','%s')";
       $sql = sprintf($query,$this->_mysql_log,$_SESSION['level'],$vert,$_SESSION['name']);
       mysql_query($sql)or die(mysql_error());
    }
  }
