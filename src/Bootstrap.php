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

$conf = parse_ini_file(__DIR__.'/../etc/busmaster.ini', true);

set_include_path(
    __DIR__.'/:'.
    __DIR__.'/../lib/:'.
    __DIR__.'/../lib/poschp/src/:'.
    get_include_path()
);

/**
 * all components will need to dispatch events
 */
require_once 'sfEventDispatcher/EventDispatcherInterface.php';
require_once 'sfEventDispatcher/EventDispatcher.php';
require_once 'sfEventDispatcher/Event.php';

/**
 * load and setup the dependency injector
 */
require_once 'sfDependencyInjection/Exception/ExceptionInterface.php';
require_once 'sfDependencyInjection/Exception/InvalidArgumentException.php';
require_once 'sfDependencyInjection/Exception/ServiceNotFoundException.php';
require_once 'sfDependencyInjection/Definition.php';
require_once 'sfDependencyInjection/ParameterBag/ParameterBagInterface.php';
require_once 'sfDependencyInjection/ParameterBag/ParameterBag.php';
require_once 'sfDependencyInjection/ContainerInterface.php';
require_once 'sfDependencyInjection/TaggedContainerInterface.php';
require_once 'sfDependencyInjection/IntrospectableContainerInterface.php';
require_once 'sfDependencyInjection/Container.php';
require_once 'sfDependencyInjection/ContainerBuilder.php';
require_once 'sfDependencyInjection/Reference.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * load our generic events
 */
;

/**
 * bootstrap dic (autogenerated by build process)
 */
require_once 'Bootstrap.dic.php';

/**
 * runtime class
 */
require_once 'Runtime.php';

/**
 * run everything
 */
Runtime::staticRun(new BootstrapDic);

// everything below here (autoloading included) needs automating
// @todo add signal handling events 
// event handling belongs in the daemon module
// logging should be loaded by autoload -> register pear autoload
require_once 'Log.php';
