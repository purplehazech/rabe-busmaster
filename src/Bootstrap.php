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

$conf = parse_ini_file('/etc/bustmaster/busmaster.ini', true);

set_include_path(
	dirname(__FILE__).'/:'.dirname(__FILE__).'/../lib/',
	get_include_path()
);

