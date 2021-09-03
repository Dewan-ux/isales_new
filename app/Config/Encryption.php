<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Encryption configuration.
 *
 * These are the settings used for encryption, if you don't pass a parameter
 * array to the encrypter for creation/initialization.
 */
class Encryption extends BaseConfig
{

	/*
	  |--------------------------------------------------------------------------
	  | Encryption Key Starter
	  |--------------------------------------------------------------------------
	  |
	  | If you use the Encryption class you must set an encryption key (seed).
	  | You need to ensure it is long enough for the cipher and mode you plan to use.
	  | See the user guide for more info.
	 */

	public $key = 'f8d3b62a9b6d1962c22b4640730167a78220aa2b7e7a9d109553ba12d4fea38426916cd9a05756c09cc22d97eac4f5182391714da743241192ad9df058c27538';

	/*
	  |--------------------------------------------------------------------------
	  | Encryption driver to use
	  |--------------------------------------------------------------------------
	  |
	  | One of the supported drivers, eg 'OpenSSL' or 'Sodium'.
	  | The default driver, if you don't specify one, is 'OpenSSL'.
	 */
	public $driver = 'OpenSSL';

}
