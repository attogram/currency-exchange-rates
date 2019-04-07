<?php
declare(strict_types = 1);

namespace Attogram\Currency\Sources;

interface SourceInterface
{
    public function process();
    public function get();
    public function insert();
}
