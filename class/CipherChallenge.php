<?php
/**
 * CipherChallenge  Class to decrypt and encrypt
 * 
 */ 
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
class CipherChallenge {

 
  private $_uploadDirectory = "uploads"; 
  public $_errors = [];  
  private $_acceptFileExtensions = ['txt'];  
  private $_hashSalt = '4049539420'; 


   /**
    * Uploads a file.
    *
    * @return  boolean | string , string contain the error message else true.
    */
  public function uploadFile(){
 
    $fileName = $_FILES['encryptFile']['name'];
    $fileSize = $_FILES['encryptFile']['size'];
    $fileTmpName  = $_FILES['encryptFile']['tmp_name'];
    $fileType = $_FILES['encryptFile']['type'];

    @$fileExtension = strtolower(end(explode('.',$fileName)));

     $uploadPath = getcwd() .DS. $this->_uploadDirectory .DS. basename($fileName);  

        if (! in_array($fileExtension,$this->_acceptFileExtensions)) {
            $this->_errors[] = "This file extension is not allowed. Please upload ".implode(',',$this->_acceptFileExtensions)."file";
        }

        if ($fileSize > 2000000) {
            $this->_errors[] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
        }

        if (empty($this->_errors)) {
            $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

            if ($didUpload) {
                return $this->_readFile($uploadPath); 
            } else {
                 $this->_errors[] =  "An error occurred somewhere. Try again or contact the admin";
            }
        } else {
             return $this->_errors;
        }
   

  }
  /**
   * _readFile the file from the updloaded file to decrypt
   * 
   */ 
  private function _readFile($uploadPath){
    $content = file_get_contents($uploadPath);
    $decryptContent =  $this->_decrypt($content);
    return  $this->_writeFile($decryptContent);
  }
  /**
   * _writeFile  Writes the file of decrypt content  
   * 
   */
   
  private function _writeFile($content){
   
    $decryptFile = fopen(getcwd() .DS.'decryptFile.txt', "w") or die("Unable to open file!"); 
    fwrite($decryptFile, $content); 
    fclose($decryptFile); 
    return true;
  }
  /**
   * _encrypt  Encrpt the file according to given content with hashslat protection.
   * 
   */ 
  public function _encrypt($str) {
    $encrypted_text = "";
    $hashSalt =  $this->_hashSalt % 26;
    if($hashSalt < 0) {
        $hashSalt += 26;
    }
    $i = 0;
    while($i < strlen($str)) {
        $c = strtoupper($str{$i}); 

        if(($c >= "A") && ($c <= 'Z')) {
            if((ord($c) + $hashSalt) > ord("Z")) {
                $encrypted_text .= chr(ord($c) + $hashSalt - 26);
        } else {
            $encrypted_text .= chr(ord($c) + $hashSalt);
        }
      } else {
         if (preg_match("/[^0-9a-z\.\&\@]/i", $c)) { 
          $encrypted_text .= $c;
         } 
          $encrypted_text .= " ";
      }
      $i++;
    }
    return $encrypted_text;
  }

  /**
   *  _decrypt  _decrypt the file according to given content with sceret key as hashslat protection.
   */ 
  public function _decrypt($str) {
      $decrypted_text = "";
    $hashSalt = $this->_hashSalt % 26;
    if($hashSalt < 0) {
        $hashSalt += 26;
    }
    $i = 0;
    while($i < strlen($str)) {
        $c = strtoupper($str{$i}); 
        if(($c >= "A") && ($c <= 'Z')) {
            if((ord($c) - $hashSalt) < ord("A")) {
                $decrypted_text .= chr(ord($c) - $hashSalt + 26);
        } else {
            $decrypted_text .= chr(ord($c) - $hashSalt);
        }
      } else {
        if (preg_match("/[^0-9a-z\.\&\@]/i", $c)) { 
          $decrypted_text .= $c;
         } 
          $decrypted_text .= " ";
      }
      $i++;
    }
    return $decrypted_text;
  }

}