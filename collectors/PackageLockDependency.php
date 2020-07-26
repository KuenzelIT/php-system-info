<?php namespace App\Collectors;

class PackageLockDependency
{
    protected $name;
    protected $path;
    protected $package;

    public function __construct($config)
    {
        $this->name = $config['name'];
        $this->path = $config['path'];
        $this->package = $config['package'];
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

        $json = file_get_contents($path . 'package-lock.json');
        $data = json_decode($json);

        print_r($data);

        return [
            'name' => $this->name,
            'version' => $data->dependencies->{$this->package}->version,
            'installed' => true
        ];
    }
}
