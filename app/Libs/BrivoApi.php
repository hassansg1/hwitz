<?php
namespace App\Libs;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;

use Http;
use stdClass;

use App\Models\Brivo;

class BrivoApi {

    public $user;
    public $pass;
    public $key;
    public $auth_url;
    public $token_url;
    public $code;
    public $bearer;
    public $verbose;
    public $client;
    public $jar;

    public function __construct() {
        $this->clientid = config('services.brivo.client-id');
        $this->secret = config('services.brivo.secret');
        $this->key = config('services.brivo.api-key');
        $this->user = config('services.brivo.user');
        $this->pass = config('services.brivo.pass');
        $this->redirect_uri = config('services.brivo.callback');

        $this->auth_url = 'https://auth.brivo.com/oauth/authorize';
        $this->token_url = 'https://auth.brivo.com/oauth/token';
        $this->login_url = 'https://auth.brivo.com/login.do';
        $this->api_url = 'https://api.brivo.com/v1/api/';

        $this->client = new Client();
    }

    public function auth() {

        $this->jar = new \GuzzleHttp\Cookie\CookieJar;
        
        $brivo = Brivo::find(1);


        // If token is current then use it
        if (false && $brivo && $brivo->access_token && strtotime($brivo->updated_at) > time()-55) {

            $arr = [
                'cookies' => $this->jar,
                'headers' => [
                    'api-key' => $this->key,
                    'Authorization' => 'Bearer '.$brivo->access_token,
                ],
                'debug' => $this->verbose,
            ];

            return $arr;
        }
        else {

            $post = [
                'grant_type' => 'password',
                'username' => $this->user,
                'password' => $this->pass,
                ];
        
            if ($this->verbose) echo "$this->clientid\n$this->secret\n";

            $arr = [
                'cookies' => $this->jar,
                'headers' => [
                    'content-type' => 'application/x-www-form-urlencoded',
                    'api-key' => $this->key,
                    'Authorization' => 'Basic '.base64_encode($this->clientid.':'.$this->secret),
                ],
                'body' => http_build_query($post),
                'debug' => $this->verbose,
            ];


            $url = $this->token_url;
            if ($this->verbose) echo "$url\n";

            try {
                $res = $this->client->post($this->token_url, $arr);
            }
            catch (ClientException $e) {
                $res = $e->getResponse();
                echo (string)$res->getBody()."\n";
            }

            if ($this->verbose) {
                echo ('Status: '.$res->getStatusCode())."\n";
                foreach ($res->getHeaders() as $name => $values) {
                    echo "$name: ".implode(',',$values)."\n";
                }
            }

            $token = json_decode((string)$res->getBody());

            if ($this->verbose) print_r($token);
            if (!$token || !isset($token->access_token)) {
                die("No Token\n");
            }
            //
            // Now save tokens
            $brivo = Brivo::find(1);
            if (!$brivo) {
                $brivo = new Brivo;
            }
            $brivo->access_token = $token->access_token;
            $brivo->refresh_token = $token->refresh_token;
            $brivo->save();

            $arr = [
                'cookies' => $this->jar,
                'headers' => [
                    'api-key' => $this->key,
                    'Authorization' => 'Bearer '.$brivo->access_token,
                ],
                'debug' => $this->verbose,
            ];
            if ($this->verbose) print_r($arr);

            // Return array of options with the bearer token in the header,
            return $arr;
        }
    }

    // Used for get operations
    public function getSection($section) {
        $arr = $this->auth();

        if ($section) {
            $url = $this->api_url.$section;
        }

        if ($this->verbose) echo "$url: ".print_r($arr,true)."\n";

        try {
            $res = $this->client->get($url, $arr);
        }
        catch (ClientException $e) {
            $res = $e->getResponse();
            echo $url.': '.$res->getStatusCode().': '.(string)$res->getBody()."\n";
            return '';
        }

        return (string)$res->getBody()."\n";
    }

    // Used for post operations
    public function postSection($section, $post=[]) {
        $arr = $this->auth();

        if ($section) {
            $url = $this->api_url.$section;
        }

        if ($this->verbose) echo "$url: ".print_r($arr,true)."\n";
        if ($post) {
            $arr['headers']['content-type'] = 'application/json';
            $arr['body'] = json_encode($post);
        }

        if ($this->verbose) print_r($arr);

        try {
            $res = $this->client->post($url, $arr);
        }
        catch (ClientException $e) {
            $res = $e->getResponse();
            echo $url.': '.$res->getStatusCode().': '.(string)$res->getBody()."\n";
            return '';
        }

        return (string)$res->getBody()."\n";
    }

    // Use for put operations
    public function putSection($section, $post=[]) {
        $arr = $this->auth();

        if ($section) {
            $url = $this->api_url.$section;
        }

        if ($this->verbose) echo "$url: ".print_r($arr,true)."\n";
        if ($post) {
            $arr['headers']['content-type'] = 'application/json';
            $arr['body'] = json_encode($post);
        }

        if ($this->verbose) print_r($arr);

        try {
            $res = $this->client->put($url, $arr);
        }
        catch (ClientException $e) {
            $res = $e->getResponse();
            echo $url.': '.$res->getStatusCode().': '.(string)$res->getBody()."\n";
            return '';
        }

        return (string)$res->getBody()."\n";
    }

    // Use for delete operations
    public function deleteSection($section) {
        $arr = $this->auth();

        if ($section) {
            $url = $this->api_url.$section;
        }

        if ($this->verbose) echo "$url: ".print_r($arr,true)."\n";

        try {
            $res = $this->client->delete($url, $arr);
        }
        catch (ClientException $e) {
            $res = $e->getResponse();
            echo $url.': '.$res->getStatusCode().': '.(string)$res->getBody()."\n";
            return '';
        }

        return (string)$res->getBody()."\n";
    }

    public function doorAction($id, $action) {
        $uri = 'schedules/change-state/'.$id;


        if ($id) {
            switch ($action) {
            case 'grant':
                $post = [
                    'endTimeMode' => 'UNTIL_TIME',
                    'behavior' => 'UNLOCK',
                    'endTime' => substr(gmdate(DATE_ATOM, time()+6),0,-6).'Z',
                ];
                dde($this->postSection($uri, $post), 'brivo.log');
                break;
            case 'unlock':
                $post = [
                    'endTimeMode' => 'UNTIL_TIME',
                    'behavior' => 'UNLOCK',
                    'endTime' => substr(gmdate(DATE_ATOM, time()+3600*24),0,-6).'Z',
                ];
                dde($this->postSection($uri, $post), 'brivo.log');
                break;
            case 'lock':
            case 'remove':
                dde($this->deleteSection($uri), 'brivo.log');
                break;
            case 'lockdown':
                dde('lockdown', 'brivo.log');
                break;
            case 'removelockdown':
                dde('removelockdown', 'brivo.log');
                break;
            }
        }
    }

    public function doorStatus($id) {
        $uri = 'device-status/'.$id;

        if ($id) {
            $rtn = json_decode($this->getSection($uri));
            $state = $rtn->accessPointState;
            if ($this->verbose) echo "$state\n";
        }
        return $state;
    }

    public function getAccessPoints() {

        $res = $this->getSection('access-points');

        return "$res\n";

    }

    public function getAccounts() {

        $res = $this->getSection('accounts');

        return "$res\n";

    }
}
