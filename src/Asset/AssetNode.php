<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Asset;

use Edu\IU\RSB\StructuredDataNodes\BaseNode;
use Edu\IU\RSB\StructuredDataNodes\NodeInterface;

abstract class AssetNode extends BaseNode implements NodeInterface{

    public function __construct(string $identifier, string $assetType)
    {
        parent::__construct('asset', $identifier);
        $this->setValueAssetType($assetType);

    }

    public function setValueAssetType(string $assetType):void
    {
        $this->assetType = $assetType;
    }
}