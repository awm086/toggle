<?php

// require 'vendor/autoload.php';

// use GuzzleHttp\Client as Guzzle;

// class ToggleHTTPClient extends Guzzle
// {
//     const BASE_URI = 'https://toggl.com';
//     private $api_token;
//     private $workspace_id;
//     private $config;

//     public function __construct($api_token, $workspace_id)
//     {
//         $config = [
//             'base_uri' => self::BASE_URI,
//             'query' => ['workspace_id' => $workspace_id, 'user_agent' => 'api_test'],
//             'auth' => [$api_token, 'api_token']
//         ];
//         $this->config = $config;
//         parent::__construct($config);
//     }

//     public function getDetailedReport($since, $until, $user = NULL)
//     {
//         $resource =  '/reports/api/v2/details';
//         $reportOptions = ['since' => $since, 'until' => $until];
//         if ($user) {
//             $reportOptions['user_ids'] = $user;
//         }
//         $this->config['query'] = array_merge($this->config['query'], $reportOptions);
//         print_r($this->config);
//         return json_decode(
//            (string) parent::get($resource, $this->config)->getBody()
//         );

//     }
// }

//  curl -v -u 4ce66fd47803a241ec4f2c4a510f322a:api_token -X GET "https://toggl.com/reports/api/v2/details\?workspace_id=950575&since=2020-05-15&until=2020-07-14&user_agent\=api_test"
// $options = [
//     'base_uri' => 'https://toggl.com',
//     'auth' => ['4ce66fd47803a241ec4f2c4a510f322a', 'api_token'],
//     'query' => [
//         'user_agent' => 'api_test',
//         'workspace_id' => '950575',
//         'since' => '2020-06-15',
//         'until' => '2020-07-12',
//         'user_ids' => '4431821',
//     ],
// ];
// $client = new GuzzleHttp\Client();
// $response = $client->get('/reports/api/v2/details',  $options);


$myClient = new ToggleHTTPClient('4ce66fd47803a241ec4f2c4a510f322a', '950575');
$report = $myClient->getDetailedReport('2020-06-15', '2020-07-12', '4431821');
print_r($report);

// echo $response->getStatusCode();
// $json = json_decode($response->getBody());
// print_r($json);
