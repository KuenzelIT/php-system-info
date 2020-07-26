<?php namespace App\Api;

/**
 * @property \GuzzleHttp\Client client
 */
class SysDashApi
{
    protected $token;
    protected $client;
    protected $baseUrl;

    public function __construct($baseURL, $token)
    {
        $this->token = $token;
        $this->baseUrl = $baseURL;

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->baseUrl. 'api/',
        ]);
    }

    public function sendSoftware($software)
    {
        $params = ['softwareList' => $software];

        return $this->post('system/1/softwareList', $params);
    }

    public function sendOS($os)
    {
        return $this->post('system/1/os', $os);
    }

    protected function post($url, $data)
    {
        return $this->client->post($url, [
            'form_params' => $data,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token
            ]
        ]);
    }
}
