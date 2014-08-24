<?php
/*
 * Filename   : User.class.php
 * Purpose    : 
 *
 * @author    : Sheikh Iftekhar
 * @project   : 
 * @version   : 1.0.0
 * @copyright : 
 */

class User 
{
   private $db;
   
   private $userId;
   private $firstName;
   private $surname;
   private $age;
   private $gender;

  /**
   * Purpose : User object initiator
   *
   * @access : public
   * @param  : none
   * @return : none
   */
   public function __construct($db, $stdObject = null)
   {
      $this->db = $db;
      
      if($stdObject)
      {
         if(is_object($stdObject))
         {
            $this->init($stdObject);
         }
         else
         {
            $sql  = "SELECT * FROM " . USERS_TBL . " WHERE id = $stdObject";
            
            try
            {
               $rows = $this->db->select($sql);
            }
            catch(Exception $Exception){}

            if(isset($rows[0]))
            {
               $this->init($rows[0]);
            }
         }
      }
   }

   private function init($stdObject)
   {
      $fields = (array)$stdObject;
      foreach($fields as $key => $value)
      {
         $function_name = "set" . str_replace(
                                    ' ', 
                                    '', 
                                    ucwords(str_replace('_', ' ', $key))
                                  );
         $this->$function_name($value);
      }
   }

  /**
   * Purpose : Sets user id
   *
   * @access : public
   * @param  : $userId - int
   * @return : none
   */
   public function setId($userId)
   {
      $this->userId = $userId;
   }

  /**
   * Purpose : Sets user email
   *
   * @access : public
   * @param  : $email - string
   * @return : none
   */
   public function setFirstName($firstName)
   {
      $this->firstName = $firstName;
   }

  /**
   * Purpose : Sets user password
   *
   * @access : public
   * @param  : $password - string
   * @return : none
   */
   public function setSurname($surname)
   {
      $this->surname = $surname;
   }

  /**
   * Purpose : Sets user status
   *
   * @access : public
   * @param  : $status - int
   * @return : none
   */
   public function setAge($age)
   {
      $this->age = $age;
   }

  /**
   * Purpose : Sets user authKey (depricated)
   *           This function will be removed.
   *
   * @access : public
   * @param  : $authKey - string
   * @return : none
   */
   public function setGender($gender)
   {
      $this->gender = $gender;
   }
  /**
   * Purpose : gets user id
   *
   * @access : public
   * @param  : none
   * @return : int
   */
   public function getId()
   {
      return $this->userId;
   }

  /**
   * Purpose : gets user email
   *
   * @access : public
   * @param  : none
   * @return : string
   */
   public function getFirstName()
   {
      return $this->firstName;
   }

  /**
   * Purpose : gets user password
   *
   * @access : public
   * @param  : none
   * @return : string - md5 formated
   */
   public function getSurname()
   {
      return $this->surname;
   }

  /**
   * Purpose : gets user auth-key
   *
   * @access : public
   * @param  : none
   * @return : string
   */
   public function getAge()
   {
      return $this->age;
   }

  /**
   * Purpose : gets user first name
   *
   * @access : public
   * @param  : none
   * @return : string
   */
   public function getGender()
   {
      return $this->gender;
   }

  /**
   * Purpose : ads user data into database
   *
   * @access : public
   * @param  : none
   * @return : void
   */
   public function insert()
   {
      $data             = array();
      $data['table']    = USERS_TBL;
      $data['data']     = array(
         'firstName'    => $this->getFirstName(),
         'surname'      => $this->getSurname(),
         'age'          => $this->getAge(),
         'gender'       => $this->getGender()
      );
      
      try
      {
         $id = $this->db->insert($data);
      }
      catch(Exception $Exception){}
      
      return $id;      
   }

  /**
   * Purpose : update user data into database
   *
   * @access : public
   * @param  : none
   * @return : void
   */
   public function update()
   {
      $data             = array();
      $data['table']    = USERS_TBL;
      $data['data']     = array(
        'firstName'    => $this->getFirstName(),
         'surname'      => $this->getSurname(),
         'age'          => $this->getAge(),
         'gender'       => $this->getGender()
      );
      $data['where']    = "id = " . $this->getId();
      
      try
      {
         $this->db->update($data);
      }
      catch(Exception $Exception){}
      
      return true;
   }

  /**
   * Purpose : deletes user from database
   *
   * @access : public
   * @param  : none
   * @return : void
   */
   public function delete()
   {
      $data          = array();
      $data['table'] = USERS_TBL;
      $data['where'] = "id = " . $this->getId();
      
      try
      {
         $this->db->delete($data);
      }
      catch(Exception $Exception){}
      
      return true;
   }
   
   
   public function saveFriends($userId, $friends)
   {
   	  if(!is_array($friends)) return;
   	  
   	  foreach($friends as $key => $value)
	  	{
         $data['table']    = USERS_FRIENDS_TBL;
         $data['data']     = array(
            'user_id'    => $userId,
            'friend_id'  => $value
         );
         
         try
         {
            $id = $this->db->insert($data);
         }
         catch(Exception $Exception){}
      }
      return;
   }
 
   
}