<?php

declare(strict_types=1);

namespace Smile\GdprDump\Tests\Unit\Converter\Converters;

use Smile\GdprDump\Converter\Converters\RandomizeEmail;
use Smile\GdprDump\Converter\Parameters\ValidationException;
use Smile\GdprDump\Tests\Unit\Converter\TestCase;

final class RandomizeEmailTest extends TestCase
{
    /**
     * Test the converter.
     */
    public function testConverter(): void
    {
        $converter = $this->createConverter(RandomizeEmail::class, ['domains' => ['example.org']]);

        $value = $converter->convert(null);
        $this->assertSame('', $value);

        $value = $converter->convert('user1@gmail.com');
        $this->assertIsString($value);
        $this->assertStringNotContainsString('user1', $value);
        $this->assertStringNotContainsString('@gmail.com', $value);
        $this->assertStringEndsWith('@example.org', $value);
    }

    /**
     * Test that email usernames are anonymized consistently.
     */
    public function testConsistentUsername(): void
    {
        $converter = $this->createConverter(RandomizeEmail::class, [
            'domains' => ['example.org'],
        ]);

        $value = $converter->convert('user1@example.org');
        $this->assertIsString($value);
        $this->assertSame('24c9e15e52afc47c225b757e7bee1f9d@example.org', $value);
        $this->assertSame($value, $converter->convert('user1@example.org'));
    }

    /**
     * Test that the username is consistently anonymized regardless of RandomizeText parameters.
     */
    public function testUsernameConsistencyWithRandomizeTextParameters(): void
    {
        $converter = $this->createConverter(RandomizeEmail::class, [
            'domains' => ['example.org'],
            'replacements' => 'a',
        ]);

        $value = $converter->convert('user1@example.org');
        $this->assertSame('24c9e15e52afc47c225b757e7bee1f9d@example.org', $value);
    }

    /**
     * Assert that an exception is thrown when the parameter "domains" is empty.
     */
    public function testEmptyDomains(): void
    {
        $this->expectException(ValidationException::class);
        $this->createConverter(RandomizeEmail::class, ['domains' => []]);
    }

    /**
     * Assert that an exception is thrown when the parameter "domains" is not an array.
     */
    public function testInvalidDomains(): void
    {
        $this->expectException(ValidationException::class);
        $this->createConverter(RandomizeEmail::class, ['domains' => 'invalid']);
    }
}
