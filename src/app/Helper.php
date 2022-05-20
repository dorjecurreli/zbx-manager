<?php
declare(strict_types=1);

use GuzzleHttp\Client;


class Helper
{


    public static function postRequest($data) {

        $app = App::getInstance();

        $client = new Client([

            'base_uri' =>  $app->app['base_url'],

            'headers' => [
                'Content-Type' => 'application/json-rpc'
            ],

            'json' => $data,

            'timeout'  => $app->app['timeout'],
        ]);


        $response = $client->request('POST', 'api_jsonrpc.php');

        return $response->getBody();


    }

}


?>



