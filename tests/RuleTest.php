<?php
namespace Pyncer\Tests\Validation;

use DateTime;
use PHPUnit\Framework\TestCase;
use Pyncer\Data\Mapper\MapperInterface;
use Pyncer\Data\Model\ModelInterface;
use StdClass;

class RuleTest extends TestCase
{
    public function testAliasRule(): void
    {
        $rule = new \Pyncer\Validation\Rule\AliasRule(
            allowNumericCharacters: true,
            allowLowerCaseCharacters: true,
            allowUpperCaseCharacters: true,
            allowUnicodeCharacters: true,
            separatorCharacters: '-',
            replacementCharacter: '',
        );

        $this->assertTrue($rule->isValid('AZaz09-草'));
        $this->assertFalse($rule->isValid('$^'));

        $this->assertEquals($rule->clean('AZaz09-草'), 'AZaz09-草');
        $this->assertEquals($rule->clean('--A--Z--'), 'A-Z');

        $rule = new \Pyncer\Validation\Rule\AliasRule(
            allowNumericCharacters: false,
            allowLowerCaseCharacters: true,
            allowUpperCaseCharacters: true,
            allowUnicodeCharacters: true,
            separatorCharacters: '-',
            replacementCharacter: '',
        );

        $this->assertTrue($rule->isValid('AZaz-草'));
        $this->assertTrue($rule->isValid('AZaz09-草'));
        $this->assertFalse($rule->isValid('0-9'));
        $this->assertFalse($rule->isValid('$^'));

        $this->assertFalse($rule->isValidAndClean('AZaz09-草'));

        $this->assertEquals($rule->clean('AZaz09-草'), 'AZaz-草');
        $this->assertEquals($rule->clean('--0--9--'), '');

        $rule = new \Pyncer\Validation\Rule\AliasRule(
            allowNumericCharacters: false,
            allowLowerCaseCharacters: true,
            allowUpperCaseCharacters: true,
            allowUnicodeCharacters: false,
            separatorCharacters: '-',
            replacementCharacter: '_',
        );

        $this->assertTrue($rule->isValid('AZaz'));
        $this->assertTrue($rule->isValid('AZaz-'));
        $this->assertTrue($rule->isValid('AZaz09-草'));
        $this->assertFalse($rule->isValid('09-草'));
        $this->assertFalse($rule->isValid('$^'));

        $this->assertFalse($rule->isValidAndClean('AZaz-09-草'));
        $this->assertFalse($rule->isValidAndClean('AZaz-草'));
        $this->assertFalse($rule->isValidAndClean('AZaz-09'));

        $this->assertEquals($rule->clean('AZaz09-草'), 'AZaz__-_');
        $this->assertEquals($rule->clean('--A Z--'), 'A-Z');
        $this->assertEquals($rule->clean('--0--9--'), '');
        $this->assertEquals($rule->clean('$^'), '');

        $rule = new \Pyncer\Validation\Rule\AliasRule(
            allowNumericCharacters: false,
            allowLowerCaseCharacters: false,
            allowUpperCaseCharacters: true,
            allowUnicodeCharacters: false,
            separatorCharacters: '-_',
            replacementCharacter: '*',
        );

        $this->assertTrue($rule->isValid('AZ'));
        $this->assertTrue($rule->isValid('az'));
        $this->assertTrue($rule->isValid('AZaz09-草'));
        $this->assertFalse($rule->isValid('$^'));

        $this->assertFalse($rule->isValidAndClean('AZaz-09-草'));
        $this->assertFalse($rule->isValidAndClean('AZaz-草'));
        $this->assertFalse($rule->isValidAndClean('AZaz-09'));

        $this->assertEquals($rule->clean('AZ-az_09-草'), 'AZ-AZ_**-*');
        $this->assertEquals($rule->clean('--A Z--'), 'A-Z');
        $this->assertEquals($rule->clean('--0--9--'), '');

        $rule = new \Pyncer\Validation\Rule\AliasRule(
            allowNumericCharacters: false,
            allowLowerCaseCharacters: true,
            allowUpperCaseCharacters: false,
            allowUnicodeCharacters: false,
            separatorCharacters: '-_',
            replacementCharacter: '-',
        );

        $this->assertTrue($rule->isValid('az'));
        $this->assertTrue($rule->isValid('AZaz09-草'));
        $this->assertFalse($rule->isValid('$^'));

        $this->assertFalse($rule->isValidAndClean('AZaz-09-草'));
        $this->assertFalse($rule->isValidAndClean('AZaz-草'));
        $this->assertFalse($rule->isValidAndClean('AZaz-09'));

        $this->assertEquals($rule->clean('AZ-az_09-草'), 'az-az');
        $this->assertEquals($rule->clean('--A Z--'), 'a-z');
        $this->assertEquals($rule->clean('--0--9--'), '');

        $rule = new \Pyncer\Validation\Rule\AliasRule(
            allowNumericCharacters: false,
            allowLowerCaseCharacters: true,
            allowUpperCaseCharacters: false,
            allowUnicodeCharacters: true,
            separatorCharacters: '-',
            replacementCharacter: '-',
        );

        $this->assertTrue($rule->isValid('az草'));
        $this->assertTrue($rule->isValid('AZaz09-草'));
        $this->assertFalse($rule->isValid('$^'));

        $this->assertFalse($rule->isValidAndClean('AZaz-09-草'));
        $this->assertFalse($rule->isValidAndClean('AZaz-草'));
        $this->assertFalse($rule->isValidAndClean('AZaz-09'));

        $this->assertEquals($rule->clean('AZ-az_09-草'), 'az-az-草');

        $rule = new \Pyncer\Validation\Rule\AliasRule(
            allowNumericCharacters: false,
            allowLowerCaseCharacters: false,
            allowUpperCaseCharacters: true,
            allowUnicodeCharacters: true,
            separatorCharacters: '-',
            replacementCharacter: '-',
        );

        $this->assertTrue($rule->isValid('AZ-草'));
        $this->assertTrue($rule->isValid('AZaz09-草'));
        $this->assertFalse($rule->isValid('$^'));

        $this->assertFalse($rule->isValidAndClean('AZaz-09-草'));
        $this->assertFalse($rule->isValidAndClean('AZaz-草'));
        $this->assertFalse($rule->isValidAndClean('AZaz-09'));

        $this->assertEquals($rule->clean('AZ-az_09-草'), 'AZ-AZ-草');

        $rule = new \Pyncer\Validation\Rule\AliasRule(
            allowNumericCharacters: false,
            allowLowerCaseCharacters: false,
            allowUpperCaseCharacters: false,
            allowUnicodeCharacters: true,
            separatorCharacters: '-',
            replacementCharacter: '-',
        );

        $this->assertTrue($rule->isValid('AZ-草'));
        $this->assertTrue($rule->isValid('AZ'));
        $this->assertTrue($rule->isValid('az'));

        $this->assertEquals($rule->clean('AZ-草'), 'AZ-草');
        $this->assertEquals($rule->clean('AZ'), 'AZ');
        $this->assertEquals($rule->clean('az'), 'az');
    }
    public function testBase64IdRule(): void
    {
        $rule = new \Pyncer\Validation\Rule\Base64IdRule(
            allowNull: false,
            allowEmpty: false,
        );

        $this->assertTrue($rule->isValid('09aZ-_'));
        $this->assertTrue($rule->isValid(' 09aZ-_ '));
        $this->assertFalse($rule->isValid('09aZ='));
        $this->assertFalse($rule->isValid(null));
        $this->assertEquals($rule->clean(null), '');
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(''));
        $this->assertEquals($rule->clean(''), '');
        $this->assertEquals($rule->clean(' '), '');

