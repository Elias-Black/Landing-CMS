# Landing CMS
A simple CMS for landing pages.

### Hello guys!
I wrote this CMS for myself and want to share it for everyone.
Landing CMS is a simple tool for management landing pages. It don't use any databases. All data stores in a plain text files. You need only web-server with PHP 5.3 and early!

### How to use
In the Admin panel you can create fields with 4 formats: string, multiple line text, WYSIWYG field and checkbox. When you creating a field you need to enter an alias.
Then, when you want to use you fields in your files you need to connect DB to your file. Now you can to call your fields by alias.

**Example:**
```php
<?php

// Connecting the database
require_once('cms/_db/public.php');

?>

<html>
  <head>
    <title><?php echo $get['title']; ?></title>
    ...
```



### Thanks
- [TinyMCE](https://github.com/tinymce/tinymce) for the WYSUWYG;
- [Responsive File Manager](http://www.responsivefilemanager.com/) for TinyMCE external filemanager.
