<?php

declare(strict_types=1);

namespace TheBrokenTile\Test;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\ObjectBuilder;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\ObjectBuilderInterface;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

/**
 * @internal
 */
#[CoversClass(ObjectBuilder::class)]
final class ObjectBuilderTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testBuild(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $stringResponse = '::string-response::';
        $expectedResponse = $this->createMock(DataTransferObjectInterface::class);

        $supportedBuilder = $this->createMock(ObjectBuilderInterface::class);
        $supportedBuilder
            ->expects(self::once())
            ->method('supports')
            ->with($request)
            ->willReturn(true)
        ;

        $supportedBuilder
            ->method('build')
            ->with($stringResponse, $request)
            ->willReturn($expectedResponse)
        ;

        $notSupportedBuilder = $this->createMock(ObjectBuilderInterface::class);
        $notSupportedBuilder
            ->expects(self::once())
            ->method('supports')
            ->with($request)
            ->willReturn(false)
        ;

        $notSupportedBuilder
            ->expects(self::never())
            ->method('build')
            ->with($stringResponse, $request)
        ;

        $secondNotSupportedBuilder = $this->createMock(ObjectBuilderInterface::class);
        $secondNotSupportedBuilder
            ->expects(self::never())
            ->method('supports')
            ->with($request)
        ;

        $secondNotSupportedBuilder
            ->expects(self::never())
            ->method('build')
            ->with($stringResponse, $request)
        ;

        $builder = new ObjectBuilder([
            $notSupportedBuilder,
            $supportedBuilder,
            $secondNotSupportedBuilder,
        ]);

        $response = $builder->build($request, $stringResponse);

        self::assertSame($expectedResponse, $response);
    }
}