        $rule = new \Pyncer\Validation\Rule\Base64IdRule(
            allowNull: true,
            allowEmpty: false,
        );

        $this->assertTrue($rule->isValid('09aZ-_'));
        $this->assertTrue($rule->isValid(' 09aZ-_ '));
        $this->assertTrue($rule->isValid(null));
        $this->assertEquals($rule->clean(null), null);
        $this->assertFalse($rule->isValid([]));
        $this->assertTrue($rule->isValid(''));
        $this->assertEquals($rule->clean(''), null);
        $this->assertEquals($rule->clean(' '), null);

        $rule = new \Pyncer\Validation\Rule\Base64IdRule(
            allowNull: false,
            allowEmpty: true,
        );

        $this->assertTrue($rule->isValid('09aZ-_'));
        $this->assertTrue($rule->isValid(' 09aZ-_ '));
        $this->assertTrue($rule->isValid(null));
        $this->assertEquals($rule->clean(null), '');
        $this->assertFalse($rule->isValid([]));
        $this->assertTrue($rule->isValid(''));
        $this->assertEquals($rule->clean(''), '');
        $this->assertEquals($rule->clean(' '), '');

        $rule = new \Pyncer\Validation\Rule\Base64IdRule(
            allowNull: true,
            allowEmpty: true,
        );

