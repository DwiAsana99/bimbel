<?php
namespace App\Helpers;

class Terbilang 
{
    private $bil;
    
    public function __construct()
    {
        $this->bil = 0;
    }
    
    public function terbilang($n)
    {
        $this->bil = $n;
        $bilangan = array("nol","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan","sepuluh","sebelas");
        
        if($this->bil < 12)             
        {                 
            return $bilangan[$this->bil];
        }
        else if($this->bil < 20)             
        {                 
            $b = $this->bil % 10;
            return $this->terbilang($b)." belas ";
        }
        else if($this->bil < 100)             
        {                 
            $b = $this->bil % 10;
            $c = $this->bil / 10;
            
            if($b == 0)
            {
                return $this->terbilang($c). " puluh ";
            }
            else
            {
                return $this->terbilang($c). " puluh ".$bilangan[$b];
            }
        }
        else if($this->bil < 200)             
        {                 
            $b = $this->bil % 100;
            $str = "";
            if($b == 0)
            {
                return "seratus ";
            }
            else
            {
                return "seratus ".$this->terbilang($b);
            }
        }
        else if($this->bil < 1000)             
        {                 
            $b = $this->bil % 100;
            $c = $this->bil / 100;
            
            if($b == 0)
            {
                return $bilangan[$c]. " ratus ";
            }
            else
            {
                return $bilangan[$c]. " ratus ".$this->terbilang($b);
            }
        }
        else if($this->bil < 2000)             
        {                 
            $b = $this->bil % 1000;
            $str = "";
            if($b == 0)
            {
                return "seribu ";
            }
            else
            {
                return "seribu ".$this->terbilang($b);
            }
        }
        else if($this->bil < 1000000)             
        {                 
            $b = $this->bil % 1000;
            $c = $this->bil / 1000;
            
            if($b == 0)
            {
                return $this->terbilang($c). " ribu ";
            }
            else
            {
                return $this->terbilang($c). " ribu ".$this->terbilang($b);
            }
        }
        else if($this->bil < 1000000000)             
        {                 
            $b = $this->bil % 1000000;
            $c = $this->bil / 1000000;
            
            if($b == 0)
            {
                return $this->terbilang($c). " juta ";
            }
            else
            {
                return $this->terbilang($c). " juta ".$this->terbilang($b);
            }
        }
        else if($this->bil == 1000000000)
        {
            return $this->terbilang($this->bil / 1000000000) . " milyar ";
        }
        else
        {
            return "Maksimal bilangan 1 milyar";
        }
    }
}