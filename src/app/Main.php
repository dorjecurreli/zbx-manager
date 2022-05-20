<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once 'App.php';
require_once 'Helper.php';
require_once 'Service.php';





class Main
{

    /**
     * @var Service $service
     */
    private Service $service;

    public static $app;



    public function __construct() {

        $this->service = new Service();

        self::$app = App::getInstance();

    }


    public function login() {

        $data = [
            'jsonrpc' => App::ZBX_API_VERSION,
            'method' => 'user.login',
            'params' => [
                'user' => self::$app->app['username'],
                'password' => self::$app->app['password']
            ],
            "id" => '1',
            'auth' => NULL
        ];

       return Helper::postRequest($data);

    }

    public function getHosts($token) {

        $data = [
            'jsonrpc' => App::ZBX_API_VERSION,
            'method' => 'host.get',
            'params' => [
                "outputs" => [
                    "hostid",
                    "host",
                    "interfaceid",
                    "templateid"
                ],
                "selectInterfaces" => [
                    "interdaceid",
                    "ip"
                ]

            ],
            "id" => '2',
            'auth' => $token

        ];

        return Helper::postRequest($data);
    }

    public function getGroups($token) {

        $data = [
            'jsonrpc' => App::ZBX_API_VERSION,
            'method' => 'hostgroup.get',
            'params' => [
                "outputs" => "extends",
                "filter" => [
                    "name" => [
                        "Docker Nodes"
                    ]

                ]
            ],
            "id" => '3',
            'auth' => $token

        ];

        return Helper::postRequest($data);
    }


    public function getTemplates($token) {

        $data = [
            'jsonrpc' => App::ZBX_API_VERSION,
            "method" => "template.get",
            'params' => [
                "outputs" => "extends",
                "filter" => [
                    "host" => [
                        "Linux by Zabbix agent",
                        "Docker"
                    ]

                ]
            ],
            "id" => '4',
            'auth' => $token

        ];

        return Helper::postRequest($data);
    }

    public function getTemplate($token, $name) {

        $data = [
            'jsonrpc' => App::ZBX_API_VERSION,
            "method" => "template.get",
            'params' => [
                "outputs" => "extends",
                "filter" => [
                    "host" => [
                        $name
                    ]

                ]
            ],
            "id" => '4',
            'auth' => $token

        ];

        return Helper::postRequest($data);
    }



    public function createHost($token) {

        $data = [

            'jsonrpc' => App::ZBX_API_VERSION,
            "method" => "host.create",
            'params' => [
                "host" => "pipelines-01.sindria.org",
                "interfaces" => [
                    "type" => 1,
                    "main" => 1,
                    "useip" => 1,
                    "ip" => "172.26.14.17",
                    "dns" => "",
                    "port" => "10050"
                ],
                "groups" => [
                    "groupid" => "17"
                ],
                "templates" => [
                    [
                        "templateid" => "10318"
                    ],
                    [
                        "templateid" => "10001"
                    ]
                ]

            ],
            "id" => '4',
            'auth' => $token

        ];


        return Helper::postRequest($data);

    }

    public function execute() {

        // Login into session
        $session = $this->login();

        // get json string
        $json = $session->getContents();

        // convert json string into associative array
        $data = json_decode($json, true);

        // retrive specific value from created associative array, in this case the session token
        $token = $data['result'];




       // Get Hosts
        $hosts = $this->getHosts($token);

        $json_hosts = $hosts->getContents();

        $result_getHosts = json_decode($json_hosts, true);



        // Get hosts group information based on 'Docker Nodes' name filter.
        $hosts_group = $this->getGroups($token);
        $json_group = $hosts_group->getContents();
        $result_getGroup = json_decode($json_group, true);





        // Get template information based on hosts template type 'Linux by Zabbix agent' and 'Docker'
        $templates = $this->getTemplates($token);
        $json_templates = $templates->getContents();
        $result_templates = json_decode($json_templates, true);

        echo $result_templates['result'][0]['templateid'] . "\n";

//        foreach ($result_templates['result'] as $entry) {
//            echo $entry['templateid'] . "\n";
//
//        }


        //dd($result_templates);




        // Create Host
//        $create_host = $this->createHost($token);
//        $json_create_host = $create_host->getContents();
//        $result_create_host = json_decode($json_create_host,true);
//        dd($result_create_host);


        $this->service->test();


    }

}

$test = new Main();



$test->execute();






















?>