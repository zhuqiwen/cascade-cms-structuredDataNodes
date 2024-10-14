<?php

namespace  Edu\IU\RSB\StructuredDataNodes;


use PHPUnit\Exception;
use PHPUnit\TextUI\XmlConfiguration\Group;

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

    public function getSingleChildNodeByName(string $nodeIdentifier): NodeInterface | null
    {
        $result = null;
        foreach ($this->rootArray as $node) {
            if ($node->identifier == trim($nodeIdentifier)){
                $result = $node;
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

    public function getSingleDescendantNodeByPath(string $pathToNode):NodeInterface | null
    {
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

    public function getAllDescendantNodesByPath(string $pathToNode): array
    {
        $result = [];
        if (empty(trim($pathToNode, DIRECTORY_SEPARATOR))){
            return $result;
        }

        $pathArray = explode(DIRECTORY_SEPARATOR, $pathToNode);
        $childNodeIdentifier = $pathArray[0];
        $childrenNodesToSearch = $this->getAllChildrenNodesByName($childNodeIdentifier);

        if (sizeof($pathArray) > 1){
            foreach ($childrenNodesToSearch as $childNode){
                if ($childNode instanceof GroupNode){
                    $result = array_merge($result, $childNode->getAllDescendantNodesByPath($pathToNode));
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