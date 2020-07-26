<?php namespace App\Collectors;

class Nextcloud
{

    /**
     * Nextcloud constructor.
     */
    public function __construct($config)
    {
        $this->path = $config['path'];
    }

    public function appendData($data)
    {
        if (!isset($data['software']))
            $data['software'] = [];

        $data['software']['Nextcloud'] = $this->collect();

        return $data;
    }

    public function collect()
    {
        $path = $this->path;

        if (!endsWith($path, '/'))
            $path .= '/';

        $path .= 'version.php';

        if (!file_exists($this->path))
            return $this->notInstalled();

        $content = file_get_contents($path);

        if (!preg_match('/\$OC_VersionString = \'(.*?)\';/', $content, $matches))
            return $this->notInstalled();

        return [
            'name' => 'Nextcloud',
            'version' => $matches[1],
            'installed' => true
        ];
    }

    protected function notInstalled()
    {
        return [
            'name' => 'Nextcloud',
            'version' => null,
            'installed' => false
        ];
    }
}
