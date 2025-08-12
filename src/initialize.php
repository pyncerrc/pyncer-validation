<?php
use Pyncer\Initializer;
use Pyncer\Validation\Rule\PasswordRule;

Initializer::define('Pyncer\Validation\ALIAS_ALLOW_NUMERIC_CHARACTERS', true);
Initializer::define('Pyncer\Validation\ALIAS_ALLOW_LOWER_CASE_CHARACTERS', true);
Initializer::define('Pyncer\Validation\ALIAS_ALLOW_UPPER_CASE_CHARACTERS', true);
Initializer::define('Pyncer\Validation\ALIAS_ALLOW_UNICODE_CHARACTERS', true);
Initializer::define('Pyncer\Validation\ALIAS_SEPARATOR_CHARACTERS', '-');
Initializer::define('Pyncer\Validation\ALIAS_REPLACEMENT_CHARACTER', '');

Initializer::define('Pyncer\Validation\PASSWORD_CONFIRM_NEW', false);
Initializer::define('Pyncer\Validation\PASSWORD_CONFIRM_OLD', false);
Initializer::define('Pyncer\Validation\PASSWORD_MIN_LENGTH', null);
Initializer::define('Pyncer\Validation\PASSWORD_MAX_LENGTH', null);
Initializer::define('Pyncer\Validation\PASSWORD_REQUIRE_NUMERIC_CHARACTERS', false);
Initializer::define('Pyncer\Validation\PASSWORD_REQUIRE_ALPHA_CHARACTERS', false);
Initializer::define('Pyncer\Validation\PASSWORD_REQUIRE_LOWER_CASE_CHARACTERS', false);
Initializer::define('Pyncer\Validation\PASSWORD_REQUIRE_UPPER_CASE_CHARACTERS', false);
Initializer::define('Pyncer\Validation\PASSWORD_REQUIRE_SPECIAL_CHARACTERS', false);
Initializer::define('Pyncer\Validation\PASSWORD_SPECIAL_CHARACTERS', PasswordRule::SPECIAL_CHARACTERS);
Initializer::define('Pyncer\Validation\PASSWORD_ALLOW_WHITESPACE', false);

Initializer::define('Pyncer\Validation\PHONE_ALLOW_E164', true);
Initializer::define('Pyncer\Validation\PHONE_ALLOW_NANP', false);
Initializer::define('Pyncer\Validation\PHONE_ALLOW_FORMATTING', false);
