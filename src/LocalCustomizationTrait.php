<?php
declare(strict_types = 1);

namespace Attogram\Currency;

trait LocalCustomizationTrait
{
    private $localDirectory = __DIR__ . '/../local/';

    private function customHeader()
    {
        $this->includeCustom('custom.header.php');
    }

    private function customFooter()
    {
        $this->includeCustom('custom.footer.php');
    }

    /**
     * @param string $fileName
     * @return string
     */
    private function includeCustom(string $fileName)
    {
        if (is_readable($this->localDirectory . $fileName)) {
            include($this->localDirectory . $fileName);
        }
    }
}