        $this->assertTrue($rule->isValid('09aZ-_'));
        $this->assertTrue($rule->isValid(' 09aZ-_ '));
        $this->assertTrue($rule->isValid(null));
        $this->assertEquals($rule->clean(null), null);
        $this->assertFalse($rule->isValid([]));
        $this->assertTrue($rule->isValid(''));
        $this->assertEquals($rule->clean(''), '');
        $this->assertEquals($rule->clean(' '), '');
    }

    public function testBoolRule(): void
    {
        $rule = new \Pyncer\Validation\Rule\BoolRule(
            allowNull: false,
        );

        $this->assertTrue($rule->isValid(true));
        $this->assertTrue($rule->isValid(false));
        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertTrue($rule->isValid([]));
        $this->assertTrue($rule->isValid(['a' => 'b']));
        $this->assertTrue($rule->isValid(new StdClass()));
        $this->assertTrue($rule->isValid((object)['a' => 'b']));
        $this->assertEquals($rule->clean(true), true);
        $this->assertEquals($rule->clean(false), false);
        $this->assertEquals($rule->clean(null), false);
        $this->assertEquals($rule->clean(''), false);
        $this->assertEquals($rule->clean([]), false);
        $this->assertEquals($rule->clean(['a' => 'b']), true);
        $this->assertEquals($rule->clean(new StdClass()), false);
        $this->assertEquals($rule->clean((object)['a' => 'b']), true);
        $this->assertEquals($rule->clean(false), false);
        $this->assertEquals($rule->clean(0), false);
        $this->assertEquals($rule->clean(1), true);
        $this->assertEquals($rule->clean('true'), true);
        $this->assertEquals($rule->clean(' true '), true);
        $this->assertEquals($rule->clean('false'), false);
        $this->assertEquals($rule->clean(' false '), false);
        $this->assertEquals($rule->clean(' '), false);

        $rule = new \Pyncer\Validation\Rule\BoolRule(
            allowNull: true,
        );

        $this->assertTrue($rule->isValid(true));
        $this->assertTrue($rule->isValid(false));
        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertTrue($rule->isValid([]));
        $this->assertTrue($rule->isValid(['a' => 'b']));
        $this->assertTrue($rule->isValid(new StdClass()));
        $this->assertTrue($rule->isValid((object)['a' => 'b']));
        $this->assertEquals($rule->clean(true), true);
        $this->assertEquals($rule->clean(false), false);
        $this->assertEquals($rule->clean(null), null);
        $this->assertEquals($rule->clean(''), false);
        $this->assertEquals($rule->clean([]), false);
        $this->assertEquals($rule->clean(['a' => 'b']), true);
        $this->assertEquals($rule->clean(new StdClass()), false);
        $this->assertEquals($rule->clean((object)['a' => 'b']), true);
        $this->assertEquals($rule->clean(false), false);
        $this->assertEquals($rule->clean(0), false);
        $this->assertEquals($rule->clean(1), true);
        $this->assertEquals($rule->clean('true'), true);
        $this->assertEquals($rule->clean(' true '), true);
        $this->assertEquals($rule->clean('false'), false);
        $this->assertEquals($rule->clean(' false '), false);
        $this->assertEquals($rule->clean(' '), false);
    }

    public function testDateRule(): void
    {
        $rule = new \Pyncer\Validation\Rule\DateRule(
            allowNull: false,
            allowEmpty: false,
            empty: '0000-00-00',
        );

        $this->assertFalse($rule->isValid(null));
        $this->assertFalse($rule->isValid(''));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
        $this->assertFalse($rule->isValid('0000-00-00'));
        $this->assertTrue($rule->isValid('2023-01-06'));
        $this->assertFalse($rule->isValid('2023-01-32'));
        $this->assertFalse($rule->isValid('2023-13-06'));
        $this->assertTrue($rule->isValid(' 2023-01-06 '));
        $this->assertTrue($rule->isValid('2012-02-29'));
        $this->assertFalse($rule->isValid('2013-02-29'));
        $this->assertFalse($rule->isValid('2023-01-06 11:11:11'));
        $this->assertEquals($rule->clean(null), '');
        $this->assertEquals($rule->clean(''), '');
        $this->assertEquals($rule->clean(' '), '');
        $this->assertEquals($rule->clean('0000-00-00'), '');

        $rule = new \Pyncer\Validation\Rule\DateRule(
            allowNull: true,
            allowEmpty: false,
            empty: '0000-00-00',
        );

        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
        $this->assertTrue($rule->isValid('0000-00-00'));
        $this->assertTrue($rule->isValid('2023-01-06'));
        $this->assertTrue($rule->isValid(' 2023-01-06 '));
        $this->assertEquals($rule->clean(null), null);
        $this->assertEquals($rule->clean(''), null);
        $this->assertEquals($rule->clean(' '), null);
        $this->assertEquals($rule->clean('0000-00-00'), null);

        $rule = new \Pyncer\Validation\Rule\DateRule(
            allowNull: false,
            allowEmpty: true,
            empty: '0000-00-00',
        );

        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
        $this->assertTrue($rule->isValid('0000-00-00'));
        $this->assertTrue($rule->isValid('2023-01-06'));
        $this->assertTrue($rule->isValid(' 2023-01-06 '));
        $this->assertEquals($rule->clean(null), '0000-00-00');
        $this->assertEquals($rule->clean(''), '0000-00-00');
        $this->assertEquals($rule->clean(' '), '0000-00-00');
        $this->assertEquals($rule->clean('0000-00-00'), '0000-00-00');

        $rule = new \Pyncer\Validation\Rule\DateRule(
            allowNull: true,
            allowEmpty: true,
            empty: 'any time',
        );

        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
        $this->assertFalse($rule->isValid('0000-00-00'));
        $this->assertTrue($rule->isValid('2023-01-06'));
        $this->assertTrue($rule->isValid(' 2023-01-06 '));
        $this->assertEquals($rule->clean(null), null);
        $this->assertEquals($rule->clean(''), 'any time');
        $this->assertEquals($rule->clean(' '), 'any time');
        $this->assertEquals($rule->clean('any time'), 'any time');
        $this->assertEquals($rule->clean('0000-00-00'), '');

        $rule = new \Pyncer\Validation\Rule\DateRule(
            minValue: '2023-01-01',
            maxValue: '2023-01-31',
        );

        $this->assertTrue($rule->isValid('2023-01-01'));
        $this->assertTrue($rule->isValid('2023-01-21'));
        $this->assertTrue($rule->isValid('2023-01-31'));
        $this->assertFalse($rule->isValid('2022-12-31'));
        $this->assertFalse($rule->isValid('2023-02-01'));
    }

    public function testDateTimeRule(): void
    {
        $rule = new \Pyncer\Validation\Rule\DateTimeRule(
            allowNull: false,
            allowEmpty: false,
            empty: '0000-00-00 00:00:00',
        );

        $this->assertFalse($rule->isValid(null));
        $this->assertTrue($rule->isValid(new DateTime()));
        $this->assertFalse($rule->isValid(''));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
        $this->assertFalse($rule->isValid('0000-00-00 00:00:00'));
        $this->assertTrue($rule->isValid('2023-01-06 11:11:11'));
        $this->assertTrue($rule->isValid(' 2023-01-06 11:11:11 '));
        $this->assertFalse($rule->isValid('2023-01-32 11:11:11'));
        $this->assertFalse($rule->isValid('2023-13-06 11:11:11'));
        $this->assertFalse($rule->isValid('2023-01-06 11:11:61'));
        $this->assertTrue($rule->isValid('2012-02-29 11:11:11'));
        $this->assertFalse($rule->isValid('2013-02-29 11:11:11'));
        $this->assertFalse($rule->isValid('2023-01-06'));
        $this->assertEquals($rule->clean(null), '');
        $this->assertEquals($rule->clean(''), '');
        $this->assertEquals($rule->clean(' '), '');
        $this->assertEquals($rule->clean('0000-00-00 00:00:00'), '');

        $rule = new \Pyncer\Validation\Rule\DateTimeRule(
            allowNull: true,
            allowEmpty: false,
            empty: '0000-00-00 00:00:00',
        );

        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
        $this->assertTrue($rule->isValid('0000-00-00 00:00:00'));
        $this->assertTrue($rule->isValid('2023-01-06 11:11:11'));
        $this->assertTrue($rule->isValid(' 2023-01-06 11:11:11 '));
        $this->assertEquals($rule->clean(null), null);
        $this->assertEquals($rule->clean(''), null);
        $this->assertEquals($rule->clean(' '), null);
        $this->assertEquals($rule->clean('0000-00-00 00:00:00'), null);

        $rule = new \Pyncer\Validation\Rule\DateTimeRule(
            allowNull: false,
            allowEmpty: true,
            empty: '0000-00-00 00:00:00',
        );

        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
        $this->assertTrue($rule->isValid('0000-00-00 00:00:00'));
        $this->assertTrue($rule->isValid('2023-01-06 11:11:11'));
        $this->assertTrue($rule->isValid(' 2023-01-06 11:11:11 '));
        $this->assertEquals($rule->clean(null), '0000-00-00 00:00:00');
        $this->assertEquals($rule->clean(''), '0000-00-00 00:00:00');
        $this->assertEquals($rule->clean(' '), '0000-00-00 00:00:00');
        $this->assertEquals($rule->clean('0000-00-00 00:00:00'), '0000-00-00 00:00:00');

        $rule = new \Pyncer\Validation\Rule\DateTimeRule(
            allowNull: true,
            allowEmpty: true,
            empty: 'any time',
        );

        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
        $this->assertFalse($rule->isValid('0000-00-00 00:00:00'));
        $this->assertTrue($rule->isValid('2023-01-06 11:11:11'));
        $this->assertTrue($rule->isValid(' 2023-01-06 11:11:11 '));
        $this->assertEquals($rule->clean(null), null);
        $this->assertEquals($rule->clean(''), 'any time');
        $this->assertEquals($rule->clean(' '), 'any time');
        $this->assertEquals($rule->clean('any time'), 'any time');
        $this->assertEquals($rule->clean('0000-00-00 00:00:00'), '');

        $rule = new \Pyncer\Validation\Rule\DateTimeRule(
            minValue: '2023-01-01 00:00:10',
            maxValue: '2023-01-31 23:59:49',
        );

        $this->assertTrue($rule->isValid('2023-01-01 00:00:10'));
        $this->assertTrue($rule->isValid('2023-01-21 12:01:01'));
        $this->assertTrue($rule->isValid('2023-01-31 23:59:49'));
        $this->assertFalse($rule->isValid('2023-01-01 00:00:00'));
        $this->assertFalse($rule->isValid('2023-01-31 23:59:59'));
        $this->assertFalse($rule->isValid('2022-12-31 00:00:00'));
        $this->assertFalse($rule->isValid('2023-02-01 00:00:00'));
    }

    public function testEmailRule(): void
    {
        $rule = new \Pyncer\Validation\Rule\EmailRule();

        $this->assertTrue($rule->isValid('name@example.com'));
        $this->assertFalse($rule->isValid('name <name@example.com>'));
        $this->assertFalse($rule->isValid('name'));
        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertTrue($rule->isValid(' '));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
    }

    public function testEnumRule(): void
    {
        $rule = new \Pyncer\Validation\Rule\EnumRule(
            values: ['one', 'two', 'three'],
            allowNull: false,
            allowEmpty: false,
        );

        $this->assertTrue($rule->isValid('one'));
        $this->assertTrue($rule->isValid('two'));
        $this->assertTrue($rule->isValid('three'));
        $this->assertFalse($rule->isValid('four'));
        $this->assertFalse($rule->isValid(null));
        $this->assertFalse($rule->isValid(''));
        $this->assertFalse($rule->isValid(' '));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));

        $this->assertEquals($rule->clean(' one '), 'one');
        $this->assertEquals($rule->clean('four'), '');
        $this->assertEquals($rule->clean(null), '');
        $this->assertEquals($rule->clean(''), '');
        $this->assertEquals($rule->clean(' '), '');

        $rule = new \Pyncer\Validation\Rule\EnumRule(
            values: ['one', 'two', 'three'],
            allowNull: true,
            allowEmpty: false,
        );

        $this->assertTrue($rule->isValid('one'));
        $this->assertFalse($rule->isValid('four'));
        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertTrue($rule->isValid(' '));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));

        $this->assertEquals($rule->clean(' one '), 'one');
        $this->assertEquals($rule->clean('four'), null);
        $this->assertEquals($rule->clean(null), null);
        $this->assertEquals($rule->clean(''), null);
        $this->assertEquals($rule->clean(' '), null);

        $rule = new \Pyncer\Validation\Rule\EnumRule(
            values: ['one', 'two', 'three'],
            allowNull: false,
            allowEmpty: true,
        );

        $this->assertTrue($rule->isValid('one'));
        $this->assertFalse($rule->isValid('four'));
        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertTrue($rule->isValid(' '));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));

        $this->assertEquals($rule->clean(' one '), 'one');
        $this->assertEquals($rule->clean('four'), '');
        $this->assertEquals($rule->clean(null), '');
        $this->assertEquals($rule->clean(''), '');
        $this->assertEquals($rule->clean(' '), '');

        $rule = new \Pyncer\Validation\Rule\EnumRule(
            values: ['one', 'two', 'three'],
            allowNull: true,
            allowEmpty: true,
        );

        $this->assertTrue($rule->isValid('one'));
        $this->assertFalse($rule->isValid('four'));
        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertTrue($rule->isValid(' '));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));

        $this->assertEquals($rule->clean(' one '), 'one');
        $this->assertEquals($rule->clean('four'), '');
        $this->assertEquals($rule->clean(null), null);
        $this->assertEquals($rule->clean(''), '');
        $this->assertEquals($rule->clean(' '), '');
    }

    public function testFloatRule(): void
    {
        $rule = new \Pyncer\Validation\Rule\FloatRule(
            minValue: -100.5,
            maxValue: 100.5,
            allowNull: false,
            allowEmpty: false,
        );

        $this->assertTrue($rule->isValid(-50));
        $this->assertTrue($rule->isValid(-50.5));
        $this->assertTrue($rule->isValid(50));
        $this->assertTrue($rule->isValid(50.5));
        $this->assertTrue($rule->isValid('-50'));
        $this->assertTrue($rule->isValid('-50.5'));
        $this->assertTrue($rule->isValid('50'));
        $this->assertTrue($rule->isValid(' 50 '));
        $this->assertTrue($rule->isValid('50.5'));
        $this->assertFalse($rule->isValid(-150));
        $this->assertFalse($rule->isValid(-150.5));
        $this->assertFalse($rule->isValid(150));
        $this->assertFalse($rule->isValid(150.5));
        $this->assertFalse($rule->isValid(0));
        $this->assertFalse($rule->isValid(0.0));
        $this->assertFalse($rule->isValid(null));
        $this->assertFalse($rule->isValid(true));
        $this->assertFalse($rule->isValid(false));
        $this->assertFalse($rule->isValid(''));
        $this->assertFalse($rule->isValid(' '));
        $this->assertFalse($rule->isValid('test'));
        $this->assertFalse($rule->isValid('50test'));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));

        $this->assertEquals($rule->clean(-50), -50.0);
        $this->assertEquals($rule->clean(-50.5), -50.5);
        $this->assertEquals($rule->clean(50), 50.0);
        $this->assertEquals($rule->clean(50.5), 50.5);
        $this->assertEquals($rule->clean('-50'), -50.0);
        $this->assertEquals($rule->clean('-50.5'), -50.5);
        $this->assertEquals($rule->clean('50'), 50.0);
        $this->assertEquals($rule->clean(' 50 '), 50.0);
        $this->assertEquals($rule->clean('50.5'), 50.5);
        $this->assertEquals($rule->clean(-150), -100.5);
        $this->assertEquals($rule->clean(-150.5), -100.5);
        $this->assertEquals($rule->clean(150), 100.5);
        $this->assertEquals($rule->clean(150.5), 100.5);
        $this->assertEquals($rule->clean(0), '');
        $this->assertEquals($rule->clean(0.0), '');
        $this->assertEquals($rule->clean(null), '');
        $this->assertEquals($rule->clean(''), '');
        $this->assertEquals($rule->clean(' '), '');
        $this->assertEquals($rule->clean('test'), '');

        $rule = new \Pyncer\Validation\Rule\FloatRule(
            minValue: -100.5,
            maxValue: 100.5,
            allowNull: true,
            allowEmpty: true,
        );

        $this->assertTrue($rule->isValid(-50));
        $this->assertTrue($rule->isValid(-50.5));
        $this->assertTrue($rule->isValid(50));
        $this->assertTrue($rule->isValid(50.5));
        $this->assertTrue($rule->isValid('-50'));
        $this->assertTrue($rule->isValid('-50.5'));
        $this->assertTrue($rule->isValid(' 50 '));
        $this->assertTrue($rule->isValid('50.5'));
        $this->assertFalse($rule->isValid(-150));
        $this->assertFalse($rule->isValid(-150.5));
        $this->assertFalse($rule->isValid(150));
        $this->assertFalse($rule->isValid(150.5));
        $this->assertTrue($rule->isValid(0));
        $this->assertTrue($rule->isValid(0.0));
        $this->assertTrue($rule->isValid(null));
        $this->assertFalse($rule->isValid(true));
        $this->assertFalse($rule->isValid(false));
        $this->assertTrue($rule->isValid(''));
        $this->assertTrue($rule->isValid(' '));
        $this->assertFalse($rule->isValid('test'));
        $this->assertFalse($rule->isValid('50test'));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));

        $this->assertEquals($rule->clean(-50), -50.0);
        $this->assertEquals($rule->clean(-50.5), -50.5);
        $this->assertEquals($rule->clean(50), 50.0);
        $this->assertEquals($rule->clean(50.5), 50.5);
        $this->assertEquals($rule->clean('-50'), -50.0);
        $this->assertEquals($rule->clean('-50.5'), -50.5);
        $this->assertEquals($rule->clean('50'), 50.0);
        $this->assertEquals($rule->clean(' 50 '), 50.0);
        $this->assertEquals($rule->clean('50.5'), 50.5);
        $this->assertEquals($rule->clean(-150), -100.5);
        $this->assertEquals($rule->clean(-150.5), -100.5);
        $this->assertEquals($rule->clean(150), 100.5);
        $this->assertEquals($rule->clean(150.5), 100.5);
        $this->assertEquals($rule->clean(0), 0.0);
        $this->assertEquals($rule->clean(0.0), 0.0);
        $this->assertEquals($rule->clean(null), null);
        $this->assertEquals($rule->clean(''), 0.0);
        $this->assertEquals($rule->clean(' '), 0.0);
        $this->assertEquals($rule->clean('test'), null);
    }

    public function testIdRule(): void
    {
        $mockModel = $this->getMockBuilder(ModelInterface::class)
            ->getMock();

        $mockMapper = $this->getMockBuilder(MapperInterface::class)
            ->getMock();

        $mockMapper->expects($this->any())
            ->method('selectByColumns')
            ->will($this->returnValue($mockModel));

        $rule = new \Pyncer\Validation\Rule\IdRule(
            mapper: $mockMapper,
            allowNull: false,
            allowEmpty: false,
        );

        $this->assertTrue($rule->isValid(1));
        $this->assertTrue($rule->isValid(2));
        $this->assertFalse($rule->isValid(0));
        $this->assertFalse($rule->isValid(null));
        $this->assertFalse($rule->isValid(''));
        $this->assertFalse($rule->isValid(' '));
        $this->assertFalse($rule->isValid('test'));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));

        $this->assertEquals($rule->clean(0), '');
        $this->assertEquals($rule->clean(null), '');
        $this->assertEquals($rule->clean(''), '');
        $this->assertEquals($rule->clean(' '), '');
        $this->assertEquals($rule->clean('test'), '');

        $rule = new \Pyncer\Validation\Rule\IdRule(
            mapper: $mockMapper,
            allowNull: true,
            allowEmpty: true,
        );

        $this->assertTrue($rule->isValid(1));
        $this->assertTrue($rule->isValid(2));
        $this->assertTrue($rule->isValid(0));
        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertTrue($rule->isValid(' '));
        $this->assertFalse($rule->isValid('test'));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));

        $this->assertEquals($rule->clean(0), 0);
        $this->assertEquals($rule->clean(null), null);
        $this->assertEquals($rule->clean(''), 0);
        $this->assertEquals($rule->clean(' '), 0);
        $this->assertEquals($rule->clean('test'), '');
    }

    public function testIntRule(): void
    {
        $rule = new \Pyncer\Validation\Rule\IntRule(
            minValue: -100,
            maxValue: 100,
            allowNull: false,
            allowEmpty: false,
        );

        $this->assertTrue($rule->isValid(-50));
        $this->assertTrue($rule->isValid(-50.0));
        $this->assertFalse($rule->isValid(-50.5));
        $this->assertTrue($rule->isValid(50));
        $this->assertTrue($rule->isValid(50.0));
        $this->assertFalse($rule->isValid(50.5));
        $this->assertTrue($rule->isValid('-50'));
        $this->assertTrue($rule->isValid(' -50 '));
        $this->assertTrue($rule->isValid('-50.0'));
        $this->assertFalse($rule->isValid('-50.5'));
        $this->assertTrue($rule->isValid('50'));
        $this->assertTrue($rule->isValid('50.0'));
        $this->assertFalse($rule->isValid('50.5'));
        $this->assertFalse($rule->isValid(-150));
        $this->assertFalse($rule->isValid(-150.5));
        $this->assertFalse($rule->isValid(150));
        $this->assertFalse($rule->isValid(150.5));
        $this->assertFalse($rule->isValid(0));
        $this->assertFalse($rule->isValid(0.0));
        $this->assertFalse($rule->isValid(null));
        $this->assertFalse($rule->isValid(true));
        $this->assertFalse($rule->isValid(false));
        $this->assertFalse($rule->isValid(''));
        $this->assertFalse($rule->isValid(' '));
        $this->assertFalse($rule->isValid('test'));
        $this->assertFalse($rule->isValid('50test'));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));

        $this->assertEquals($rule->clean(-50), -50);
        $this->assertEquals($rule->clean(-50.0), -50);
        $this->assertEquals($rule->clean(-50.5), '');
        $this->assertEquals($rule->clean(50), 50);
        $this->assertEquals($rule->clean(50.0), 50);
        $this->assertEquals($rule->clean(50.5), '');
        $this->assertEquals($rule->clean('-50'), -50);
        $this->assertEquals($rule->clean('-50.0'), -50);
        $this->assertEquals($rule->clean('-50.5'), '');
        $this->assertEquals($rule->clean('50'), 50);
        $this->assertEquals($rule->clean(' 50 '), 50);
        $this->assertEquals($rule->clean('50.0'), 50);
        $this->assertEquals($rule->clean('50.5'), '');
        $this->assertEquals($rule->clean(-150), -100);
        $this->assertEquals($rule->clean(-150.5), '');
        $this->assertEquals($rule->clean(150), 100);
        $this->assertEquals($rule->clean(150.5), '');
        $this->assertEquals($rule->clean(0), '');
        $this->assertEquals($rule->clean(0.0), '');
        $this->assertEquals($rule->clean(null), '');
        $this->assertEquals($rule->clean(''), '');
        $this->assertEquals($rule->clean(' '), '');
        $this->assertEquals($rule->clean('test'), '');

        $rule = new \Pyncer\Validation\Rule\IntRule(
            minValue: -100,
            maxValue: 100,
            allowNull: true,
            allowEmpty: true,
        );

        $this->assertTrue($rule->isValid('-50.0'));

        $this->assertTrue($rule->isValid(-50));
        $this->assertTrue($rule->isValid(-50.0));
        $this->assertFalse($rule->isValid(-50.5));
        $this->assertTrue($rule->isValid(50));
        $this->assertTrue($rule->isValid(50.0));
        $this->assertFalse($rule->isValid(50.5));
        $this->assertTrue($rule->isValid('-50'));
        $this->assertTrue($rule->isValid(' -50 '));
        $this->assertTrue($rule->isValid('-50.0'));
        $this->assertFalse($rule->isValid('-50.5'));
        $this->assertTrue($rule->isValid('50'));
        $this->assertTrue($rule->isValid('50.0'));
        $this->assertFalse($rule->isValid('50.5'));
        $this->assertFalse($rule->isValid(-150));
        $this->assertFalse($rule->isValid(-150.5));
        $this->assertFalse($rule->isValid(150));
        $this->assertFalse($rule->isValid(150.5));
        $this->assertTrue($rule->isValid(0));
        $this->assertTrue($rule->isValid(0.0));
        $this->assertTrue($rule->isValid('0'));
        $this->assertTrue($rule->isValid('0.0'));
        $this->assertTrue($rule->isValid(null));
        $this->assertFalse($rule->isValid(true));
        $this->assertFalse($rule->isValid(false));
        $this->assertTrue($rule->isValid(''));
        $this->assertTrue($rule->isValid(' '));
        $this->assertFalse($rule->isValid('test'));
        $this->assertFalse($rule->isValid('50test'));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));

        $this->assertEquals($rule->clean(-50), -50);
        $this->assertEquals($rule->clean(-50.0), -50);
        $this->assertEquals($rule->clean(-50.5), '');
        $this->assertEquals($rule->clean(50), 50);
        $this->assertEquals($rule->clean(50.0), 50);
        $this->assertEquals($rule->clean(50.5), '');
        $this->assertEquals($rule->clean('-50'), -50);
        $this->assertEquals($rule->clean('-50.0'), -50);
        $this->assertEquals($rule->clean('-50.5'), '');
        $this->assertEquals($rule->clean('50'), 50);
        $this->assertEquals($rule->clean(' 50 '), 50);
        $this->assertEquals($rule->clean('50.0'), 50);
        $this->assertEquals($rule->clean('50.5'), '');
        $this->assertEquals($rule->clean(-150), -100);
        $this->assertEquals($rule->clean(-150.5), '');
        $this->assertEquals($rule->clean(150), 100);
        $this->assertEquals($rule->clean(150.5), '');
        $this->assertEquals($rule->clean(0), 0);
        $this->assertEquals($rule->clean(0.0), 0);
        $this->assertEquals($rule->clean(null), null);
        $this->assertEquals($rule->clean(''), 0);
        $this->assertEquals($rule->clean(' '), 0);
        $this->assertEquals($rule->clean('test'), '');
    }

    public function testNullifyRule(): void
    {
        $rule = new \Pyncer\Validation\Rule\NullifyRule(
            allowWhitespace: false,
        );

        $this->assertTrue($rule->isValid('test'));
        $this->assertTrue($rule->isValid(1));
        $this->assertTrue($rule->isValid(1.1));
        $this->assertTrue($rule->isValid(true));
        $this->assertTrue($rule->isValid(['a' => 'b']));
        $this->assertTrue($rule->isValid(''));
        $this->assertTrue($rule->isValid(' '));
        $this->assertTrue($rule->isValid(0));
        $this->assertTrue($rule->isValid(0.0));
        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(false));
        $this->assertTrue($rule->isValid([]));

        $this->assertEquals($rule->clean('test'), 'test');
        $this->assertEquals($rule->clean(1), 1);
        $this->assertEquals($rule->clean(1.1), 1.1);
        $this->assertEquals($rule->clean(true), true);
        $this->assertEquals($rule->clean(['a' => 'b']), ['a' => 'b']);
        $this->assertEquals($rule->clean(''), null);
        $this->assertEquals($rule->clean(' '), null);
        $this->assertEquals($rule->clean(0), null);
        $this->assertEquals($rule->clean(0.0), null);
        $this->assertEquals($rule->clean(null), null);
        $this->assertEquals($rule->clean(false), null);
        $this->assertEquals($rule->clean([]), null);

        $rule = new \Pyncer\Validation\Rule\NullifyRule(
            allowWhitespace: true,
        );
        $this->assertEquals($rule->clean(' '), ' ');
    }

    public function testPasswordRule(): void
    {
        $rule = new \Pyncer\Validation\Rule\PasswordRule(
            minLength: 3,
            maxLength: 10,
            requireNumericCharacters: true,
            requireAlphaCharacters: true,
            requireLowerCaseCharacters: true,
            requireUpperCaseCharacters: true,
            requireSpecialCharacters: true,
            specialCharacters: '+=-_!@#$%^&*()?<>{}[]"\'.,`~|\\/:;',
            allowWhitespace: false,
        );

        $this->assertTrue($rule->isValid('1aZ$'));
        $this->assertFalse($rule->isValid('aZ$'));
        $this->assertFalse($rule->isValid('1Z$'));
        $this->assertFalse($rule->isValid('1a$'));
        $this->assertFalse($rule->isValid('1aZ'));
        $this->assertTrue($rule->isValid('1a Z$'));
        $this->assertTrue($rule->isValid(' 1a Z$ '));
        $this->assertTrue($rule->isValid('1aZ$aaaaaa'));
        $this->assertFalse($rule->isValid('1aZ$aaaaaab'));

        $this->assertTrue($rule->isValidAndClean('1a Z$'));
        $this->assertFalse($rule->isValidAndClean(' 1a Z$ '));
    }

    public function testPhoneRule(): void
    {
        $rule = new \Pyncer\Validation\Rule\PhoneRule(
            allowNanp: true,
            allowE164: true,
            allowFormatting: true,
        );

        $this->assertTrue($rule->isValid('555-555-5555'));
        $this->assertTrue($rule->isValid('(555) 555-5555'));
        $this->assertTrue($rule->isValid('+1-555-555-5555'));
        $this->assertTrue($rule->isValid('+1-555-555'));

        $rule = new \Pyncer\Validation\Rule\PhoneRule(
            allowNanp: true,
            allowE164: false,
            allowFormatting: false,
        );

        $this->assertTrue($rule->isValid('555-555-5555'));
        $this->assertTrue($rule->isValid('(555) 555-5555'));
        $this->assertTrue($rule->isValid('+1-555-555-5555'));
        $this->assertFalse($rule->isValid('+1-555-555'));

        $this->assertFalse($rule->isValidAndClean('(555) 555-5555'));
    }

    public function testRequiredRule(): void
    {
        $rule = new \Pyncer\Validation\Rule\RequiredRule(
            allowWhitespace: false,
        );

        $this->assertTrue($rule->isValid('test'));
        $this->assertTrue($rule->isValid(1));
        $this->assertTrue($rule->isValid(1.1));
        $this->assertTrue($rule->isValid(true));
        $this->assertTrue($rule->isValid(['a' => 'b']));

        $this->assertFalse($rule->isValid(''));
        $this->assertFalse($rule->isValid(' '));
        $this->assertFalse($rule->isValid(0));
        $this->assertFalse($rule->isValid(0.0));
        $this->assertFalse($rule->isValid(null));
        $this->assertFalse($rule->isValid(false));
        $this->assertFalse($rule->isValid([]));

        $rule = new \Pyncer\Validation\Rule\RequiredRule(
            allowWhitespace: true,
        );

        $this->assertTrue($rule->isValid('test'));
        $this->assertTrue($rule->isValid(' '));
        $this->assertTrue($rule->isValid(1));
        $this->assertTrue($rule->isValid(1.1));
        $this->assertTrue($rule->isValid(true));
        $this->assertTrue($rule->isValid(['a' => 'b']));

        $this->assertFalse($rule->isValid(''));
        $this->assertFalse($rule->isValid(0));
        $this->assertFalse($rule->isValid(0.0));
        $this->assertFalse($rule->isValid(null));
        $this->assertFalse($rule->isValid(false));
        $this->assertFalse($rule->isValid([]));
    }

    public function testStringRule(): void
    {
        $rule = new \Pyncer\Validation\Rule\StringRule(
            allowNull: false,
            allowEmpty: false,
            allowWhitespace: false,
        );

        $this->assertTrue($rule->isValid('testing'));
        $this->assertTrue($rule->isValid(' testing '));
        $this->assertTrue($rule->isValid('test'));
        $this->assertTrue($rule->isValid(' test '));
        $this->assertFalse($rule->isValid(null));
        $this->assertFalse($rule->isValid(''));
        $this->assertFalse($rule->isValid(' '));
        $this->assertFalse($rule->isValid(false));
        $this->assertTrue($rule->isValid(true));
        $this->assertTrue($rule->isValid(0));
        $this->assertTrue($rule->isValid(0.0));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));

        $this->assertEquals($rule->clean(null), '');
        $this->assertEquals($rule->clean(''), '');
        $this->assertEquals($rule->clean(' '), '');
        $this->assertEquals($rule->clean(false), '');
        $this->assertEquals($rule->clean(true), '1');
        $this->assertEquals($rule->clean(0), '0');
        $this->assertEquals($rule->clean(0.0), '0');
        $this->assertEquals($rule->clean([]), '');
        $this->assertEquals($rule->clean(['a' => 'b']), '');

        $rule = new \Pyncer\Validation\Rule\StringRule(
            minLength: 5,
            maxLength: 10,
            allowNull: true,
            allowEmpty: true,
            allowWhitespace: false,
        );

        $this->assertTrue($rule->isValid('testing'));
        $this->assertTrue($rule->isValid(' testing '));
        $this->assertFalse($rule->isValid('test'));
        $this->assertFalse($rule->isValid(' test '));
        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertTrue($rule->isValid(' '));
        $this->assertTrue($rule->isValid(false));
        $this->assertFalse($rule->isValid(true));
        $this->assertFalse($rule->isValid(0));
        $this->assertFalse($rule->isValid(0.0));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));

        $this->assertEquals($rule->clean(null), null);
        $this->assertEquals($rule->clean(''), '');
        $this->assertEquals($rule->clean(' '), '');
        $this->assertEquals($rule->clean(false), '');
        $this->assertEquals($rule->clean(true), '1');
        $this->assertEquals($rule->clean(0), '0');
        $this->assertEquals($rule->clean(0.0), '0');
        $this->assertEquals($rule->clean([]), null);
        $this->assertEquals($rule->clean(['a' => 'b']), null);

        $rule = new \Pyncer\Validation\Rule\StringRule(
            minLength: 5,
            maxLength: 10,
            allowWhitespace: true,
        );

        $this->assertTrue($rule->isValid('testing'));
        $this->assertTrue($rule->isValid(' testing '));
        $this->assertFalse($rule->isValid('test'));
        $this->assertTrue($rule->isValid(' test '));

        $this->assertEquals($rule->clean('testing'), 'testing');
        $this->assertEquals($rule->clean(' testing '), ' testing ');
        $this->assertEquals($rule->clean('test'), 'test');
        $this->assertEquals($rule->clean(' test '), ' test ');
    }

    public function testTimeRule(): void
    {
        $rule = new \Pyncer\Validation\Rule\TimeRule(
            allowNull: false,
            allowEmpty: false,
            empty: '00:00:00',
        );

        $this->assertFalse($rule->isValid(null));
        $this->assertFalse($rule->isValid(''));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
        $this->assertFalse($rule->isValid('00:00:00'));
        $this->assertTrue($rule->isValid('12:12:12'));
        $this->assertTrue($rule->isValid('-12:12:12'));
        $this->assertTrue($rule->isValid('24:12:12'));
        $this->assertTrue($rule->isValid('-24:12:12'));
        $this->assertTrue($rule->isValid(' 12:12:12 '));
        $this->assertEquals($rule->clean(null), '');
        $this->assertEquals($rule->clean(''), '');
        $this->assertEquals($rule->clean(' '), '');
        $this->assertEquals($rule->clean('00:00:00'), '');
        $this->assertEquals($rule->clean('test'), '');

        $rule = new \Pyncer\Validation\Rule\TimeRule(
            allowNull: true,
            allowEmpty: false,
            empty: '00:00:00',
        );

        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
        $this->assertTrue($rule->isValid('00:00:00'));
        $this->assertTrue($rule->isValid('12:12:12'));
        $this->assertTrue($rule->isValid('-12:12:12'));
        $this->assertTrue($rule->isValid('24:12:12'));
        $this->assertTrue($rule->isValid('-24:12:12'));
        $this->assertTrue($rule->isValid(' 12:12:12 '));
        $this->assertEquals($rule->clean(null), null);
        $this->assertEquals($rule->clean(''), null);
        $this->assertEquals($rule->clean(' '), null);
        $this->assertEquals($rule->clean('00:00:00'), null);
        $this->assertEquals($rule->clean('test'), '');

        $rule = new \Pyncer\Validation\Rule\TimeRule(
            allowNull: false,
            allowEmpty: true,
            empty: '00:00:00',
        );

        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
        $this->assertTrue($rule->isValid('00:00:00'));
        $this->assertTrue($rule->isValid('12:12:12'));
        $this->assertTrue($rule->isValid('-12:12:12'));
        $this->assertTrue($rule->isValid('24:12:12'));
        $this->assertTrue($rule->isValid('-24:12:12'));
        $this->assertTrue($rule->isValid(' 12:12:12 '));
        $this->assertEquals($rule->clean(null), '00:00:00');
        $this->assertEquals($rule->clean(''), '00:00:00');
        $this->assertEquals($rule->clean(' '), '00:00:00');
        $this->assertEquals($rule->clean('00:00:00'), '00:00:00');
        $this->assertEquals($rule->clean('test'), '00:00:00');

        $rule = new \Pyncer\Validation\Rule\TimeRule(
            allowNull: true,
            allowEmpty: true,
            empty: 'any time',
        );

        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
        $this->assertTrue($rule->isValid('00:00:00'));
        $this->assertTrue($rule->isValid('12:12:12'));
        $this->assertTrue($rule->isValid('-12:12:12'));
        $this->assertTrue($rule->isValid('24:12:12'));
        $this->assertTrue($rule->isValid('-24:12:12'));
        $this->assertTrue($rule->isValid(' 12:12:12 '));
        $this->assertEquals($rule->clean(null), null);
        $this->assertEquals($rule->clean(''), 'any time');
        $this->assertEquals($rule->clean(' '), 'any time');
        $this->assertEquals($rule->clean('any time'), 'any time');
        $this->assertEquals($rule->clean('00:00:00'), '00:00:00');
        $this->assertEquals($rule->clean('test'), null);

    }

    public function testUidRule(): void
    {
        $rule = new \Pyncer\Validation\Rule\UidRule(
            allowNull: false,
            allowEmpty: false,
            empty: '00000000-0000-0000-0000-000000000000',
        );

        $this->assertFalse($rule->isValid(null));
        $this->assertFalse($rule->isValid(''));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
        $this->assertFalse($rule->isValid('00000000-0000-0000-0000-000000000000'));
        $this->assertTrue($rule->isValid('adda5eb3-f98d-4eff-9ea6-ad2ff15473cc'));
        $this->assertEquals($rule->clean(null), '');
        $this->assertEquals($rule->clean(''), '');
        $this->assertEquals($rule->clean(' '), '');
        $this->assertEquals($rule->clean('00000000-0000-0000-0000-000000000000'), '');

        $rule = new \Pyncer\Validation\Rule\UidRule(
            allowNull: true,
            allowEmpty: false,
            empty: '00000000-0000-0000-0000-000000000000',
        );

        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
        $this->assertTrue($rule->isValid('00000000-0000-0000-0000-000000000000'));
        $this->assertTrue($rule->isValid('adda5eb3-f98d-4eff-9ea6-ad2ff15473cc'));
        $this->assertEquals($rule->clean(null), null);
        $this->assertEquals($rule->clean(''), null);
        $this->assertEquals($rule->clean(' '), null);
        $this->assertEquals($rule->clean('00000000-0000-0000-0000-000000000000'), null);

        $rule = new \Pyncer\Validation\Rule\UidRule(
            allowNull: false,
            allowEmpty: true,
            empty: '00000000-0000-0000-0000-000000000000',
        );

        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
        $this->assertTrue($rule->isValid('00000000-0000-0000-0000-000000000000'));
        $this->assertTrue($rule->isValid('adda5eb3-f98d-4eff-9ea6-ad2ff15473cc'));
        $this->assertEquals($rule->clean(null), '00000000-0000-0000-0000-000000000000');
        $this->assertEquals($rule->clean(''), '00000000-0000-0000-0000-000000000000');
        $this->assertEquals($rule->clean(' '), '00000000-0000-0000-0000-000000000000');
        $this->assertEquals($rule->clean('00000000-0000-0000-0000-000000000000'), '00000000-0000-0000-0000-000000000000');

        $rule = new \Pyncer\Validation\Rule\UidRule(
            allowNull: true,
            allowEmpty: true,
            empty: 'any time',
        );

        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
        $this->assertFalse($rule->isValid('00000000-0000-0000-0000-000000000000'));
        $this->assertTrue($rule->isValid('adda5eb3-f98d-4eff-9ea6-ad2ff15473cc'));
        $this->assertEquals($rule->clean(null), null);
        $this->assertEquals($rule->clean(''), 'any time');
        $this->assertEquals($rule->clean(' '), 'any time');
        $this->assertEquals($rule->clean('any time'), 'any time');
        $this->assertEquals($rule->clean('00000000-0000-0000-0000-000000000000'), '');
    }

    public function testUrldRule(): void
    {
        $rule = new \Pyncer\Validation\Rule\UrlRule();

        $this->assertTrue($rule->isValid('https://pyncer.com'));
        $this->assertFalse($rule->isValid('pyncer.com'));
        $this->assertFalse($rule->isValid('name'));
        $this->assertTrue($rule->isValid(null));
        $this->assertTrue($rule->isValid(''));
        $this->assertTrue($rule->isValid(' '));
        $this->assertFalse($rule->isValid([]));
        $this->assertFalse($rule->isValid(['a' => 'b']));
    }
}
