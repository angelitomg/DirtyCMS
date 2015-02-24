# DirtyCMS
This is a CMS?

## What is DirtyCMS?

DirtyCMS is not a good software engineering example. It is a **eficiency** and **simplicity** example.

Its desgined to be simply, powerful and fit in a single file.

## How to install

Just copy DirtyCMS folder to your webroot folder and access **backend.php**.

## Structure

DirtyCMS consists of a few files:

* backend.php - main back-end file
* db.sqlite - database file
* frontend.php - basic functions to query data on front-end interface
* index.php - example file
* uploads/ - uploads directory
* .htaccess - some basic Apache configs

## Changing configs

Is highly recommended move database file **out** of your webroot folder and change value of **SALT** constant. You can do this, change their values in files **backend.php** and **frontend.php**.

You can change default uploads path, changing **UPLOADS_PATH** constant on **backend.php**.

## Author

AMG Labs(http://www.amglabs.net/)

Angelito M. Goulart (http://www.angelitomg.com/)
