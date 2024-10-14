<?php

namespace  Edu\IU\RSB\StructuredDataNodes;


use PHPUnit\Exception;

/**
 * a wrapper class for Converter
 */

class SystemDataStructureRoot{

    private BaseNode $baseNodeExample;

    private Converter $converter;

    private array $rooArray = [];

    public function __construct()
    {
        $this->baseNodeExample = new BaseNode('text', 'baseNodeExample');
        $this->converter = new Converter();
    }

    public function convert(array $structuredDataNodesArray):void
    {
        try {
            $this->checkIfValidStructuredDataNodesArray($structuredDataNodesArray);
            $this->rooArray = $this->converter->convert($structuredDataNodesArray);
        }catch (\Exception $e){
            $this->printInCLI($e->getMessage());
        }

    }

    public function getRootArray():array
    {
        return $this->rooArray;
    }

    private function printInCLI(string $message):void
    {
        if (PHP_SAPI === 'cli') {
            print_r($message . PHP_EOL);
            die('Task aborted');
        }
    }



    public function checkIfValidStructuredDataNodesArray(array $structuredDataNodesArray):void
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


    public function checkIfValidPropertiesExist(\stdClass $originalNode):void
    {
        $requiredKeys = array_keys($this->baseNodeExample->getNodeArray());

        foreach ($requiredKeys as $key) {
            if (!property_exists($originalNode, $key)){
                throw new \RuntimeException('Property: ' . "'" . $key . "'" . " doesn't exist in this structured data node: " . print_r($originalNode, true));
            }

        }
    }
}