<?php namespace App\Collectors;

class Apt
{
    protected $software;

    /**
     * Apt constructor.
     */
    public function __construct($config)
    {
        $this->software = $config['software'];
    }

    public function appendData($data)
    {
        if (!isset($data['software']))
            $data['software'] = [];

        $data['software'] = array_merge($data['software'], $this->getSoftwareList());
        $data['hasPMUpgrades'] = $this->hasAnyUpgrades();

        return $data;
    }

    /**
     * @return array
     */
    protected function getSoftwareList()
    {
        $list = [];

        foreach ($this->software as $name)
            $list[$name] = $this->getSoftware($name);

        return $list;
    }

    protected function getSoftware($software)
    {
        $output = [];

        exec('dpkg -s ' . $software . ' 2>&1', $output);

        foreach ($output as $line)
        {
            if (startsWith($line, 'Version:'))
            {
                return [
                    'name' => $software,
                    'version' => str_replace('Version: ', '', $line),
                    'installed' => true
                ];
            }
        }

        return [
            'name' => $software,
            'version' => null,
            'installed' => false
        ];
    }

    public function getUpgradable()
    {
        exec('apt list --upgradable 2>&1', $output);

        return array_values(array_filter($output, function ($line) {
            if (empty($line))
                return false;

            if (strpos($line, 'WARNING: ') === 0)
                return false;

            if (strpos($line, 'Auflistung...') === 0)
                return false;

            if (strpos($line, 'Listing...') === 0)
                return false;

            return true;
        }));
    }

    public function hasAnyUpgrades()
    {
        $upgradableList = $this->getUpgradable();

        return count($upgradableList) > 0;
    }
}
