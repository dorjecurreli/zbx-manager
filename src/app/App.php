<?php
declare(strict_types=1);


/**
 * Class App
 *
 * @package zbx-manager
 * @author Dorje Curreli <dor.curreli@gmail.com>
 * @version 0.1.0
 */
class App
{

    /**
     *  Zabbix API Version
     */
    const ZBX_API_VERSION = 2.0;

    /**
     * @var $app
     */
    public $app;

    /**
     * Singleton instance
     *
     * @var null|App $instance
     */
    private static $instance = null;

    /**
     *  Disable object clone
     */
    private function __clone()
    {
        // evita la clonazione dell'oggetto
    }


    /**
     * Singleton instance
     *
     * @return null|App
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new App();
        }
        return static::$instance;
    }


    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->app = $this->config();
    }

    public function config()
    {
        return include __DIR__ . '/../config/app.php';
    }
}





?>



