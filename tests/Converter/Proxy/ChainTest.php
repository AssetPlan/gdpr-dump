<?php
declare(strict_types=1);

namespace Smile\GdprDump\Tests\Converter\Proxy;

use Smile\GdprDump\Converter\Proxy\Chain;
use Smile\GdprDump\Tests\Converter\TestConverter;
use Smile\GdprDump\Tests\TestCase;

class ChainTest extends TestCase
{
    /**
     * Test the converter.
     */
    public function testConverter()
    {
        $parameters = [
            'converters' => [
                new TestConverter(),
                new TestConverter(),
            ],
        ];

        $converter = new Chain($parameters);

        $value = $converter->convert('notAnonymized');
        $this->assertSame('test_test_notAnonymized', $value);
    }

    /**
     * Test if an exception is thrown when the converter chain is not set.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testConvertersNotSet()
    {
        new Chain([]);
    }
}
