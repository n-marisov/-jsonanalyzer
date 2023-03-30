<?php

namespace Maris\JsonAnalyzer\Interfaces;

interface JsonAdapterInterface
{
    public function fromJson( mixed $data , string $namespace ):mixed;

    public function toJson( mixed $data , string $namespace ):mixed;
}