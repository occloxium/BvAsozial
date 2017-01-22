# Changenotes

# Version 1.2.29
**22.01.2017**

*   Added avatar customisation option to registration
*   Added wrapper functions for *Notification System*
*   Added email bodies for *Notification System*
*   Added some helper functions to `functions.php`
*   Added client-side hashing of all transmitted passwords to increase security
*   Altered most login and verification functions to not hash parameter passwords anymore (because of client-side hashing)

# Version 1.2.28
**19.01.2017**

*   Fixed some errors with single-action-pages (mostly admin area), also restyled the headers
*   Fixed some not-yet-updated PHP scripts
*   Fixed most of the registration process
*   **Proposed** *Notification System* (see [version notes](version-notes.md))
*   The sent registration link now automatically tries to log the receivant in

# Version 1.2.27
**17.01.2017**

*   Added Composer for managing the *PHPMailer* package
*   Revoked outdated installation files
*   Generalised invitation: Sending mails is now bound to configurable constants (see `/bva-install/` changes)
*   Fixed some issues
*   **Proposed** *Autobuild* feature to generate installation zip files from current version without `constants.php` & `.htaccess`

# Version 1.2.26
**13.01.2017**

*   Created version notes to hold development notes for the next intermediate versions
*   Took first preparational steps for [version notes](version-notes.md)#1
*   Fixed some issues & logical problems   

# Version 1.2.25
**12.01.2017**

*   **Introduced new group function** : `is_privileged` determines if a user is allowed to perform certain changes
*   `/admin/register-user/` :
*   Fixed page not working at all
*   Improved user creation and removed some unnecessary cases of error catching
*   `/includes/addName.php, /includes/removeName.php` :
*   Updated scripts to fit stylistic needs of **1.2.x**
*   Increased security measures
*   Usage of `is_privileged`
*   Fixed some unnecessary queries in group functions in case a session is already created
*   Updated some CSS
*   *Beautified* more files
*   **SECURITY** : Added client-side hashing of passwords at the login to increase security of passwords. Passwords are now transmitted completely hashed with SHA-384 by JavaScript

# Version 1.2.24
**11.01.2017**

*   **Introduced User Groups**: A centralized login system for all user groups which then determines the user group and acts appropriately.
*   Backend Stuff: `login()` now binds the complete data set of the logged-in user to his session. Access via `$_SESSION['user']`.
*   Backend Stuff: Added user groups to the user's session array.
*   Added documentation for `/includes/functions.php` to explain each and every function.
*   Fixed some of the completely destroyed `/admin/` directory. Complete fix will follow up next.
*   Switched to SHA-384 for most hashing to provide more security
*   Updated Database to fit the needs for *User Groups*. You can find the complete definition of the database at `/bva-install/preset/db.php`

# Version 1.2.23
**09.01.2017**

*   Minor bug fixes
*   Minor enhancements in visual presentation

# Version 1.2.22
**08.01.2017**

*   Added BvAsozial-Installer for usage on distant server to create certain files needed and database tables automatically
*   Added milestones.md file for milestone logging
*   Changed the way index.php files import dependencies. At the beginning of each file, call `require( 'constants.php' )` first to create useful constants for handling access to the other dependencies, like almost evertime `require_once( ABS_PATH . INC_PATH . 'functions.php')`.
*   Fixed minor installer bugs
*   Changed the way passwords get hashed. Now use SHA2-384 for password hashing
*   Changed how HTML head tag is loaded. Moved repetitive content to single PHP script file to be loaded by `_getHead(...)` while giving the file name for external css file to be also loaded.
*   Changed the way the navigation is loaded. Done by calling `_getNav(...)` with the current position in navigation as parameter.
