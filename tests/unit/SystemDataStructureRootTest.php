<?php

namespace unit;


use Edu\IU\RSB\StructuredDataNodes\BaseNode;

class SystemDataStructureRootTest extends BaseTest {

    public function testGetFirstOrSingleDescendantNodeByPathReturnsNullForEmptyPath(): void
    {
        self::$systemDataStructureRoot->setRootArray(self::$mockDataIET);
        $this->assertNull(self::$systemDataStructureRoot->getFirstDescendantNodeByPath(''));
        $this->assertNull(self::$systemDataStructureRoot->getSingleDescendantNodeByPath(''));

        self::$systemDataStructureRoot->setRootArray(self::$mockDataIWF);
        $this->assertNull(self::$systemDataStructureRoot->getFirstDescendantNodeByPath(''));
        $this->assertNull(self::$systemDataStructureRoot->getSingleDescendantNodeByPath(''));
    }

    public function testGetAllDescendantNodesByPathReturnsEmptyArrayForEmptyOrNonExistingPath(): void
    {
        self::$systemDataStructureRoot->setRootArray(self::$mockDataIET);
        $this->assertEmpty(self::$systemDataStructureRoot->getAllDescendantNodesByPath(''));

        self::$systemDataStructureRoot->setRootArray(self::$mockDataIWF);
        $this->assertEmpty(self::$systemDataStructureRoot->getAllDescendantNodesByPath(''));
    }

    public function testGetSingleChildNodeByNameReturnsCorrectNode(): void
    {
        self::$systemDataStructureRoot->setRootArray(self::$mockDataIET);
        $result = self::$systemDataStructureRoot->getSingleChildNodeByName('notes');
        $expectedArray = self::$ietOriginalData[0];
        $this->assertSame($expectedArray, $result->getNodeArray());

        // 2nd section
        $sectionNode = self::$systemDataStructureRoot->getSingleChildNodeByName('section', 1);
        $tableHeaderCellNode = $sectionNode->getSingleDescendantNodeByPath('column/chunk/table-header/header-cell');
        $expectedArray = self::$ietOriginalData[4]['structuredDataNodes']['structuredDataNode'][10]['structuredDataNodes']['structuredDataNode']['structuredDataNodes']['structuredDataNode'][25]['structuredDataNodes']['structuredDataNode'];
        $this->assertSame($expectedArray, $tableHeaderCellNode->getNodeArray());

        //1st section
        $sectionNode = self::$systemDataStructureRoot->getSingleChildNodeByName('section');
        $tableHeaderCellNode = $sectionNode->getSingleDescendantNodeByPath('column/chunk/table-header/header-cell', 1);
        $expectedArray = self::$ietOriginalData[3]['structuredDataNodes']['structuredDataNode'][10]['structuredDataNodes']['structuredDataNode'][1]['structuredDataNodes']['structuredDataNode'][25]['structuredDataNodes']['structuredDataNode'][0];
        $this->assertSame($expectedArray, $tableHeaderCellNode->getNodeArray());

        $tableHeaderCellNode = $sectionNode->getSingleDescendantNodeByPath('column/chunk/table-header/header-cell', 2);
        $expectedArray = self::$ietOriginalData[3]['structuredDataNodes']['structuredDataNode'][10]['structuredDataNodes']['structuredDataNode'][1]['structuredDataNodes']['structuredDataNode'][25]['structuredDataNodes']['structuredDataNode'][1];
        $this->assertSame($expectedArray, $tableHeaderCellNode->getNodeArray());

        $tableHeaderNode = $sectionNode->getSingleDescendantNodeByPath('column/chunk/table-header', 1);
        $expectedArray = self::$ietOriginalData[3]['structuredDataNodes']['structuredDataNode'][10]['structuredDataNodes']['structuredDataNode'][1]['structuredDataNodes']['structuredDataNode'][25];
        $this->assertSame($expectedArray, $tableHeaderNode->getNodeArray());

    }

    public function testGetAllDescendantNodesByPathReturnsCorrectSizeArray():void
    {
        self::$systemDataStructureRoot->setRootArray(self::$mockDataIET);
        $sectionsArray = self::$systemDataStructureRoot->getAllDescendantNodesByPath('section');
        $this->assertSame(2, sizeof($sectionsArray));

        $chunksArray = self::$systemDataStructureRoot->getAllDescendantNodesByPath('section/column/chunk');
        $this->assertSame(5, sizeof($chunksArray));

        $tableHeadersArray = self::$systemDataStructureRoot->getAllDescendantNodesByPath('section/column/chunk/table-header');
        $this->assertSame(5, sizeof($tableHeadersArray));

        $tableHeaderCellsArray = self::$systemDataStructureRoot->getAllDescendantNodesByPath('section/column/chunk/table-header/header-cell');
        $this->assertSame(7, sizeof($tableHeaderCellsArray));

    }
}