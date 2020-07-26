<?php namespace App\Collectors;

class LaravelFramework
{
    protected $name;
    protected $path;

    public function __construct($config)
    {
        $this->name = $config['name'];
        $this->path = $config['path'];
    }

    public function appendData($data)
    {
        if (!isset($data['software']))
            $data['software'] = [];

        $data['software'][$this->name] = $this->collect();

        return $data;
    }

    public function collect()
    {
        $path = $this->path;

        if (!endsWith($path, '/'))
            $path .= '/';

        return [
            'name' => $this->name,
            'version' => exec('php ' . $path . 'artisan -V'),
            'installed' => true
        ];
    }
}
