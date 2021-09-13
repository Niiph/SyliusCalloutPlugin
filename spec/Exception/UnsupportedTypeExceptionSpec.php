<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Exception;

use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutPlugin\Exception\UnsupportedTypeException;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;

final class UnsupportedTypeExceptionSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('string', 'bool');
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(UnsupportedTypeException::class);
    }

    public function it_is_an_unexpected_type_and_invalid_argument_exception(): void
    {
        $this->shouldHaveType(UnexpectedTypeException::class);
        $this->shouldHaveType(\InvalidArgumentException::class);
    }
}
