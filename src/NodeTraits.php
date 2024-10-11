<?php

namespace  Edu\IU\RSB\StructuredDataNodes;

trait NodeTraits{

    public function getAssetInfo(\stdClass $assetNode):array
    {
        if (!is_null($assetNode->blockId) && !is_null($assetNode->blockPath)){
            $type = 'block';
            $assetId = $assetNode->blockId;
            $assetPath = $assetNode->blockPath;
        }elseif (!is_null($assetNode->fileId) && !is_null($assetNode->filePath)){
            $type = 'file';
            $assetId = $assetNode->fileId;
            $assetPath = $assetNode->filePath;
        }elseif (!is_null($assetNode->pageId) && !is_null($assetNode->pagePath)){
            $type = 'page';
            $assetId = $assetNode->pageId;
            $assetPath = $assetNode->pagePath;
        }elseif (!is_null($assetNode->symlinkId) && !is_null($assetNode->symlinkPath)){
            $type = 'symlink';
            $assetId = $assetNode->symlinkId;
            $assetPath = $assetNode->symlinkPath;
        }else{
            $type = null;
            $assetId = null;
            $assetPath = null;
        }

        return compact('type', 'assetId', 'assetPath');
    }
}