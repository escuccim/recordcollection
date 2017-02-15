# Changelog

All Notable changes to `RecordCollection` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## Unreleased
### Added
- Removed references to Laravel's form function to reduce dependencies
- Added ability to delete records
- Added rich card for record
- Added error page if record not found in show view, previously was throwing 404 error

### Fixed
- Updated tests
- Fixed bug related to change in forms

## v0.0.2-beta.2 [2017-01-27]

### Added
- Added middleware to config file
- Added name of function to tell if user is admin or not to config file
- Changed code to point to config file instead of hardcoding in how to determine user permissions

### Fixed
- Removed call to setLanguage() function on every single request, replaced with my translate middleware, much simpler
- Removed VueJS from add and edit screens
- Fixed bugs
- Replaced header section in views with push to stack scripts, which makes more sense