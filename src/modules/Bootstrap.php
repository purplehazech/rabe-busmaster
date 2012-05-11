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

require_once __DIR__.'/../Bootstrap.php';

/**
 * System_Daemon for a quick and reliable way to deamonize
 */
require_once 'System/Daemon.php';

/**
 * daemonize when module confirms
 */
if (isset($dic)) {
    $dic->get('dispatcher')->addListener(
        '/daemon/start',
        function (Symfony\Component\EventDispatcher\Event $event) {
            $daemon = $dic->get('daemon');
            $daemon->setOption("appName", strtolower(MODULE_NAME));
            $daemon->setOption("usePEARLogInstance", $dic->get('logger'));
            $daemon->start();
        }
    );
    $module = $dic->get(MODULE_NAME.'Daemon');
    $module->start();
    // @todo reactivate thru getopt (so it stays inactive in most cases except cli)
    $run = false;
    while ($run) {
        $daemon->run();
        // wait for some time
        usleep(500000); // 0.5 secs
    }
}

