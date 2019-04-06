<?php

declare(strict_types=1);

namespace Wenhsun\Transform;

use PHPUnit\Framework\TestCase;

class MultiColumnTransformerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    private function makeSUT()
    {
        return new MultiColumnTransformer();
    }

    public function testToJson_TextNull_ReturnEmptyJson()
    {
        $sut = $this->makeSUT();
        $r = $sut->toJson(';', null);

        $this->assertEquals('[]', $r);
    }

    public function testToJson_NoSplitText_ReturnJsonWithOneProp()
    {
        $sut = $this->makeSUT();
        $r = $sut->toJson(';', 'abc');

        $this->assertEquals('["abc"]', $r);
    }

    public function testToJson_WithSplitText_ReturnJsonWithPropsSeperateBySplitText()
    {
        $sut = $this->makeSUT();
        $r = $sut->toJson(';', 'abc;def');

        $this->assertEquals('["abc","def"]', $r);
    }

    public function testToJson_WithEmptyText_ReturnEmptyJsonArray()
    {
        $sut = $this->makeSUT();
        $r = $sut->toJson(';', '');

        $this->assertEquals('[]', $r);
    }

    public function testToJson_WithNumberOfZeroText_ReturnJsonWithNumberOfZero()
    {
        $sut = $this->makeSUT();
        $r = $sut->toJson(';', '0');

        $this->assertEquals('["0"]', $r);
    }

    public function testToJson_WithDifferentSplit_WillNotSplit()
    {
        $sut = $this->makeSUT();
        $r = $sut->toJson(';', 'abc,def');

        $this->assertEquals('["abc,def"]', $r);
    }

    public function testToText_OnlyOneArgInJson_ReturnPureTextWithThatArg()
    {
        $sut = $this->makeSUT();
        $r = $sut->toText(';', '["abc"]');

        $this->assertEquals('abc', $r);
    }

    public function testToText_ShouldSplitBySplitText()
    {
        $sut = $this->makeSUT();
        $r = $sut->toText(';', '["abc", "def"]');

        $this->assertEquals('abc;def', $r);
    }

    public function testToText_WithEmptyJsonArray_ReturnEmptyString()
    {
        $sut = $this->makeSUT();
        $r = $sut->toText(';', '[]');

        $this->assertTrue($r === '');
    }

    public function testToText_WithIllegalJson_ReturnEmptyString()
    {
        $sut = $this->makeSUT();
        $r = $sut->toText(';', '["abc", "def",]');

        $this->assertTrue($r === '');
    }
}
