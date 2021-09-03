<?php

//--------------------------------------------------------------------
// App Namespace
//--------------------------------------------------------------------
// This defines the default Namespace that is used throughout
// CodeIgniter to refer to the Application directory. Change
// this constant to change the namespace that all application
// classes should use.
//
// NOTE: changing this will require manually modifying the
// existing namespaces of App\* namespaced-classes.
//
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
|--------------------------------------------------------------------------
| Composer Path
|--------------------------------------------------------------------------
|
| The path that Composer's autoload file is expected to live. By default,
| the vendor folder is in the Root directory, but you can customize that here.
*/
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
|--------------------------------------------------------------------------
| Timing Constants
|--------------------------------------------------------------------------
|
| Provide simple ways to work with the myriad of PHP functions that
| require information to be in seconds.
*/
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2592000);
defined('YEAR')   || define('YEAR', 31536000);
defined('DECADE') || define('DECADE', 315360000);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

//defined('BASE_API')  || define('BASE_API', 'https://'.$_SERVER['HTTP_HOST'].'/');
defined('BASE_API')  || define('BASE_API', 'http://localhost/isales-new/');
// defined('BASE_API')  || define('BASE_API', 'localhost/');
//defined('BASE_API')  || define('BASE_API', 'https://43.231.129.121:443/');

// defined('BASE_API')  || define('BASE_API', 'http://182.23.42.200:8002/isales/');
// defined('BASE_API')  || define('BASE_API', 'localhost/');
// defined('_API_KEY')  || define('PABX_API_KEY', '4c2467e087068e59a927017334a87d70');
defined('PABXURL')  || define('PABXURL', 'https://arwich.c-icare.cc/apipbx/');
defined('PABX_API_KEY')  || define('PABX_API_KEY', '4c2467e087068e59a927017334a87d70');
defined('PABX_APIB_KEY')  || define('PABX_APIB_KEY', "Bearer ".PABX_API_KEY);
defined('RECAPTCHA_SECRET_KEY')  || define('RECAPTCHA_SECRET_KEY', '6LcNKFEaAAAAAEe9WENk140kPFMjj4EUQ79XVw78');
defined('STATUS_KAWIN')  || define('STATUS_KAWIN', array('Belum Menikah','Sudah Menikah','Janda/Duda'));
defined('HUBUNGAN')  || define('HUBUNGAN', array('Suami/Istri','Anak Kandung','Orang Tua Kandung'));
defined('JK')  || define('JK', array('L'=>'Laki-laki','P'=>'Perempuan'));
defined('SATUAN')  || define('SATUAN', array('Bulanan' => 'Monthly', 'Tahunan' => 'Yearly'));
defined('CHECKED')  || define('CHECKED', array('Not Checked','Approved By QA','Suspend Billing','Call TFC', 'Data Black List', 
'Reject Data Lapse', 'Reject Kesehatan', 'Reject Pekerjaan', 'Reject Double Data', 'Cancel By QA', 'Cancel By Email', 'Cancel By Prospect'));
define('NIMG', "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");
