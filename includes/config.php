<?php

define('OPENAI_API_KEY', getenv('OPENAI_API_KEY'));   
define('ACTIVE_API', 'openai');
define('DEBUG_MODE', true);

define('DATA_FOLDER', __DIR__ . '/../data/');
define('LOGO_FOLDER', __DIR__ . '/../logos/');

if (!file_exists(DATA_FOLDER)) mkdir(DATA_FOLDER, 0777, true);
if (!file_exists(LOGO_FOLDER)) mkdir(LOGO_FOLDER, 0777, true);
?>