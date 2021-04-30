<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.anthonygfigueroa.ca
 * @since      1.0.0
 *
 * @package    Shoplync_Store_Info
 * @subpackage Shoplync_Store_Info/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Shoplync_Store_Info
 * @subpackage Shoplync_Store_Info/admin
 * @author     Anthony Figueroa <mail@anthonygfigueroa.ca>
 */
class Shoplync_Store_Info_Helpers {
	
	/**
	 * Forked From: https://stackoverflow.com/a/31107425
	 *
	 * Generate a random string, using a cryptographically secure 
	 * pseudorandom number generator (random_int)
	 *
	 * This function uses type hints now (PHP 7+ only), but it was originally
	 * written for PHP 5 as well.
	 * 
	 * For PHP 7, random_int is a PHP core function
	 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
	 * 
	 * @param int $length      How many characters do we want?
	 * @param string $keyspace A string of all possible characters
	 *                         to select from
	 * @return string
	 */
	public static function random_str(
		int $length = 10,
		string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
	): string {
		if ($length < 1) {
			throw new \RangeException("Length must be a positive integer");
		}
		$pieces = [];
		$max = mb_strlen($keyspace, '8bit') - 1;
		for ($i = 0; $i < $length; ++$i) {
			$pieces []= $keyspace[random_int(0, $max)];
		}
		return implode('', $pieces);
	}
	
	
}