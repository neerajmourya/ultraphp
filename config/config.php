<?php
ob_start();
session_start();
define('BASE_DIR', dirname(__DIR__));
define('APP_DIR', BASE_DIR.'/app/');

//Enter the base url
define('BASE_URL', 'http://www.egrapes.in/');

//Enter the sub folder if installed in subfolder i.e.,http://www.egrapes.in/ultraphp/
//define('SUB_FOLDER', '/ultraphp/');

//Enable Default routing
define('DEFAULT_ROUTES',TRUE);

//Application Key
define('APP_KEY','sLjNs%!VDg*SHU#7W@YwVjn6X?GN_DQCc*tsGy#mJmFX%6gM');

//Encryption Keys
define('ENC_KEY', '42cKIe#{:77q#HF&W(Mn$azrA[+Pn%0-');
define('ENC_IV', 'qh6tL[<;Db32}|Z>pdeX?-@G8jxv).oa');