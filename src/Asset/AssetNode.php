<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Asset;

use Edu\IU\RSB\StructuredDataNodes\BaseNode;

abstract class AssetNode extends BaseNode{

    public function __construct(string $identifier)
    {
        parent::__construct('asset', $identifier);
    }
}