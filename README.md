**No Longer Maintained**

# Landing CMS
A simple CMS for Landing Pages.

**v0.0.6** Be careful! While that is an alpha version.

### Hello guys!
I wrote this CMS for myself and want to share it for everyone.
Landing CMS is a simple tool for management landing pages. It doesn't use any databases. All data stores in a plain text files. You need only web-server with PHP 5.2 or newer (7 and 7.3 supports)!

[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QGKZW29YXRDCL)

***

### Installation
For using the CMS you should copy all files from this repository to root directory of your site. Now you need config permissions.

For checking configs you can use helper: open `https://your_site.com/install.php`
![Installation helper](https://github.com/Elias-Black/Landing-CMS/blob/gh-pages/screenshots/installation.png "Installation helper")

You should set 777 permissions for upload and DB directories:
- assets/_cms/uploads/tinymce/source/
- assets/_cms/uploads/tinymce/thumbs/
- cms/_db/

If helper show that all is done you should going to Admin panel: `https://your_site.com/cms/`

Now you need to create your password.
![Creating password](https://github.com/Elias-Black/Landing-CMS/blob/gh-pages/screenshots/creating-password.png "Creating password")

**All is done!** You can create your first Field and use it in your code.
![Welcome to Landing CMS](https://github.com/Elias-Black/Landing-CMS/blob/gh-pages/screenshots/clean-cms.png "Welcome to Landing CMS")

***

### How to use
In the Admin panel you can create Fields with 7 formats:
- String
- Multiple line text
- WYSIWYG Field
- Checkbox
- Color Picker
- File Uploader
- Group of Fields

When you creating a Field you need to enter an Alias.
![Adding a new Field](https://github.com/Elias-Black/Landing-CMS/blob/gh-pages/screenshots/adding-field.png "Adding a new Field")

Filled Admin panel will look something like this:
![Filled CMS](https://github.com/Elias-Black/Landing-CMS/blob/gh-pages/screenshots/filled-cms.png "Filled CMS")


Then, when you want to use your Fields in your files you need to connect Controller to your file.

**Example:**
```php
<?php

// Connecting the public controller
require_once('assets/controller.php');

?>

<html>
  ...
```
### Text
You can to call your Fields by Alias.

**Example:**
```php
<html>
  <head>
    <title><?=$get['title'];?></title>
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
### Groups
If you created a Group, you can call its specific element.

**Example:**
```php
<p>
   <?=$get['main_group']['item1'];?>
</p>
```
You can also iterate through each of the Group's elmenets. (Subgroup or Field).

**Example:**
```php
<?php foreach($get['main_group'] as $name => $item): ?>
   <ul>
      <li><b><?=$name;?>:</b> <?=$item;?>;</li>
   </ul>
<?php endforeach; ?>
```
### Modules
If you need use any modules you can creat these in `modules` directory. For using your Fields in your modules you should connect DB before connected this modules.

**Example:**
```php
<?php

// Connecting the public controller
require_once('assets/controller.php');

// Connecting a module
require_once('modules/rand_num.php');

?>

  <html>
    <head>

  ...

  <b>Random number module:</b> <?=$rand_num;?>

  ...
```
That so... In my opinion this functionality enough for all landing pages. If you
 don't think so you can fork my project or suggest me some features ;)
***

### Hotkeys
To speed up work with the CMS, you can use the following hotkeys:
- `special-key` + `s` - save;
- `special-key` + `n` - new Field;
- `special-key` + `z` - cancel;

You can find out the special keys for your browser [here](https://developer.mozilla.org/en-US/docs/Web/HTML/Global_attributes/accesskey "accesskey - HTML: HyperText Markup Language | MDN").

### Languages
You can translate the CMS to any languages. How to do this:
- Go to `cms/_lang`;
- Create a directory with language name like `en`;
- Into the new directory create the `main.php` file;
- Fill the `main.php` file by analogy with `cms/_lang/en/main.php`;
- Update the `cms/_classes/utils.class.php` file's constant `LANGUAGE` with the name of the language directory;

### To do
v 0.0.7:
- Fix Color Picker and File Uploader for IE9;
- Add Security Key to Responsive File Manager;
- Add JS-less accessebility for File Uploader;
- Fix Ghost Bug for Color Picker (sometimes just does not work);
- Add supporting RGB/RGBA/HEX to Color Picker;

v 1.0:
- Add Admin icon to frontend;
- Add Plugins support
- Add Drag'n'Drop for Groups and Fields sorting;

v 2.0
- Add Pages;

***

### Thanks
- [TinyMCE](https://github.com/tinymce/tinymce "TinyMCE project on GitHub.") for the WYSIWYG editor;
- [Responsive File Manager](http://www.responsivefilemanager.com/ "Responsive File Manager site.") for TinyMCE external filemanager;
- [Tovic / color-picker](https://github.com/tovic/color-picker "A simple color picker plugin written in pure JavaScript, for modern browsers.") for the Color Picker;
- [Forsigner / magic-check](https://github.com/forsigner/magic-check "Beautify Radio and Checkbox with pure CSS.") for beautiful Checkboxes;
- [Bootstrap](https://github.com/twbs/bootstrap "Bootstrap project on GitHub.") for Admin panel styles.
