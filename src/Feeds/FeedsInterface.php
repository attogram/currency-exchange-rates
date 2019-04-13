<?php
declare(strict_types = 1);

namespace Attogram\Currency\Feeds;

interface FeedsInterface
{
    public function get();
    public function transform();
    public function process();
    public function insert();
}
