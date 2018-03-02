<?php
namespace ultraphp\core;
/* 
 * Copyright (C) 2017 Neeraj Mourya (neerajmorya@gmail.com)
 * Github Profile : https://github.com/neerajmourya
 * Website : http://neeraj.egrapes.in
 * Company Website : http://www.egrapes.in
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


/**
 * UltraEncryption class
 * 
 * Provides methods to encrypt and decrypt string
 * 
 * @author  Neeraj Mourya <neeraj@egrapes.in>
 * @link    http://neeraj.egrapes.in/
 * @since   1.0.0
 */
class UltraEncryption {

    /**
     * @const string
     */
    const ENCRYPT_METHOD = 'AES-256-CBC';

    /**
     * @var string
     */
    private static $key;
    /**
     * @var string
     */
    private static $iv;

    /**
     * Initialise keys
     */
    public static function initialiseKeys($key, $iv) {
        self::$key = hash('sha256', $key);
        self::$iv = substr(hash('sha256', $iv), 0, 16);
    }

    /**
     * Encrypt the given string.
     * 
     * @param string $string
     * @return string
     * @since 1.0.0
     */
    public static function encrypt($string) {
        return base64_encode(openssl_encrypt($string, self::ENCRYPT_METHOD, self::$key, 0, self::$iv));
    }

    /**
     * Decrypt the given string.
     * @param string $string
     * @return string
     */
    public static function decrypt($string) {
        return openssl_decrypt(base64_decode($string), self::ENCRYPT_METHOD, self::$key, 0, self::$iv);
    }
    
}
