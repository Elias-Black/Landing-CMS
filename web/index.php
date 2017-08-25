<?php

$public_db_path = 'cms/_db/public.php';

$public_db_content = file_get_contents($public_db_path);



$get = unserialize($public_db_content);
