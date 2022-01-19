<?php namespace App\Libraries;

class KeyGen
{
    
    public function generateString($length, $seed)  
    {  
        $characters  = "0123456789";  
        $characters .= "abcdefghijklmnopqrstuvwxyz";  
        $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";  
        
        $randStr = "T";  
        
        $nmr_loops = $length;  
        
        while ($nmr_loops--)  
        {  
            mt_srand($seed++);
            $randStr .= $characters[mt_rand(0, strlen($characters) - 1)];  
        }  
        
        return $randStr;  
    }  

    public function generateId($seed){
        $strCardNo = "T";
  
        $key1 = $this->randomInt($seed);
        $encKey1 = $this->encryptKey($key1);
  
        $key2 = $this->randomInt($seed+1000);
        $encKey2 = $this->encryptKey($key2);
  
        $strCardNo .= $this->intToStr($encKey1, 8).$this->intToStr($encKey2, 8);
        
  
        return $strCardNo;
      }
  
    public function generatePwd($seed){
        
  
        $key1 = $this->randomInt($seed);
        $encKey1 = $this->encryptKey($key1);

        $strCardNo = $this->intToStr($encKey1, 8);
        
        return $strCardNo;
    }
  
    private function randomInt($seed)
    {
        mt_srand($seed);        
        return (int)round(mt_rand(0, 0xFFF) % 0x1000 * microtime(true));
    }
      
    private function encryptKey($key){
    
        $destKey1 = ($key >> 16) & 0xFF;
        $destKey2 = $key & 0xFF;
        $destKey3 = ($key >> 24) & 0xFF;
        $destKey4 = ($key >> 8) & 0xFF;

        $destKey = 0;
        $destKey += $destKey1<<24;
        $destKey += $destKey2<<16;
        $destKey += $destKey3<<8;
        $destKey += $destKey4;

        return $destKey;

    }
  
    private function intToStr($btData, $len)
    {
        
        $strCardNo = "";
                    
        for ($i = 0; $i < $len; $i++)
        {
            $strCardNo .= $this->ByteToChar(($btData >> (4 * $i)) & 0xF);
            
        }
        
        return $strCardNo;
    }
  
    private function ByteToChar($btByte)
    {
        if ($btByte >= 0 && $btByte < 0xA)
            return $btByte;

        if ($btByte == 0xA)
            return 'b';

        if ($btByte == 0xB)
            return 'f';

        if ($btByte == 0xC)
            return 'd';

        if ($btByte == 0xD)
            return 'a';

        if ($btByte == 0xE)
            return 'c';

        if ($btByte == 0xF)
            return 'k';

        return 't';
    }
  
  
  
  
  
}