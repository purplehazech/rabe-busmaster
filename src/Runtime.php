<?php
/**
 * Runtime Kickstarting Env for Busmaster
 *
 * PHP Version 5
 *
 * @category   Busmaster
 * @package    Server
 * @subpackage Bootstrap
 * @author     Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright  2012 Lucas S. Bickel 2011 - Alle Rechte vorbehalten
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @link       http://purplehaze.ch
 */

/**
 * Runtime
 *
 * PHP Version 5
 *
 * @category   Busmaster
 * @package    Server
 * @subpackage Bootstrap
 * @author     Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @link       http://purplehaze.ch
 */
class Runtime
{
    /**
     * @var Runtime
     */
    static $instance = false;

    /**
     * constructor
     *
     * @param Object $dic Dependency Injection Container
     */
    public function __contruct($dic)
    {
        $this->dic = $dic;
    }

    /**
     * static method for kickstarting
     *
     * @param Object $dic Dependency Injection Container
     *
     * @return void
     **/
    public function staticRun($dic)
    {
        if (!self::$instance) {
            self::$instance = new Runtime($dic);
        }
        self::$instance->run();
    }

    /**
     * delegate to backend given by env
     *
     * @return void
     */
    public function run()
    {
        // check for runtime envs
        if (in_array($_ENV['enviroment'], array('production', 'integration', 'development')))
        {
            $this->dic->get(MODULE_NAME.'Daemon');
        }
    }
}

