<?php
require_once('User.class.php');

class Utils 
{
	  private $db;
    private $params;
    private $cmdList;
   
    public function __construct($params)
    {
       $this->db       = $params['db_link'];
       $this->cmdList  = $params['cmdList'];
    }
	  
	  public function run()
	  {
	  	  $cmd   = $this->cmdList[0];
        switch ($cmd)
        {         
           case 'read'               : $screen = $this->read();                   break;
           case 'friends'            : $screen = $this->getFriends();             break;
           case 'friends_of_friends' : $screen = $this->getFriendsOfFriends();    break;
           case 'suggested_friends'  : $screen = $this->getSuggestedFriends();    break;
           default                   : $screen = $this->invalidParameter();      
        }
	  }
	  
	  private function read()
	  {
	  	 $this->db->query('TRUNCATE TABLE user');
	  	 $this->db->query('TRUNCATE TABLE user_friend');
      
       $params['db_link']   = $this->db;
       
	  	 $filePath = __DIR__.'\..\data.json';
	  	 
	  	 $data = json_decode(file_get_contents($filePath));
	
       foreach($data as $key => $value)
       {
       	  $userObj = new User($this->db);
       	  $userObj->setId($value->id);
       	  $userObj->setFirstName($value->firstName);
       	  $userObj->setSurname($value->surname);
       	  $userObj->setAge($value->age);
       	  $userObj->setGender($value->gender);
       	  
          $userObj->insert();   
       	  
       	  $friends = $value->friends;
       	  $this->saveFriends($value->id, $friends);
       }
       
       echo 'Succesfully import data';
       exit;
	  }
	  
	  private function saveFriends($userId, $friends)
	  {
	  	 $userObj = new User($this->db);
	  	 $userObj->saveFriends($userId, $friends);
	  	 
	  	 return;
	  }
	  
	  private function getFriends()
	  {
	  	 if(!isset($this->cmdList[1])) return;
	  	 
       $query  = "SELECT C.* FROM user_friend AS A LEFT JOIN user AS C ON (A.user_id = C.id)
                 WHERE A.friend_id  = " . $this->cmdList[1];
       
       $data = $this->db->select($query);
       echo json_encode($data);
       exit;
	  }
	  
	  private function getFriendsOfFriends()
	  {
	  	 if(!isset($this->cmdList[1])) return;
	  	 $query = "SELECT * FROM user where id IN (SELECT distinct(y.friend_id) FROM user_friend x JOIN user_friend y
                 ON y.user_id = x.friend_id AND y.friend_id <> x.user_id LEFT JOIN user_friend z
                 ON z.friend_id = y.friend_id AND z.user_id = x.user_id WHERE x.user_id = ".$this->cmdList[1]."
                 AND z.user_id IS NULL)";
       
       $data = $this->db->select($query);
       echo json_encode($data);
       exit;
	  }
	  
	  private function getSuggestedFriends()
	  {
	  	 if(!isset($this->cmdList[1])) return;
       
       $query = "SELECT * FROM user where id IN (SELECT y.friend_id FROM user_friend x
                 LEFT JOIN user_friend y ON y.user_id = x.friend_id AND y.friend_id <> x.user_id
                 LEFT JOIN user_friend z ON z.friend_id = y.friend_id  AND z.user_id = x.user_id
                 WHERE x.user_id = ". $this->cmdList[1] ." AND z.user_id IS NULL
                 GROUP BY y.friend_id HAVING COUNT(*) >= 2)";

       $data = $this->db->select($query);
       if(!is_null($data))
       echo json_encode($data);
       exit;
	  }
	  
	  
	  private function invalidParameter()
	  {
	  	  echo 'Invalid Parameter';
	  	  exit;
	  }
	  
}
?>