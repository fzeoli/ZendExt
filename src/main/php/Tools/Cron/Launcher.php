<?php 
/*

   Copyright 2011 Monits
 
   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.

*/
/**
 * Launcher script for offline processes.
 *
 * @category  Tools
 * @package   Tools_Cron
 * @copyright 2010 Monits
 * @license   Copyright (C) 2010. All rights reserved.
 * @version   Release: 1.0.0
 * @link      http://www.zendext.com/
 * @since     1.0.0
 */

error_reporting(E_ALL);
define('CONFIG_FILE', 'src/main/resources/config/process.xml');

require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('ZendExt_');

try {

    $opts = new Zend_Console_Getopt(array(
        'config|c=s' => 'Path to process config file. Optional'
    ));

    $opts->parse();
} catch (Zend_Console_Getopt_Exception $e) {

    die($e->getUsageMessage());
}

$config = $opts->config ? $opts->config : CONFIG_FILE;

$args = $opts->getRemainingArgs();
if ( empty($args) ) {

    echo 'You must specify at least one strategy.'.PHP_EOL;
} else {

    $extra = array();
    $strategy = array();
    foreach ($args as $arg) {

        $split = explode('=', $arg);
        if (count($split) == 1) {

            $strategy[] = $split[0];
        } else {

            $keys = explode('.', $split[0]);
            $lastKey = array_pop($keys);

            $level = &$extra;
            while (($key = array_shift($keys)) !== null) {

                if (!isset($level[$key])) {

                    $level[$key] = array();
                }
                $level = &$level[$key];
            }
            $level[$lastKey] = $split[1];
        }
    }

    ZendExt_Cron_Manager::run($strategy, $config, $extra);
}

echo 'All processes finished executing successfully.'.PHP_EOL;

