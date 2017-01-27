# Changelog

All Notable changes to `RecordCollection` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## [Unreleased]

### Added
- Added middleware to config file
- Added name of function to tell if user is admin or not to config file
- Changed code to point to config file instead of hardcoding in how to determine user permissions

### Fixed
- Removed call to setLanguage() function on every single request, replaced with my translate middleware, much simpler
- Removed VueJS from add and edit screens
- Fixed bugs