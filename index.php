<?php

/**
 * cPanel docroot is the Laravel project root (see repo .htaccess).
 * Fallback when the server serves DirectoryIndex before mod_rewrite runs.
 */
require __DIR__.'/public/index.php';
