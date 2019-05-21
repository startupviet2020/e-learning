<?php                  
require_once("app.php");
use Config\AppConfig;
use HV\Core\Rest\HVRestApp;
use HV\Core\Db\Factory;
use HV\Core\Db\Connection;
class HVApi{
    public static function run(){ 
        $config = array(
            "controller_path" => APP_PATH . "controllers/"
        );
        $app = new InvoiceApp($config);
        Factory::getInstance()->setEntityConf(AppConfig::getEntitySettings());
        Connection::getInstance()->setDbConf(AppConfig::getDbSettings());
        $app->authenticate();
        $app->dispatch();
    }
}
