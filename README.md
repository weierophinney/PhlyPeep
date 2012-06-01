PhlyPeep: Twitter clone for DPC 12 Workshop
===========================================

This is a sample module for the DPC 12 Zend Framework 2 workshop on building
re-usable modules. The goals are:

* Demonstrate some principles to follow when writing modules
* Demonstrate a number of components, including the ServiceManager, InputFilter,
  Form, MVC, and Db.

This will not and should not be considered a fully-functional, full-featured
module, though the basic functionality should work.

Dependencies
------------

* PHP >= 5.3.3
* Zend Framework (current master)
* ZfcUser (current master)

Installation
------------

Via Composer:
-------------
Update your composer.json

    {
        "require": {
            /* ... */
            "phly/phly-peep": "dev-master"
        }
    }

and then run:

    php composer.phar install

As a submodule:
---------------

    git submodule add git://github.com/weierophinney/PhlyPeep.git vendor/PhlyPeep

All:
----
After installation, update your `config/application.config.php` to add
"PhlyPeep" as a module.

Database Setup
--------------
Currently, PhlyPeep only ships with a schema for SQLite. You can import it using
the following:

    $> sqlite3 path/to/database < path/to/phly-peep/data/schema.sqlite.sql

