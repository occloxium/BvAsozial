# Changenotes

# Version 1.2.41
**15.04.2017**

*   **Implemented privacy mod** See [#23](https://github.com/occloxium/BvAsozial/issues/23), [#22](https://github.com/occloxium/BvAsozial/issues/22), [#21](https://github.com/occloxium/BvAsozial/issues/21) for further details
*   Moved user data to `/users/data/` and adjusted most of the content links
*   Introduced new API functions for accessing user data
*   Capsulated some huge files to compose their code from *sections* See [#41](https://github.com/occloxium/BvAsozial/issues/41)
*   Added the first unofficial PHP unit test for the privacy module
*   Factorized the HTML Drawer
*   Adjusted some CSS and JavaScript

# Version 1.2.40
**06.04.2017**

*   Fixed the [#40](https://github.com/occloxium/BvAsozial/issues/40) closure issue by ditching the whole feature
*   Protected the `/users/` directory from external HTTP access
*   Now accesses the `/users/` files through a designated `getUserFile.php`
*   Made some typographical changes
*   Minor refinements to some AJAX scripts

# Version 1.2.39
**25.02.2017**

*   Added Settings page & implemented back-end functionality
*   Added seamless integration of privacy settings
*   Moved changing passwords from `/users/` to `/einstellungen/`

# Version 1.2.38
**23.02.2017**

*   Fixed some mayor problems with the "Ultra-Fragen-Manager"
*   Fixed a huge Rufnamen error for both `/users/` & `/mod/edit/`
*   Added some CSS improvements
*   Added `/about/` frame
*   Fixed other minor bugs

# Version 1.2.37
**21.02.2017**

*   Fully ported the name system to the v2 state of `/mod/edit/` in `/users/`
*   Updated the control flow in `/includes/answerQuestions.php` to fit the new post data structure
*   Fixed some minor problems with the "Ultra-Fragen-Manager"
*   Fixed some CSS
*   Improved the pie chart progress indicators to properly calculate progress of a users profile
*   Milestones are now stored directly in the repository / project
*   Added offline support for the Material Icon Pack to even work if Google is not reachable

# Version 1.2.36
**18.02.2017**

*   Added moderator addition
*   Fixed control flow in `is_mod` & `is_admin`
*   Fixed unstable registration logins
*   Fixed not properly executing javascript in `js/fragen.registrierung.js`

# Version 1.2.35
**16.02.2017**

*   Added the "Ultra-Fragen-Manager" back-end script (`/includes/frage.php`);
*   Dropped "Fragen für Freunde" section from `/users/`
*   Added *most* moderator features to the front-end. See [Milestones](milestones.md) for details on the mod feature
*   Added *mod management* to the admin panel
*   Added breadcrump navigation to most singleton operation-only pages in `/admin/`
*   Added `/mod/`. See [Milestones](milestones.md) for details

# Version 1.2.34
**08.02.2017**

*   Minor changes to some scripts
*   Added mod access to user properties

# Version 1.2.33
**01.02.2017**

*   Prepared distant server execution
*   Fixed some errors in question alteration


# Version 1.2.32
**31.01.2017**

*   Changed the accent color of the whole platform to a bright blue
*   Fixed all errors in main user interaction files like `/fragen/`, `/users/`, `/freunde/` etc.
*   Removed the remaining usage of `$_SESSION['username']` and replaced it with `$_SESSION['user']['uid']` to access the complete user object instead of an own value out of context
*   Reworked most of the AJAX PHP Content files to use the `require` functions
*   Fixed some other bugs in quite a few files
*   Minor changes to backend structure

# Version 1.2.31
**25.01.2017**

*   *Finally* fixed the errors in user registering by an admin (There were some missplaced dependencies)
*   Fixed some bugs in general admin operations
*   Fixed some issues in the completely **unstable** process of registration
*   Prepared avatar setting from registration

**Notes**

*   the question subsection of the registration process is completely broken. Fix it ASAP.


# Version 1.2.30
**22.01.2017**

*   ~~Fixed errors with registration (403 when accessing)~~
*   Removed some old JavaScript files
*   Updated installer to auto-install composer on the server (*indev*)

**Notes**

*   Installer: db.php is definitely outdated, constants.pre.php is probably outdated
*   Validate composer installation script does what it's supposed to

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
*   ~~Fixed most of the registration process~~
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
