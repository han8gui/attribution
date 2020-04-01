<?php

namespace bing\attribution;

class GeoPhone
{
	static $data_file = __DIR__ . '/phone.dat'; //数据文件所在位置
	
	static $opList = [
	    1 => '移动',
        2 => '联通',
        3 => '电信',
        4 => '电信虚拟运营商',
        5 => '联通虚拟运营商',
        6 => '移动虚拟运营商'
    ];

    static $opSimpleList = [
        1 => '移动',
        2 => '联通',
        3 => '电信',
        4 => '电信',
        5 => '联通',
        6 => '移动'
    ];

	public static function find($tel)
    {
		if (empty($GLOBALS['__cached_phone_data'])){
			$GLOBALS['__cached_phone_data'] = file_get_contents(self::$data_file);
		}
		
		$bytes = &$GLOBALS['__cached_phone_data'];
		$index_offset = unpack('L', substr($bytes,4,4));
		$index_offset = intval($index_offset[1]);
		$rec_size = (strlen($bytes)-$index_offset)/9;
		$tel_prefix = intval(substr($tel,0,7));
		
		$hitItem = null;
        $hitItemIdx = [];
		$left_pos = 0;
		$right_pos = $rec_size;
		while ($left_pos < ($right_pos - 1)) {
		
			$pos = $left_pos + intval(($right_pos - $left_pos)/2);
			$idx = unpack('L',substr($bytes,$index_offset+$pos*9,4));
            $idx = intval($idx[1]);

			if ($idx<$tel_prefix){
				$left_pos = $pos;
			}else if ($idx>$tel_prefix){
				$right_pos = $pos;
			}else{
				$hitItemIdx = unpack('Lidx_pos/ctype',substr($bytes,$index_offset+$pos*9+4,5));
				$c_pos = intval($hitItemIdx['idx_pos']);
				for($i=0; $i<100; $i++){
					if ($bytes[$i+$c_pos]=="\0"){
						$hitItem = substr($bytes,$c_pos,$i);
						break;
					}
				}
				break;
			}
		}
		
		if (!empty($hitItem)){
			$ret = explode("|", $hitItem);
            $type = $hitItemIdx['type'];
			return [
			    'from' => $ret[0] . self::$opSimpleList[$type],
			    'province' => $ret[0],
                'city' => $ret[1],
                'postcode' => $ret[2],
                'area_code' => $ret[3],
                'op' => self::$opList[$type]
			];
		}else{
			return null;
		}
	}
}

