<?php

namespace Jonnybarnes\UnicodeTools;

class UnicodeTools {
		/**
		 * This is a callback that parses a string for any occurence of
		 * \uXXXXX\ - a unicode codepoint, and then calls the utf8CPtoHex
		 * function output the raw unicode character
		 *
		 * @returns string
		 */
	public function convertUnicodeCodepoints($input) {
		$output = preg_replace_callback('/\\\\u([0-9a-fA-F]{4,6}\\\\)/i', 'self::utf8CPtoHex', $input);
		return $output;
	}

	/**
	 * This takes a codepoint of the form \uXXXX(X)\ and uses
	 * PHP's chr() function to ouptput a raw UTF-8 encoded character
	 *
	 * @returns string
	 */
	public function utf8CPtoHex($cp) {
		$num = $cp[1];
		$num = '0x' . $num;
		$bin = base_convert($cp[1], 16, 2);
		if($num <= 0x7F) { //U+0000 - U+007F -- 1 byte
			$bin = str_pad($bin, 7, "0", STR_PAD_LEFT);
			$returnbin = '0' . $bin;
			$utf8hex = chr('0x' . base_convert($returnbin, 2, 16));
			return $utf8hex;
		}
		if($num <= 0x7FF) { //U+0080 - U+07FF -- 2 bytes
			$bin = str_pad($bin, 11, "0", STR_PAD_LEFT);
			$bin1 = substr($bin, 0, 5); $returnbin1 = '110' . $bin1;
			$bin2 = substr($bin, 5); $returnbin2 = '10' . $bin2;
			$utf8hex = chr('0x' . base_convert($returnbin1, 2, 16)) . chr('0x' . base_convert($returnbin2, 2, 16));
			return $utf8hex;
		}
		if($num <= 0xFFFF) { //U+0800 - U+FFFF -- 3 bytes
			$bin = str_pad($bin, 16, "0", STR_PAD_LEFT);
			$bin1 = substr($bin, 0, 4); $returnbin1 = '1110' . $bin1;
			$bin2 = substr($bin, 4, 6); $returnbin2 = '10' . $bin2;
			$bin3 = substr($bin, 10); $returnbin3 = '10' . $bin3;
			$utf8hex = chr('0x' . base_convert($returnbin1, 2 ,16)) . chr('0x' . base_convert($returnbin2, 2, 16)) . chr('0x' . base_convert($returnbin3, 2, 16));
			return $utf8hex;
		}
		if($num <= 0x10FFFF) { //U+10000 - U+10FFFF -- 4 bytes
			$bin = str_pad($bin, 21, "0", STR_PAD_LEFT);
			$bin1 = substr($bin, 0, 3); $returnbin1 = '11110' . $bin1;
			$bin2 = substr($bin, 3, 6); $returnbin2 = '10' . $bin2;
			$bin3 = substr($bin, 9, 6); $returnbin3 = '10' . $bin3;
			$bin4 = substr($bin, 15); $returnbin4 = '10' . $bin4;
			$utf8hex = chr('0x' . base_convert($returnbin1, 2, 16)) . chr('0x' . base_convert($returnbin2, 2, 16)) . chr('0x' . base_convert($returnbin3, 2, 16)) . chr('0x' . base_convert($returnbin4, 2, 16));
			return $utf8hex;
		}
		if($num >= 0x110000) { //U+110000 -- invalid UTF-8 character
			throw new \Exception('Codepoint maps to invalid Unicode character');
		}
	}
}