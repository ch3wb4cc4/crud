<!DOCTYPE html>
<head>
<meta charset="UTF-8" />
<meta name="robots" content="noindex, nofollow"/>
<meta name="viewport" content="width=device-width" />
<meta name=viewport content="width=device-width, initial-scale=1">

<title>x AG Portal</title>

<link rel="icon" href="<?php echo $GLOBALS['template_path']; ?>favicon.ico" sizes="32x32" />
<?php /*
<link rel="icon" href="https://x.de/wp-content/uploads/2021/06/cropped-favicon-32x32.png" sizes="32x32" />
<link rel="icon" href="https://x.de/wp-content/uploads/2021/06/cropped-favicon-192x192.png" sizes="192x192" />
<link rel="apple-touch-icon" href="https://x.de/wp-content/uploads/2021/06/cropped-favicon-180x180.png" />
<meta name="msapplication-TileImage" content="https://x.de/wp-content/uploads/2021/06/cropped-favicon-270x270.png" />
*/ ?>

<style type="text/css">
*{
    border-width: 1px;
	font-family: sans-serif;
	font-size: 16px; 
	line-height: 24px;
}

h1{ font-size: 150%; }
h2{ font-size: 130%; }
h3, h4, h5, h6{ font-size: 100%; }

body{
	background-color: #fff;
	color: #444;
}

a:hover{
    color: #0BBBEF;
}

.app{
	width: 92%;
	max-width: 972px;
	margin: 0 auto;
	color: #444;
	border-color: #444;
	padding: 16px 2%;
}

body *{
	background-color: transparent;
	color: #444;
	border-color: #444;
    border-width: 1px;
}

.header-section{
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: auto;
    display: block;
    overflow: visible;
}

.header{
	margin: 0 auto;
	display: block;
	overflow: hidden;
}

.logo{
    float: left;
    display: inline;
	width: 96%;
	max-width: 64px;
	overflow: hidden;
	background-color: #fff;
	text-align: center;
}

.logo img{
	width: 96%;
}

.header .txt{
	width: auto;
	max-width: 60%;
	float: left;
    display: inline;
    overflow: hidden;
	color: #fff;
	border-color: #666;
	padding: 16px 2%;
}

.header a{
	text-decoration: none;
}

input[type=submit]{
    background-color: #0BBBEF;
    color: #fff;
    border-color: #0BBBEF;
    padding: 8px; 
}

input[type=submit]:hover{
    background-color: #94D8EC;
    border-color: #94D8EC;
}

</style>
</head>
<body>


<div class="app">
<div style="margin-bottom: 32px; text-align: left; font-size: 12px !important;" class="header">
    <a style="font-size: 12px !important;" href="<?php echo $GLOBALS['url_current']; ?>" class="logo"><img src="<?php echo $GLOBALS['template_path']; ?>img/logo.png" alt="<?php echo $_SERVER['HTTP_HOST']; ?>" /></a>
</div>
<?php 
echo $GLOBALS['output'];
?>

</div>

</body>
</html>