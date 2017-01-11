# Changenotes

# Version 1.2.24
**11.01.2017**

* **Introduced User Groups**: A centralized login system for all user groups which then determines the user group and acts appropriately.
* Backend Stuff: `login()` now binds the complete data set of the logged-in user to his session. Access via `$_SESSION['user']`.
* Backend Stuff: Added user groups to the user's session array.
* Added documentation for `/includes/functions.php` to explain each and every function.
* Fixed some of the completely destroyed `/admin/` directory. Complete fix will follow up next.
* Switched to SHA-384 for most hashing to provide more security
* Updated Database to fit the needs for *User Groups*. You can find the complete definition of the database at `/bva-install/preset/db.php`

# Version 1.2.23
**09.01.2017**

* Minor bug fixes
* Minor enhancements in visual presentation

# Version 1.2.22
**08.01.2017**

* Added BvAsozial-Installer for usage on distant server to create certain files needed and database tables automatically
* Added milestones.md file for milestone logging
* Changed the way index.php files import dependencies. At the beginning of each file, call `require( 'constants.php' )` first to create useful constants for handling access to the other dependencies, like almost evertime `require_once( ABS_PATH . INC_PATH . 'functions.php')`.
* Fixed minor installer bugs
* Changed the way passwords get hashed. Now use SHA2-384 for password hashing
* Changed how HTML head tag is loaded. Moved repetitive content to single PHP script file to be loaded by `_getHead(...)` while giving the file name for external css file to be also loaded.
* Changed the way the navigation is loaded. Done by calling `_getNav(...)` with the current position in navigation as parameter.
