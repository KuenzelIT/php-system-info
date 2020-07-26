<?php namespace App\Collectors;

class OS
{
    /**
     * OperatingSystem constructor.
     */
    public function __construct($config)
    {
    }

    public function appendData($data)
    {
        $data['os'] = $this->getOS();

        return $data;
    }

    public function getOS()
    {
        $osRelease = file_get_contents('/etc/os-release');

        preg_match('/NAME="(.*?)"/', $osRelease, $matches);

        $name = $matches[1];

        preg_match('/VERSION_ID="(.*?)"/', $osRelease, $matches);

        $version = $matches[1];

        preg_match('/PRETTY_NAME="(.*?)"/', $osRelease, $matches);

        $prettyName = $matches[1];

        return [
            'name' => $name,
            'version' => $version,
            'fullName' => $prettyName
        ];
    }
}
