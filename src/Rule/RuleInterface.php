<?php
namespace Pyncer\Validation\Rule;

interface RuleInterface
{
    /**
     * Defends a value against this rule.
     *
     * @param mixed $value The value to defend.
     * @return mixed The cleaned value.
     * @throw InvalidArgumentException When the specified value does not meet
     *      the rule requiredments.
     */
    public function defend(mixed $value): mixed;

    /**
     * Determines if $value meets the requirements of this rule.
     *
     * @param mixed $value The value to test.
     * @return bool True if the value meets the requirements, otherwise false.
     */
    public function isValid(mixed $value): bool;

    /**
     * Cleans a value.
     *
     * If the value is invalid, an empty value will be returned.
     *
     * @param mixed $value The value to clean.
     * @return mixed The cleaned value.
     */
    public function clean(mixed $value): mixed;

    /**
     * Determines if $value meets the requirements of this rule and has been
     * cleaned.
     *
     * @param mixed $value The value to test.
     * @return bool True if the value meets the requirements and is clean,
     *  otherwise false.
     */
    public function isValidAndClean(mixed $value): bool;

    /**
     * Gets the error name of this rule.
     *
     * @return null|string The error name of this rule if any.
     */
    public function getError(): ?string;
}
