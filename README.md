# Landing CMS
A simple CMS for landing pages.

### Hello guys!
I wrote this CMS for myself and want to share it for everyone.
Landing CMS is a simple tool for management landing pages. It don't use any databases. All data stores in a plain text files. You need only web-server with PHP 5.3 and early!

### How to use
In the Admin panel you can create fields with 4 formats: string, multiple line text, WYSIWYG field and checkbox. When you creating a field you need to enter an alias.

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
#### Text
You can to call your fields by alias.

**Example:**
```php
<html>
  <head>
    <title><?php echo $get['title']; ?></title>
    ...
```
#### Checkbox
If you want to use checkboxes you need to check the values of equality 'on'.

**Example:**
```php
<?php if($get['maintenance_mode'] == 'on'): ?>
  <h1>Try again later</h1>
<?php else: ?>
  <h1>Hello, World!</h1>
<?php endif; ?>
```

### To do
v 1.0:
- Add admin icon to frontend
- Add dictionary array with all texts

v 2.0:
- Add Groups
- Add editing Group's names
- Add editing Fields

v 3.0:
- Add Drag'n'Drop for Groups and Fields



### Thanks
- [TinyMCE](https://github.com/tinymce/tinymce) for the WYSUWYG;
- [Responsive File Manager](http://www.responsivefilemanager.com/) for TinyMCE external filemanager.
