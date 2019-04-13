<?php
declare(strict_types = 1);

namespace Attogram\Currency;

use function is_array;
use function is_readable;

trait CustomizationTrait
{
    /** @var array */
    protected $config = [];

    /** @var string */
    protected $customDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..'
        . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR;

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
        if (!empty($config) && is_array($config)) {
            $this->config = $config;
        }
        if (empty($this->config['title']) || !is_string($this->config['title'])) {
            $this->config['title'] = 'Attogram Currency Exchange Rates';
        }
        if (empty($this->config['adminIP']) || !is_string($this->config['adminIP'])) {
            $this->config['adminIP'] = null;
        }
        if (empty($this->config['hidden']) || !is_array($this->config['hidden'])) {
            $this->config['hidden'] = [];
        }
    }
}
