<?php

class Zend_View_Helper_Filesize
{
    public function filesize($dsize)
	{
		if (strlen($dsize) <= 9 && strlen($dsize) >= 7) {
			$dsize = number_format($dsize / 1048576,1);
			return "$dsize MB";
		} elseif (strlen($dsize) >= 10) {
			$dsize = number_format($dsize / 1073741824,1);
			return "$dsize GB";
		} else {
			$dsize = number_format($dsize / 1024,1);
			return "$dsize KB";
		}
	}
}
