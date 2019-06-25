<?php

declare(strict_types=1);

namespace spec\Setono\SyliusCalloutPlugin\Twig\Extension;

use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Setono\SyliusCalloutPlugin\Model\CalloutInterface;
use Setono\SyliusCalloutPlugin\Model\CalloutsAwareInterface;
use Setono\SyliusCalloutPlugin\Twig\Extension\CalloutExtension;

final class RenderCalloutExtensionSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(CalloutExtension::class);
    }

    function it_is_a_twig_extension(): void
    {
        $this->shouldHaveType(\Twig_Extension::class);
    }

    function it_returns_callouts_functions(): void
    {
        $this->getFunctions()->shouldHaveCount(1);
        $this->getFunctions()[0]->getName()->shouldBeEqualTo('setono_render_callouts');
    }

    function itRendersCallouts(
        CalloutInterface $firstCallout,
        CalloutInterface $secondCallout,
        CalloutsAwareInterface $product
    ): void {
        $firstCallout->getPosition()->willReturn('top_left_corner');
        $firstCallout->getText()->willReturn('<h3>Hello</h3>');
        $secondCallout->getPosition()->willReturn('top_left_corner');
        $secondCallout->getText()->willReturn('<p>World!</p>');

        $product->getCallouts()->willReturn(new ArrayCollection([
            $firstCallout->getWrappedObject(),
            $secondCallout->getWrappedObject(),
        ]));

        $this->renderCallouts($product, 'top_left_corner')->shouldReturn('<h3>Hello</h3><p>World!</p>');
    }
}
