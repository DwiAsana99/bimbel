<?php
namespace App\Helpers;

use Fungsi;
use DB;
use App\Settingprefix;

class NoUnik
{
    private $prefix;
    private $lengthPrefix;
    private $digit;
    private $strDigit;

    public function __construct($id = null) {
        $data = Settingprefix::find($id);
        
        $this->digit = $data->Digit;
        $this->strDigit = $this->genDigit($data->Digit);
        $this->prefix = $this->genPrefix($data->Date, $data->Prefix, $data->Pembatas);
        $this->lengthPrefix = strlen($this->prefix);
    }

    public function noUnik($query, $column)
    {
        $last = $this->getLast($query, $column, $this->prefix);
        return Fungsi::autoNumber($last ?: $this->prefix.$this->strDigit, $this->lengthPrefix, $this->digit);
    }

    private function genPrefix($isDate, $prefix, $pembatas)
    {
        $txt = [];
        $prefixIndex = 0;
        if ($prefix != null && $prefix != '') {
            $prefixIndex++;
            $txt[$prefixIndex] = $prefix;
        }
        if ($isDate == true) {
            $prefixIndex++;
            $txt[$prefixIndex] = Fungsi::sessionTglYM();
        }

        if ($pembatas != null && $pembatas != '') {
            $jadi = implode($pembatas,$txt).$pembatas;
        }else{
            $jadi = implode('',$txt);
        }

        return $jadi;
    }

    private function genDigit($length)
    {
        $digit = '';
        for ($i=0; $i < $length; $i++) { 
            $digit .= '0';
        }
        
        return $digit;
    }

    private function getLast($query, $column, $prefix = null)
    {
        return $query->when($prefix, function ($query) use ($prefix, $column) {
            return $query->where($column, 'LIKE', $prefix.'%');
        })->orderBy($column, 'DESC')->pluck($column)->first();
    }
}
