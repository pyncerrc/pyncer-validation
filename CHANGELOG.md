# Change Log

## 1.3.0 - Unreleased

### Added

- Added empty parameter to NullifyRule and RequiredRule class constructors.

### Changed

- IdRule has been separated form int rule.
- The allowNull and allowEmpty parameters have been removed from the Base64IdRule and UidRule class constructor.

## 1.2.3 - 2024-07-15

### Added

- Added DateTime support to DateRule.

## 1.2.2 - 2024-02-13

### Added

- Added SPECIAL\_CHARACTERS constant to PasswordRule.

## 1.2.1 - 2023-09-16

### Fixed

- Fixed issues with DateTime support in DateTimeRule.
- Removed unnecessary use of iterator\_to\_array function.

## 1.2.0 - 2023-09-04

### Added

- Added password rule.
- Added phone rule.
- Added alias rule.
- Added min and max values for DateRule and DateTimeRule.
- Unit test cases for new rules.
- Added isValidAndClean function to validators.

## 1.1.2 - 2023-05-11

### Fixed

- Added Stringable support to RequiredRule.

## 1.1.1 - 2023-04-30

### Added

- Added more unit test cases for IntRule and FloatRule.

### Fixed

- Fixed issue wtih IntRule and FloatRule accepting invalid values.
- Fixed issues with Stringable support.

## 1.1.0 - 2023-01-14

### Added

- Allow whitespace parameter on multiple rules.
- Date, DateTime, and Time rules.
- Base64 ID rule.
- UID rule.
- Unit tests.
- PHPStan static analysis.

## 1.0.0 - 2022-12-27

Initial release.
