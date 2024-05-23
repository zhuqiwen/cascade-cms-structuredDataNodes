<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Asset;

class SymlinkNode extends AssetNode {

    public function __construct(string $identifier, string $assetId, string $assetPath)
    {

        parent::__construct($identifier);

        $this->nodeArray['symlinkId'] = $assetId;
        $this->nodeArray['symlinkPath'] = $assetPath;
        $this->nodeArray['assetType'] = 'symlink';
    }}