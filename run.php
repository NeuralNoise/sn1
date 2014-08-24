<?php
/*
* Filename   : run.php
* Purpose    : 
*
* @author    : Sheikh Iftekhar <siftekher@gmail.com>
* @project   : 
* @version   : 1.0.0
* @copyright : 
*/

  define('DOCUMENT_ROOT',    $_SERVER['DOCUMENT_ROOT']);
  define('CLASS_DIR',        DOCUMENT_ROOT . '/sn1/classes');
  define('SUPER_CONTROLLER_URL_PREFIX',      '/sn1/run.php/');
  
  set_include_path(get_include_path() . PATH_SEPARATOR . CLASS_DIR);

  require_once('DB.class.php');
  require_once('config.php');
  require_once('Utils.php');
  
  session_start();
  
  try
  {
     $dbObj = new DB($dbInfo);
  }
  catch(Exception $e)
  {
     die($e->getMessage());
  }

  $params   =  array();
  $params['db_link'] = $dbObj;
  
  $className = str_replace(SUPER_CONTROLLER_URL_PREFIX, '', $_SERVER['REQUEST_URI']);
  
  $className = explode('/', $className);
  $params['cmdList'] = $className;

  $thisclassName = new Utils($params);
  $thisclassName->run();
?>
