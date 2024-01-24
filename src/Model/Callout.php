<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Model;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelInterface as BaseChannelInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;

class Callout implements CalloutInterface
{
    use ToggleableTrait;
    use TimestampableTrait;
    use TranslatableTrait {
        __construct as protected initializeTranslationsCollection;
    }

    protected ?int $id = null;

    protected int $version = 1;

    protected ?string $code = null;

    protected ?string $name = null;

    protected ?DateTimeInterface $startsAt = null;

    protected ?DateTimeInterface $endsAt = null;

    protected int $priority = 0;

    /** @var list<string> */
    protected array $elements = [self::DEFAULT_KEY];

    protected ?string $position = self::DEFAULT_KEY;

    /** @var Collection<array-key, BaseChannelInterface> */
    protected Collection $channels;

    /** @var Collection<array-key, CalloutRuleInterface> */
    protected Collection $rules;

    public function __construct()
    {
        $this->channels = new ArrayCollection();
        $this->rules = new ArrayCollection();
        $this->initializeTranslationsCollection();
    }

    public function __toString(): string
    {
        return (string) $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(?int $version): void
    {
        $this->version = (int) $version;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStartsAt(): ?DateTimeInterface
    {
        return $this->startsAt;
    }

    public function setStartsAt(?DateTimeInterface $startsAt): void
    {
        $this->startsAt = $startsAt;
    }

    public function getEndsAt(): ?DateTimeInterface
    {
        return $this->endsAt;
    }

    public function setEndsAt(?DateTimeInterface $endsAt): void
    {
        $this->endsAt = $endsAt;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function getElements(): array
    {
        return $this->elements;
    }

    public function setElements(array $elements): void
    {
        $this->elements = $elements;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

    public function getText(): ?string
    {
        return $this->getCalloutTranslation()->getText();
    }

    public function setText(string $text): void
    {
        $this->getCalloutTranslation()->setText($text);
    }

    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function addChannel(BaseChannelInterface $channel): void
    {
        if (!$this->hasChannel($channel)) {
            $this->channels->add($channel);
        }
    }

    public function removeChannel(BaseChannelInterface $channel): void
    {
        if ($this->hasChannel($channel)) {
            $this->channels->removeElement($channel);
        }
    }

    public function hasChannel(BaseChannelInterface $channel): bool
    {
        return $this->channels->contains($channel);
    }

    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function hasRules(): bool
    {
        return !$this->rules->isEmpty();
    }

    public function hasRule(CalloutRuleInterface $rule): bool
    {
        return $this->rules->contains($rule);
    }

    public function addRule(CalloutRuleInterface $rule): void
    {
        if (!$this->hasRule($rule)) {
            $rule->setCallout($this);
            $this->rules->add($rule);
        }
    }

    public function removeRule(CalloutRuleInterface $rule): void
    {
        $rule->setCallout(null);
        $this->rules->removeElement($rule);
    }

    protected function getCalloutTranslation(): CalloutTranslationInterface
    {
        /** @var CalloutTranslationInterface $translation */
        $translation = $this->getTranslation();

        return $translation;
    }

    protected function createTranslation(): CalloutTranslation
    {
        return new CalloutTranslation();
    }
}
