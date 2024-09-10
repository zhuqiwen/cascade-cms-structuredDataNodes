<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Asset;

class SymlinkNode extends AssetNode {

    public function __construct(string $identifier, string $assetId = '', string $assetPath = '')
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