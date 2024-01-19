<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait CalloutsAwareTrait
{
    /**
     * @var Collection|CalloutInterface[]
     *
     * @ORM\ManyToMany(targetEntity="\Setono\SyliusCalloutPlugin\Model\CalloutInterface")
     *
     * @ORM\JoinTable(
     *     name="setono_sylius_callout__product_callouts",
     *     joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="callout_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")}
     * )
     */
    protected $callouts;

    public function __construct()
    {
        $this->callouts = new ArrayCollection();
    }

    public function getCallouts(): Collection
    {
        return $this->callouts;
    }

    public function getCalloutsByPosition(string $position): Collection
    {
        return $this->callouts->filter(static function (CalloutInterface $callout) use ($position): bool {
            return $callout->getPosition() === $position;
        });
    }

    public function addCallout(CalloutInterface $callout): void
    {
        if (!$this->hasCallout($callout)) {
            $this->callouts->add($callout);
        }
    }

    public function removeCallout(CalloutInterface $callout): void
    {
        if ($this->hasCallout($callout)) {
            $this->callouts->removeElement($callout);
        }
    }

    public function hasCallout(CalloutInterface $callout): bool
    {
        return $this->callouts->contains($callout);
    }

    public function setCallouts(iterable $callouts): void
    {
        $this->callouts->clear();

        foreach ($callouts as $callout) {
            $this->addCallout($callout);
        }
    }
}
