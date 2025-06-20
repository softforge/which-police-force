# Changelog

All notable changes to the Which Police Force module will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.2.0] - 2025-06-20

### Added
- Module parameters to control display of individual elements:
  - Show/hide postcode
  - Show/hide neighbourhood
  - Show/hide area details (district, ward, parish, etc.)
  - Show/hide website link
  - Show/hide phone number
- Reorganized layout with police force name, website, and phone on same line
- More compact and cleaner display format

### Changed
- Moved website button and phone number to same line as police force name
- Area details now on separate line for better readability
- Elements only display when enabled in module configuration

## [1.1.0] - 2025-06-20

### Added
- Location details from postcodes.io API including district, ward, parish, constituency, region and country
- Display of comprehensive area information in the results
- Template-agnostic styling approach for better theme compatibility

### Changed
- Completely refactored CSS to remove opinionated styling
- Removed all color definitions, backgrounds, borders and shadows
- Removed dark mode styles (now handled by template)
- Simplified responsive and print styles
- Updated JavaScript to dynamically display available area fields

### Improved
- Module now blends naturally with any Joomla template's styling
- Enhanced user information with detailed geographic context

## [1.0.1] - 2025-06-19

### Fixed
- JSON response output issues in AJAX handler
- Prevented double JSON encoding in API responses

## [1.0.0] - 2025-06-18

### Added
- Initial release
- UK postcode lookup functionality
- Integration with UK Police API
- AJAX-based interface
- Caching system for API responses
- Responsive design
- Multilingual support structure