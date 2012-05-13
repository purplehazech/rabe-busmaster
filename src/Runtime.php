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
     * @var Array
     */
    var $runEnvs = array('production', 'integration', 'development');
    /**
     * constructor
     *
     * @param Object $dic Dependency Injection Container
     */
    public function __construct($dic)
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
    static function staticRun($dic)
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
        $daemon = $this->dic->get(MODULE_NAME.'Daemon');
        // check for runtime envs
        if (in_array($_SERVER['enviroment'], $this->runEnvs)) {
            // @codeCoverageIgnoreStart
            while ($true) {
                $daemon->run();
                usleep(5000);
            }
            // @codeCoverageIgnoreEnd
        }
    }
}

