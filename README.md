# Landing CMS
A simple CMS for landing pages.

Be careful! While that is an alpha version.

### Hello guys!
I wrote this CMS for myself and want to share it for everyone.
Landing CMS is a simple tool for management landing pages. It don't use any databases. All data stores in a plain text files. You need only web-server with PHP 5.3 and early!

*For the rapid development of the project, you can make a donation!*

[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QGKZW29YXRDCL)

***

### Installation
For using the CMS you should copy all files from this repository to root directory of your site. Now you need config permissions.

For checking configs you can use helper: open `https://your_site.com/install.php`
![Installation helper](https://github.com/Elias-Black/Landing-CMS/blob/gh-pages/screenshots/installation.png "Installation helper")

You should set 777 permissions for upload directories:
- web/_cms/uploads/tinymce/source/
- web/_cms/uploads/tinymce/thumbs/

You should set 666 permissions for DB files:
- cms/_db/password.php
- cms/_db/private.php
- cms/_db/public.php

If helper show that all is done you should going to Admin panel: `https://your_site.com/cms/`

Now you need to create your password.
![Creating password](https://github.com/Elias-Black/Landing-CMS/blob/gh-pages/screenshots/creating-password.png "Creating password")

**All is done!** You can create your first field and use it in your code.
![Welcome to Landing CMS](https://github.com/Elias-Black/Landing-CMS/blob/gh-pages/screenshots/clean-cms.png "Welcome to Landing CMS")

***

### How to use
In the Admin panel you can create fields with 5 formats: string, multiple line text, WYSIWYG field, checkbox and Color Picker. When you creating a field you need to enter an alias.
![Adding a new field](https://github.com/Elias-Black/Landing-CMS/blob/gh-pages/screenshots/adding-field.png "Adding a new field")


Then, when you want to use your fields in your files you need to connect DB to your file.

**Example:**
```php
<?php

// Connecting the database
require_once('cms/_db/public.php');

?>

<html>
  ...
```
### Text
You can to call your fields by alias.

**Example:**
```php
<html>
  <head>
    <title><?php echo $get['title']; ?></title>
    ...
```
![Demo page](https://github.com/Elias-Black/Landing-CMS/blob/gh-pages/screenshots/demo-page.png "Demo page")
### Checkbox
If you want to use checkboxes you need to check the values of equality 'on'.

**Example:**
```php
<body>

...

  <?php if($get['maintenance_mode'] == 'on'): ?>
    <h1>Try again later</h1>
  <?php else: ?>
    <h1>Hello, World!</h1>
  <?php endif; ?>

...

</body>
```
### Modules
If you need use any modules you can creat these in `modules` directory. For using your fields in your modules you should connect DB before connected this modules.

**Example:**
```php
<?php

// Connecting the batabase
require_once('cms/_db/public.php');

// Connecting a module
require_once('modules/rand_num.php');

?>

  <html>
    <head>

  ...

  <b>Random number module:</b> <?php echo $rand_num; ?>

  ...
```
That so... In my opinion this functionality enough for all landing pages. If you
 don't think so you can fork my project or suggest me some features ;)
***

### To do
v 1.0:
- Add Admin icon to frontend
- Add dictionary array with all texts
- Add Security Key to Responsive File Manager
- Set Transliteration to Responsive File Manager
- Add rulls for alias names

v 2.0:
- Add Groups
- Add editing Group's names
- Add Groups to public DB for enumerating Fields
- Add editing Fields

v 3.0:
- Add Drag'n'Drop for Groups and Fields

v 4.0
- Add Pages

***

### Thanks
- [TinyMCE](https://github.com/tinymce/tinymce "TinyMCE project on GitHub.") for the WYSUWYG;
- [Responsive File Manager](http://www.responsivefilemanager.com/ "Responsive File Manager site.") for TinyMCE external filemanager;
- [Tovic / color-picker](https://github.com/tovic/color-picker "A simple color picker plugin written in pure JavaScript, for modern browsers.") for the Color Picker;
- [Bootstrap](https://github.com/twbs/bootstrap "Bootstrap project on GitHub.") for Admin panel styles.
