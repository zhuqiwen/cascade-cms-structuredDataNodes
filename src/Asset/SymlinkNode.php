<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Asset;

use Edu\IU\RSB\StructuredDataNodes\NodeInterface;

class SymlinkNode extends AssetNode implements NodeInterface {

    public function __construct(string $identifier, string | null $assetId = null, string | null $assetPath = null)
    {

        parent::__construct($identifier, 'symlink');

        $this->symlinkId = $assetId;
        $this->symlinkPath = $assetPath;
    }

    public function setValueSymlinkPath(string $symlinkPath):void
    {
        $this->symlinkPath = $symlinkPath;
    }

    public function setValueSymlinkId(string $symlinkId):void
    {
        $this->symlinkId = $symlinkId;
    }
}