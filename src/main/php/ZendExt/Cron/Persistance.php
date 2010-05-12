<?php
/**
 * Data persistance utility for cron tasks.
 *
 * @category  ZendExt
 * @package   ZendExt_Cron
 * @copyright 2010 Monits
 * @license   Copyright (C) 2010. All rights reserved.
 * @version   Release: 1.0.0
 * @link      http://www.zendext.com/
 * @since     1.0.0
 */

/**
 * Data persistance utility for cron tasks.
 *
 * @category  ZendExt
 * @package   ZendExt_Cron
 * @author    jpcivile <jpcivile@monits.com>
 * @copyright 2010 Monits
 * @license   Copyright 2010. All rights reserved.
 * @version   Release: 1.0.0
 * @link      http://www.zendext.com/
 * @since     1.0.0
 */
final class ZendExt_Cron_Persistance
{

    private static $_dataDir = 'data/';

    /**
     * The name of the process running.
     *
     * @var string
     */
    private static $_process = null;

    /**
     * Persist data so a process can re use it later on.
     *
     * @param string $name An identifier for the data.
     * @param object $data The data object to persist.
     *
     * @return void
     */
    public static function persist($name, $data)
    {

        $filePath = self::$_dataDir.self::$_process.'/';
        $fileName = $name.'.dat';

        if ( !is_dir($filePath) ) {

            mkdir($filePath, 0744, true);
        }

        file_put_contents($filePath.$fileName, serialize($data));
    }

    /**
     * Retrieve persisted data.
     *
     * @param string $name The data identifier.
     *
     * @return object The persisted data object
     *
     * @throws ZendExt_Exception
     */
    public static function retrieve($name)
    {

        $fileName = self::$_dataDir.self::$_process.'/'.$name.'.dat';

        if ( !self::isPersisted($name) ) {

            throw new ZendExt_Exception(
                'Data persistance file '.$fileName.' not found.'
            );
        }

        return unserialize(file_get_contents($fileName));
    }

    /**
     * Check whether data is persisted.
     *
     * @param string $name The data identifier.
     *
     * @return boolean
     */
    public static function isPersisted($name)
    {

        return file_exists(self::$_dataDir.self::$_process.'/'.$name.'.dat');
    }

    /**
     * Set the current process. Can only be called once.
     *
     * @param string $process The process name.
     *
     * @return void
     */
    public static function setCurrentProcess($process)
    {

        if ( self::$_process === null ) {

            self::$_process = $process;
        }
    }

    /**
     * Set the data directory.
     *
     * @param string $dir The directory path.
     *
     * @return void
     */
    public static function setDataDirectory($dir)
    {
        self::$_dataDir = $dir;
    }
}
