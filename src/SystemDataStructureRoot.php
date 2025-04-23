<?php

namespace  Edu\IU\RSB\StructuredDataNodes;

/**
 * a wrapper class for Converter
 */

class SystemDataStructureRoot{

    private BaseNode $baseNodeExample;

    private Converter $converter;

    private array $rootArray = [];

    public function __construct(array $structuredDataNodesArray = [])
    {
        $this->baseNodeExample = new BaseNode('text', 'baseNodeExample');
        $this->converter = new Converter();
        if (!empty($structuredDataNodesArray)){
            $this->convert($structuredDataNodesArray);
        }
    }

    public function convert(array $structuredDataNodesArray):void
    {
        try {
            $this->checkIfValidStructuredDataNodesArray($structuredDataNodesArray);
            $this->rootArray = $this->converter->convert($structuredDataNodesArray);
        }catch (\Exception $e){
            $this->printInCLI($e->getMessage());
        }

    }

    public function getSingleChildNodeByName(string $nodeIdentifier, int $position = 0): NodeInterface | null
    {
        $result = null;
        $cnt = 0;
        foreach ($this->rootArray as $node) {
            if ($node->identifier == trim($nodeIdentifier)){
                if ($position == $cnt){
                    $result = $node;
                    break;
                }
                $cnt += 1;
            }
        }

        return $result;
    }

    public function getAllChildrenNodesByName(string $nodeIdentifier):array
    {
        $result = [];
        foreach ($this->rootArray as $node) {
            if ($node->identifier == trim($nodeIdentifier)){
                $result[] = $node;
            }
        }

        return $result;
    }

    public function getFirstDescendantNodeByPath(string $pathToNode):NodeInterface | null
    {
        $pathToNode = ltrim($pathToNode, DIRECTORY_SEPARATOR);
        // empty string or '/'
        if (empty(trim($pathToNode, DIRECTORY_SEPARATOR))){
            return null;
        }
        $pathArray = explode(DIRECTORY_SEPARATOR, $pathToNode);
        $childNodeIdentifier = $pathArray[0];
        $childNodeToSearch = $this->getSingleChildNodeByName($childNodeIdentifier);
        // a real path instead of just a single identifier
        if (sizeof($pathArray) > 1){

            // only group node has descendants
            if($childNodeToSearch instanceof GroupNode){
                return $childNodeToSearch->getSingleDescendantNodeByPath($pathToNode);
            }else{
                return null;
            }
        }else{// if the path is just one single identifier
            return $childNodeToSearch;
        }
    }

    public function getSingleDescendantNodeByPath(string $pathToNode, int $zeroBasedIndex = 0):NodeInterface | null
    {
        $allDescendantNodesArray = $this->getAllDescendantNodesByPath($pathToNode);

        return $allDescendantNodesArray[$zeroBasedIndex] ?? null;
    }

    public function getAllDescendantNodesByPath(string $pathToNode): array
    {
        $pathToNode = ltrim($pathToNode, DIRECTORY_SEPARATOR);
        $result = [];
        if (empty(trim($pathToNode, DIRECTORY_SEPARATOR))){
            return $result;
        }

        $pathArray = explode(DIRECTORY_SEPARATOR, $pathToNode);
        $childNodeIdentifier = array_shift($pathArray);
        $childrenNodesToSearch = $this->getAllChildrenNodesByName($childNodeIdentifier);
        $pathToUseInChildrenSearch = implode(DIRECTORY_SEPARATOR, $pathArray);
        if (sizeof($pathArray) > 0){
            foreach ($childrenNodesToSearch as $childNode){
                if ($childNode instanceof GroupNode){
                    $result = array_merge($result, $childNode->getAllDescendantNodesByPath($pathToUseInChildrenSearch));
                }
            }
        }else{
            return $childrenNodesToSearch;
        }

        return $result;
    }


    public function getRootArray():array
    {
        return $this->rootArray;
    }

    public function getStructuredDataNode():\stdClass | array
    {
        $sizeOfNodes = sizeof($this->rootArray);

        if ($sizeOfNodes <= 0){
            throw new \RuntimeException('No structured data nodes found');

        }elseif ($sizeOfNodes == 1){
            $result = (object)$this->rootArray[0]->getNodeArray();

        }else{
            $result = [];
            foreach ($this->rootArray as $node){
                $result[] = (object)$node->getNodeArray();
            }
            return $result;
        }

        return $result;

    }

    public function setRootArray(array $convertedStructuredDataNodesArray):void
    {
        if (empty($convertedStructuredDataNodesArray)){
            throw new \RuntimeException('the input $convertedStructuredDataNodesArray should not be empty');
        }
        $this->rootArray = $convertedStructuredDataNodesArray;
    }

    private function printInCLI(string $message):void
    {
        if (PHP_SAPI === 'cli') {
            print_r($message . PHP_EOL);
            die('Task aborted');
        }
    }



    private function checkIfValidStructuredDataNodesArray(array $structuredDataNodesArray):void
    {
        foreach ($structuredDataNodesArray as $stdClass){
            if (!$stdClass instanceof \stdClass){
                throw new \RuntimeException('StructuredDataNode should be a stdClass: ' . print_r($stdClass, true));
            }
            $this->checkIfValidPropertiesExist($stdClass);

            //check every child as well
            if (isset($stdClass->structuredDataNodes->structuredDataNode) && is_array($stdClass->structuredDataNodes->structuredDataNode)){
                $this->checkIfValidStructuredDataNodesArray($stdClass->structuredDataNodes->structuredDataNode);
            }
        }
    }


    private function checkIfValidPropertiesExist(\stdClass $originalNode):void
    {
        $requiredKeys = array_keys($this->baseNodeExample->getNodeArray());

        foreach ($requiredKeys as $key) {
            if (!property_exists($originalNode, $key)){
                throw new \RuntimeException('Property: ' . "'" . $key . "'" . " doesn't exist in this structured data node: " . print_r($originalNode, true));
            }

        }
    }
}