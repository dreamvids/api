<?php

/**
 * This file should be the webroot (in apache's vhost settings)
 * It aims at reducing the risk of security flaw by forcing this file to be the only entry point of the whole app.
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'index.php';