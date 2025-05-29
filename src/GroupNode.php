<?php

namespace  Edu\IU\RSB\StructuredDataNodes;


class GroupNode extends BaseNode implements NodeInterface {

    private array $textChildrenDict = [];
    private array $assetChildrenDict = [];
    private array $groupChildrenDict = [];



    public function __construct(string $identifier, BaseNode | null $node = null)
    {
        parent::__construct('group', $identifier);
        $this->setValue('structuredDataNodes', ['structuredDataNode' => null]) ;
        if(!is_null($node)){
            $this->addChild($node);
        }

    }

    public function getTextChildrenDict(): array
    {
        return $this->textChildrenDict;
    }

    public function getAssetChildrenDict(): array
    {
        return $this->assetChildrenDict;
    }

    public function getGroupChildrenDict(): array
    {
        return $this->groupChildrenDict;
    }

    public function addChild(NodeInterface $node):void
    {
        // add to dicts
        $this->addToChildrenDict($node);
        $thisNodeArray = $this;
        if (is_null($thisNodeArray->structuredDataNodes['structuredDataNode'])){
            $this->setValue('structuredDataNodes', ['structuredDataNode' => $node]);
            // only 1
        }elseif(!array_is_list((array)$thisNodeArray->structuredDataNodes['structuredDataNode'])){
            $theOneChildNode = $thisNodeArray->structuredDataNodes['structuredDataNode'];
            $tmpArray = [
                'structuredDataNode' => [
                    $theOneChildNode,
                    $node
                ]
            ];
            $this->setValue('structuredDataNodes', $tmpArray);
            // multiple
        }else{
            $tmpArray = $thisNodeArray->structuredDataNodes;
            $tmpArray['structuredDataNode'][] = $node;
            $this->setValue('structuredDataNodes', $tmpArray);
        }
    }

    public function setValueStructuredDataNodes(BaseNode $node):void
    {
        $this->addChild($node);
    }


    /**
     * override the method so that it recursively convert each child into array
     * @return array
     */
    public function getNodeArray(): array
    {
        $result = get_object_vars(...)->__invoke($this);

        if (isset($result['structuredDataNodes']['structuredDataNode'])){
            $childrenArray = [];
            if (!is_array($result['structuredDataNodes']['structuredDataNode'])){
                $result['structuredDataNodes']['structuredDataNode'] = [$result['structuredDataNodes']['structuredDataNode']];
            }

            foreach ($result['structuredDataNodes']['structuredDataNode'] as $childNode) {
                $childrenArray[] = $childNode->getNodeArray();
            }

            $result['structuredDataNodes']['structuredDataNode'] = $childrenArray;

        }

        return $result;
    }


    public function getFirstDescendantNodeByPath(string $pathToNodeFromThisGroupNode): BaseNode | null
    {
        $result = null;
        if (empty(trim($pathToNodeFromThisGroupNode))){
            return null;
        }
        //normalize path to be
        $pathToNodeFromThisGroupNode = $this->normalizePath($pathToNodeFromThisGroupNode);
        //because the key/path in dict to child looks like $this->identifier . DIRECTORY_SEPARATOR . $child->identifier
        $pathToUseInCurrentDict = $this->constructPath($pathToNodeFromThisGroupNode);
        //try textChildrenDict first
        if (array_key_exists($pathToUseInCurrentDict, $this->textChildrenDict)){
            return $this->textChildrenDict[$pathToUseInCurrentDict][0];
            //then try assetChildrenDict
        }elseif (array_key_exists($pathToUseInCurrentDict, $this->assetChildrenDict)){
            return $this->assetChildrenDict[$pathToUseInCurrentDict][0];
            // then try groupChildrenDict
        }elseif (array_key_exists($pathToUseInCurrentDict, $this->groupChildrenDict)){
            return $this->groupChildrenDict[$pathToUseInCurrentDict][0];
        }else{ //if not found, then try going through child Group nodes
            $pathToUseInChildGroupNodes = $this->getPathToUseInChildGroupNode($pathToNodeFromThisGroupNode);
            $dictKey = $this->getDictKey($pathToNodeFromThisGroupNode);

            foreach ($this->groupChildrenDict as $key => $childGroupNodeArray){
                if ($key == $dictKey){
                    foreach ($childGroupNodeArray as $childGroupNode){
                        $targetNode = $childGroupNode->getFirstDescendantNodeByPath($pathToUseInChildGroupNodes);
                        if (!is_null($targetNode)){
                            // only find the first that matches
                            return $targetNode;
                        }
                    }

                }

            }
        }

        return null;
    }

