<?php
/**
 * Agent Bootstrap
 *
 * used by agents to get a working env, this contains spawning a process!
 *
 * PHP Version 5
 *
 * @category   Busmaster
 * @package    Server
 * @subpackage CLI
 * @author     Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright  2012 Lucas S. Bickel 2011 - Alle Rechte vorbehalten
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @link       http://osc.purplehaze.ch
 */

require_once dirname(__FILE__).'/../Bootstrap.php';

/**
 * System_Daemon for a quick and reliable way to deamonize
 */
require_once 'System/Daemon.php';

/**
 * daemonize right away
 */
System_Daemon::start();
