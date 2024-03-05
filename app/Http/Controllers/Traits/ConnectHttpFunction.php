<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
trait ConnectHttpFunction
{
    public function httpGet($url, $params, $token)
    {
        $client = new Client([
                'http_errors' => false,
                'verify' => false,
                'form_params' => $params,
                'headers' => [
                    'Content-Type' => 'application/vnd.api+json',
                    'Accept' => 'application/json',
                    'Authorization-Type' => 'v1',
                    'Authorization' => `Bearer $token `,
                ],
        ]);

        $res = $client->request("GET",$url);
        return $res->getBody()->getContents();
    }
    public function httpPost($url, $params){
        $client = new Client([
            'form_params' => $params,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        $res = $client->request("POST",$url);

        return  $res->getBody()->getContents();
    }
}
