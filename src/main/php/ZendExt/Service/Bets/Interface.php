<?php
/**
 * Bets service interface.
 *
 * @category  ZendExt
 * @package   ZendExt_Service_Bets
 * @copyright 2010 Monits
 * @license   Copyright (C) 2010. All rights reserved.
 * @version   Release: 1.0.0
 * @link      http://www.zendext.com/
 * @since     1.0.0
 */

/**
 * Bets service interface.
 *
 * @category  ZendExt
 * @package   ZendExt_Service_Bets
 * @author    Franco Zeoli <fzeoli@monits.com>
 * @copyright 2010 Monits
 * @license   Copyright 2010. All rights reserved.
 * @version   Release: 1.0.0
 * @link      http://www.zendext.com/
 * @since     1.0.0
 */
interface ZendExt_Service_Bets_Interface
{
    /**
     * Retrieves the match payback ratio.
     *
     * @param string $local   The local team code.
     * @param string $visitor The visitor team code.
     *
     * @return float
     */
    public function getMatchPayback($local, $visitor);

}