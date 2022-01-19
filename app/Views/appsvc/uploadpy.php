<!doctype html>
<html>
	<head>
        <meta charset="utf-8">
	    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    </head>

    <body>
        <form id="upload-form" method="post" enctype="multipart/form-data" action="/<?=$cat_name?>/uploadpy">
            <input type="file" accept=".*" id="upload-file" name="upload-file">    
            <input type="submit" class="form-button" value="upload">

        </form>


    </body>

</html>