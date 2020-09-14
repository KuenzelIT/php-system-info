<?php namespace App\Api;

/**
 * @property \GuzzleHttp\Client client
 */
class SysDashApi
{
    protected $token;
    protected $client;
    protected $baseUrl;
    protected $systemID;

    public function __construct($baseURL, $token, $systemID)
    {
        $this->baseUrl = $baseURL;
        $this->token = $token;
        $this->systemID = $systemID;

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->baseUrl . 'api/',
        ]);
    }

    public function sendSoftware($software)
    {
        $params = ['softwareList' => $software];

        return $this->post('system/' . $this->systemID . '/softwareList', $params);
    }

    public function sendOS($os)
    {
        return $this->post('system/' . $this->systemID . '/os', $os);
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
