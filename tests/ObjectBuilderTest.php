<?php

declare(strict_types=1);

namespace TheBrokenTile\Test;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\ObjectBuilder;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\ObjectBuilderInterface;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

/**
 * @coversDefaultClass \TheBrokenTile\BoardGameGeekApi\ObjectBuilder\ObjectBuilder
 *
 * @internal
 */
final class ObjectBuilderTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @covers ::build
     */
    public function testBuild(): void
    {
        $request = $this->prophesize(RequestInterface::class)->reveal();
        $stringResponse = 'string response';
        $expectedResponse = $this->prophesize(DataTransferObjectInterface::class)->reveal();

        $supportedBuilder = $this->prophesize(ObjectBuilderInterface::class);
        $supportedBuilder
            ->supports($request)
            ->shouldBeCalledOnce()
            ->willReturn(true)
        ;
        $supportedBuilder
            ->build($stringResponse)
            ->willReturn($expectedResponse)
        ;

        $notSupportedBuilder = $this->prophesize(ObjectBuilderInterface::class);
        $notSupportedBuilder
            ->supports($request)
            ->shouldBeCalledOnce()
            ->willReturn(false)
        ;
        $notSupportedBuilder
            ->build($stringResponse)
            ->shouldNotBeCalled()
        ;

        $secondNotSupportedBuilder = $this->prophesize(ObjectBuilderInterface::class);
        $secondNotSupportedBuilder
            ->supports($request)
            ->shouldNotBeCalled()
        ;
        $secondNotSupportedBuilder
            ->build($stringResponse)
            ->shouldNotBeCalled()
        ;

        $builder = new ObjectBuilder([
            $notSupportedBuilder->reveal(),
            $supportedBuilder->reveal(),
            $secondNotSupportedBuilder->reveal(),
        ]);

        $response = $builder->build($request, $stringResponse);

        self::assertSame($expectedResponse, $response);
    }
}
