<?php

  	function is_login(){ 
      if(!isset($_SESSION['logged_in']))
        return false;
      else if($_SESSION['logged_in']==TRUE)
        return true;
      else return false;  
  	}

    function isPermitCategory($objAdmin, $objCat){
      if(is_null($objAdmin) || is_null($objCat)) 
        return false;
      $state_active = 0;
      switch($objCat->cat_id){
          case 1: $state_active = $objAdmin->stf_app_01; break;
          case 2: $state_active = $objAdmin->stf_app_02; break;
          case 3: $state_active = $objAdmin->stf_app_03; break;
          case 4: $state_active = $objAdmin->stf_app_04; break;
          case 5: $state_active = $objAdmin->stf_app_05; break;
          case 6: $state_active = $objAdmin->stf_app_06; break;
          case 7: $state_active = $objAdmin->stf_app_07; break;
          case 8: $state_active = $objAdmin->stf_app_08; break;
          case 9: $state_active = $objAdmin->stf_app_09; break;
          case 10: $state_active = $objAdmin->stf_app_10; break;
          case 11: $state_active = $objAdmin->stf_app_11; break;
          case 12: $state_active = $objAdmin->stf_app_12; break;
          case 13: $state_active = $objAdmin->stf_app_13; break;
          case 14: $state_active = $objAdmin->stf_app_14; break;
          case 15: $state_active = $objAdmin->stf_app_15; break;
          case 16: $state_active = $objAdmin->stf_app_16; break;
          case 17: $state_active = $objAdmin->stf_app_17; break;
          case 18: $state_active = $objAdmin->stf_app_18; break;
          case 19: $state_active = $objAdmin->stf_app_19; break;
          case 20: $state_active = $objAdmin->stf_app_20; break;
          case 21: $state_active = $objAdmin->stf_app_21; break;
          case 22: $state_active = $objAdmin->stf_app_22; break;
          case 23: $state_active = $objAdmin->stf_app_23; break;
          case 24: $state_active = $objAdmin->stf_app_24; break;
          case 25: $state_active = $objAdmin->stf_app_25; break;
          case 26: $state_active = $objAdmin->stf_app_26; break;
          case 27: $state_active = $objAdmin->stf_app_27; break;
          case 28: $state_active = $objAdmin->stf_app_28; break;
          case 29: $state_active = $objAdmin->stf_app_29; break;
          case 30: $state_active = $objAdmin->stf_app_30; break;

        default: break; 
      }

      return $state_active==1?true:false;
    }
    
    function getPermitedCategories($categories, $objAdmin, $category=0){
      $arrEnCat = [];
      foreach($categories as $objCat){
        if(isPermitCategory($objAdmin, $objCat))
          array_push($arrEnCat, convertEnCat($objCat, $category));  
      }
      return $arrEnCat;
    }

    function convertEnCat($objCat, $category=0){
      $objEnCat = new \StdClass;
      $objEnCat->cat_id = $objCat->cat_id;
      $objEnCat->cat_name = $objCat->cat_name;
      $objEnCat->cat_title = $objCat->cat_title;
      $objEnCat->cat_selected =  $category==$objCat->cat_id? true:false;
      return $objEnCat;
    }

    function getMenuClass() {

      return array(
          'menu_item_1' => 'menu-item-li',
          'menu_item_2' => 'menu-item-li'
      );
    }
    //사이드바의 선택상태 초기화 배렬을 반환해주는 함수
    function getSidebarClass() {

      return array(
          'side_item_1' => '',
          'side_item_2' => '',
          'side_item_3' => '',
          'side_item_4' => '',
          'side_item_5' => '',
          'side_item_6' => '',
          'side_item_7' => ''
      );
    }

    function is_appLogin($logKey){ 
      if(!isset($_SESSION[$logKey]))
        return false;
      else if($_SESSION[$logKey]==TRUE)
        return true;
      else return false;  
  	}


    function isAppOn($objStaff, $category) {
      $bAppOn = false;
      switch($category){
          case 1: $bAppOn = $objStaff->stf_app_01 == 1 ? true:false; break; 
          case 2: $bAppOn = $objStaff->stf_app_02 == 1 ? true:false; break; 
          case 3: $bAppOn = $objStaff->stf_app_03 == 1 ? true:false; break;
          case 4: $bAppOn = $objStaff->stf_app_04 == 1 ? true:false; break;
          case 5: $bAppOn = $objStaff->stf_app_05 == 1 ? true:false; break;
          case 6: $bAppOn = $objStaff->stf_app_06 == 1 ? true:false; break;
          case 7: $bAppOn = $objStaff->stf_app_07 == 1 ? true:false; break;
          case 8: $bAppOn = $objStaff->stf_app_08 == 1 ? true:false; break;
          case 9: $bAppOn = $objStaff->stf_app_09 == 1 ? true:false; break;
          case 10: $bAppOn = $objStaff->stf_app_10 == 1 ? true:false; break;
          case 11: $bAppOn = $objStaff->stf_app_11 == 1 ? true:false; break;
          case 12: $bAppOn = $objStaff->stf_app_12 == 1 ? true:false; break;
          case 13: $bAppOn = $objStaff->stf_app_13 == 1 ? true:false; break;
          case 14: $bAppOn = $objStaff->stf_app_14 == 1 ? true:false; break;
          case 15: $bAppOn = $objStaff->stf_app_15 == 1 ? true:false; break;
          case 16: $bAppOn = $objStaff->stf_app_16 == 1 ? true:false; break;
          case 17: $bAppOn = $objStaff->stf_app_17 == 1 ? true:false; break;
          case 18: $bAppOn = $objStaff->stf_app_18 == 1 ? true:false; break;
          case 19: $bAppOn = $objStaff->stf_app_19 == 1 ? true:false; break;
          case 20: $bAppOn = $objStaff->stf_app_20 == 1 ? true:false; break;
          case 21: $bAppOn = $objStaff->stf_app_21 == 1 ? true:false; break;
          case 22: $bAppOn = $objStaff->stf_app_22 == 1 ? true:false; break;
          case 23: $bAppOn = $objStaff->stf_app_23 == 1 ? true:false; break;
          case 24: $bAppOn = $objStaff->stf_app_24 == 1 ? true:false; break;
          case 25: $bAppOn = $objStaff->stf_app_25 == 1 ? true:false; break;
          case 26: $bAppOn = $objStaff->stf_app_26 == 1 ? true:false; break;
          case 27: $bAppOn = $objStaff->stf_app_27 == 1 ? true:false; break;
          case 28: $bAppOn = $objStaff->stf_app_28 == 1 ? true:false; break;
          case 29: $bAppOn = $objStaff->stf_app_29 == 1 ? true:false; break;
          case 30: $bAppOn = $objStaff->stf_app_30 == 1 ? true:false; break;
          default: $bAppOn = true; break; 
      }
      return $bAppOn;
    }


    function deleteDir($dir)
    {
      if (substr($dir, strlen($dir)-1, 1) != DIRECTORY_SEPARATOR)
          $dir .= DIRECTORY_SEPARATOR;

      if ($handle = opendir($dir))
      {
          while ($obj = readdir($handle))
          {
              if ($obj != '.' && $obj != '..')
              {
                  if (is_dir($dir.$obj))
                  {
                      if (!deleteDir($dir.$obj))
                          return false;
                  }
                  else if (is_file($dir.$obj))
                  {
                      if (!unlink($dir.$obj))
                          return false;
                  }
              }
          }

          closedir($handle);

          if (!@rmdir($dir))
              return false;
          return true;
      }
      return false;
    }


    function parseDir($dir, &$arrInfo)
    {
      if (substr($dir, strlen($dir)-1, 1) != DIRECTORY_SEPARATOR)
          $dir .= DIRECTORY_SEPARATOR;

      if ($handle = opendir($dir))
      {
          while ($obj = readdir($handle))
          {
              if ($obj != '.' && $obj != '..')
              {
                  if (is_dir($dir.$obj))
                  {
                    parseDir($dir.$obj, $arrInfo);
                  }
                  else if (is_file($dir.$obj))
                  {
                    array_push($arrInfo, $dir.$obj);
                  }
              }
          }

          closedir($handle);

      }

    }


    function getSubDir($dir, $search, &$arrInfo)
    {
      if (substr($dir, strlen($dir)-1, 1) != DIRECTORY_SEPARATOR)
          $dir .= DIRECTORY_SEPARATOR;

      if(!file_exists($dir)){
        return;
      }

      if ($handle = opendir($dir))
      {
          while ($obj = readdir($handle))
          {
              if ($obj != '.' && $obj != '..')
              {
                  if (is_dir($dir.$obj))
                  {
                    if(strlen($search) > 0) {
                      if(strpos($obj, $search) !== false)
                        array_push($arrInfo, $obj);
                    } else 
                        array_push($arrInfo, $obj);
                  }                  
              }
          }

          closedir($handle);
      }
    }

    
    function getSubFile($dir, &$arrInfo)
    {
      if (substr($dir, strlen($dir)-1, 1) != DIRECTORY_SEPARATOR)
          $dir .= DIRECTORY_SEPARATOR;

      if(!file_exists($dir)){
        return;
      }

      if ($handle = opendir($dir))
      {
          while ($obj = readdir($handle))
          {
              if ($obj != '.' && $obj != '..')
              {
                  if (is_file($dir.$obj))
                  {
                    array_push($arrInfo, $obj);
                  }                  
              }
          }

          closedir($handle);
      }
    
    }



?>