    public function getSingleDescendantNodeByPath(string $pathToNodeFromThisGroupNode, int $zeroBasedIndex = 0): BaseNode | null
    {
        $allDescendantNodesArray = $this->getAllDescendantNodesByPath($pathToNodeFromThisGroupNode);

        return $allDescendantNodesArray[$zeroBasedIndex] ?? null;
    }
    public function getAllDescendantNodesByPath(string $pathToNodeFromThisGroupNode):  array
    {
        $result = [];
        if (empty(trim($pathToNodeFromThisGroupNode))){
            return $result;
        }
        //normalize path to be
        $pathToNodeFromThisGroupNode = $this->normalizePath($pathToNodeFromThisGroupNode);
        //because the key/path in dict to child looks like $this->identifier . DIRECTORY_SEPARATOR . $child->identifier
        $pathToUseInCurrentDict = $this->constructPath($pathToNodeFromThisGroupNode);

        //try textChildrenDict first
        if (array_key_exists($pathToUseInCurrentDict, $this->textChildrenDict)){
            return $this->textChildrenDict[$pathToUseInCurrentDict];
            //then try assetChildrenDict
        }elseif (array_key_exists($pathToUseInCurrentDict, $this->assetChildrenDict)){
            return $this->assetChildrenDict[$pathToUseInCurrentDict];
            // then try groupChildrenDict
        }elseif (array_key_exists($pathToUseInCurrentDict, $this->groupChildrenDict)){
            return $this->groupChildrenDict[$pathToUseInCurrentDict];
            //if not found, then try going through child Group nodes
        }else{
            $pathToUseInChildGroupNodes = $this->getPathToUseInChildGroupNode($pathToNodeFromThisGroupNode);
            $dictKey = $this->getDictKey($pathToNodeFromThisGroupNode);
            foreach ($this->groupChildrenDict as $key => $childGroupNodeArray){
                if ($key == $dictKey){
                    foreach ($childGroupNodeArray as $childGroupNode){
                        $targetNodesArray = $childGroupNode->getAllDescendantNodesByPath($pathToUseInChildGroupNodes);
                        $result = array_merge($result, $targetNodesArray);
                    }
                }

            }
        }

        return $result;
    }


    public function getAllChildrenNodesArray():array
    {
        $result = $this->getNodeArray()['structuredDataNodes']['structuredDataNode'];
        if (is_null($result)){
            $result = [];
        }
        return $result;
    }

    public function getAllChildrenNodes():array
    {
        return $this->structuredDataNodes['structuredDataNode'];
    }


    private function normalizePath(string $originalPath): string
    {
        return trim($originalPath, DIRECTORY_SEPARATOR);
    }

    private function getPathToUseInChildGroupNode(string $pathToNodeFromThisGroupNode): string
    {
        $pathUsedInChildGroupNodes = explode(DIRECTORY_SEPARATOR, $pathToNodeFromThisGroupNode);
        //rm current node's identifier
        array_shift($pathUsedInChildGroupNodes);

        return implode(DIRECTORY_SEPARATOR, $pathUsedInChildGroupNodes);
    }

    private function addToChildrenDict(NodeInterface $node):void
    {
        match ($node->type){
            'text' => $this->addToTextChildrenDict($node),
            'asset' => $this->addToAssetChildrenDict($node),
            'group' => $this->addToGroupChildrenDict($node),
        };

    }

    private function addToTextChildrenDict(NodeInterface $node):void
    {
        $path = $this->constructPath($node->identifier);
        //node identifier is not unique
        if (array_key_exists($path, $this->textChildrenDict)){
            $this->textChildrenDict[$path][] = $node;
        }else{
            $this->textChildrenDict[$path] = [$node];
        }
    }

    private function addToAssetChildrenDict(NodeInterface $node):void
    {
        $path = $this->constructPath($node->identifier);
        if (array_key_exists($path, $this->assetChildrenDict)){
            $this->assetChildrenDict[$path][] = $node;
        }else{
            $this->assetChildrenDict[$path] = [$node];
        }
    }

    private function addToGroupChildrenDict(NodeInterface $node):void
    {
        $path = $this->constructPath($node->identifier);
        if (array_key_exists($path, $this->groupChildrenDict)){
            $this->groupChildrenDict[$path][] = $node;
        }else{
            $this->groupChildrenDict[$path] = [$node];
        }
    }

    /**
     * use child node's identifier for now until better idea shows up
     * @param NodeInterface $node
     * @return string
     */
    public function constructPath(string $nodeIdentifier):string
    {
        return $this->identifier . DIRECTORY_SEPARATOR . $nodeIdentifier;
    }

    public function getDictKey(string $path):string
    {
        $pathArray = explode(DIRECTORY_SEPARATOR, $path);
        $first = array_shift($pathArray);

        return $this->identifier . DIRECTORY_SEPARATOR . $first;
    }



}