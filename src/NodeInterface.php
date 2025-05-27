<?php

namespace  Edu\IU\RSB\StructuredDataNodes;

interface NodeInterface{
    public function getNodeArray():array;

    public function getPathWithPosition():string;
    public function getPathNoPosition():string;
}