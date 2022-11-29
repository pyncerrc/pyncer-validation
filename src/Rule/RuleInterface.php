<?php
namespace Pyncer\Validation\Rule;

interface RuleInterface
{
    public function defend(mixed $value): mixed;
    public function isValid(mixed $value): bool;
    public function clean(mixed $value): mixed;
    public function getError(): ?string;
}
