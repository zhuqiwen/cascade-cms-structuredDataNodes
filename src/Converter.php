<?php

namespace  Edu\IU\RSB\StructuredDataNodes;

use Edu\IU\RSB\StructuredDataNodes\Asset\AssetNode;
use Edu\IU\RSB\StructuredDataNodes\Asset\BlockNode;
use Edu\IU\RSB\StructuredDataNodes\Asset\FileNode;
use Edu\IU\RSB\StructuredDataNodes\Asset\LinkableNode;
use Edu\IU\RSB\StructuredDataNodes\Asset\PageNode;
use Edu\IU\RSB\StructuredDataNodes\Asset\SymlinkNode;


/**
 * convert structured data nodes from page, or block into data node objects
 */
class Converter{


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

    public function convert(array $originalNodesArray): array
    {
        $result = [];
        foreach ($originalNodesArray as $node){
            $result[] = $this->convertNode($node);
        }

        return $result;
    }

    public function convertNode(\stdClass $node): BaseNode
    {
        return match ($node->type){
            'asset' => $this->processAssetNode($node),
            'group' => $this->processGroupNode($node),
            'text' => $this->processTextNode($node),
        };

    }

    public function processAssetNode(\stdClass $assetNode): AssetNode
    {
        $assetInfo = $this->getAssetInfo($assetNode);
        //get assetid, asset path and asset type
        extract($assetInfo);
        return match ($assetNode->assetType){
            'page,file,symlink' => new LinkableNode($assetNode->identifier, $assetId, $assetPath, $type),
            'file' => new FileNode($assetNode->identifier, $assetId, $assetPath),
            'block' => new BlockNode($assetNode->identifier, $assetId, $assetPath),
            'page' => new PageNode($assetNode->identifier, $assetId, $assetPath),
            'symlink' => new SymlinkNode($assetNode->identifier, $assetId, $assetPath)
        };
    }

    public function processGroupNode(\stdClass $groupNode): GroupNode
    {
        $dataNode = new GroupNode($groupNode->identifier);

        $structuredDataNode = $groupNode->structuredDataNodes->structuredDataNode;
        $structuredDataNodeArray = $structuredDataNode;
        // in original structuredDataNode, if there is only one child, then it's a stdclass instead of array
        if (!is_array($structuredDataNode)){
            $structuredDataNodeArray = [$structuredDataNode];
        }

        foreach ($structuredDataNodeArray as $childNode) {
            $dataNode->addChild($this->convertNode($childNode));
        }

        return $dataNode;
    }

    public function processTextNode(\stdClass $textNode): BaseNode
    {
        $dataNode = new BaseNode('text', $textNode->identifier);
        $dataNode->setValue('text', $textNode->text);

        return $dataNode;
    }
}