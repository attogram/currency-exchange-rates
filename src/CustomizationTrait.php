<?php
declare(strict_types = 1);

namespace Attogram\Currency;

trait CustomizationTrait
{
    /** @var array */
    protected $config = [];

    /** @var string */
    protected $customDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..'
                                         . DIRECTORY_SEPARATOR . 'custom'
                                         . DIRECTORY_SEPARATOR;
    /**
     * @param string $fileName
     */
    protected function includeCustom(string $fileName)
    {
        if (is_readable($this->customDirectory . $fileName)) {
            /** @noinspection PhpIncludeInspection */
            include($this->customDirectory . $fileName);
        }
    }

    protected function loadConfig()
    {
        global $config;
        $this->includeCustom('config.php');
        if (isset($config) && $config && is_array($config)) {
            $this->config = $config;
        }
        if (empty($this->config['title'])) {
            $this->config['title'] = 'Exchange Rates';
        }
        if (empty($this->config['adminIP'])) {
            $this->config['adminIP'] = null;
        }
    }
}
