<?php
/**
 * Worker Bootstrap
 *
 * used by workers to get a working env
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

$conf = parse_ini_file('/etc/busmaster/busmaster.ini', true);

set_include_path(
    dirname(__FILE__).'/:'.
    dirname(__FILE__).'/../lib/'.
    get_include_path()
);

/**
 * all components will need to dispatch events
 */
require_once 'sfEventDispatcher/EventDispatcherInterface.php';
require_once 'sfEventDispatcher/EventDispatcher.php';
require_once 'sfEventDispatcher/Event.php';

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;

/**
 * load and setup the dependency injector
 */
require_once 'sfDependencyInjection/ContainerBuilder.php';
require_once 'sfDependencyInjection/Reference.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * bootstrap the dic
 *
 * i'll rewrite this to something more creative as soon
 * as i gots some module standards.
 */
$dc = new ContainerBuilder();
$dc->register('dispatcher', 'EventDispatcher');

// @todo add signal handling events (as soon as i habe automated di container building)
