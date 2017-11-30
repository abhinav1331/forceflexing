<?php 
/*error_reporting(0);
ini_set('display_errors', 0);*/
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>ForceFlexing | Welcome</title>
<!-- Favicon -->
<link rel="shortcut icon" href="<?php echo BASE_URL; ?>/static/images/favicon.ico" type="image/x-icon">
<!-- Google Fonts used -->
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,700,800,300' rel='stylesheet' type='text/css'>
<!-- font-family: 'Open Sans', sans-serif; -->

<!-- Bootstrap -->
<link href="<?php  echo BASE_URL;?>static/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom/Font Awesome/Animate.css -->
<link href="<?php  echo BASE_URL;?>static/css/toastr.css" rel="stylesheet" type="text/css" />
<link href="<?php  echo BASE_URL;?>static/css/jquery.timepicker.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<?php 

$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

//load the calender script
if (strpos($url,'contractor_profile/') !== false) 
{
	?>
	<link href="<?php  echo BASE_URL;?>static/css/jquery.datepicker.css" rel="stylesheet">
	<?php
}
//load emojis script
if (strpos($url,'inbox') !== false) 
{
	?>
	<link href="<?php  echo BASE_URL;?>static/emojis/css/jquery.emojipicker.css" rel="stylesheet">
	<link href="<?php  echo BASE_URL;?>static/emojis/css/jquery.emojipicker.tw.css" rel="stylesheet">
	<?php
} 

?>
<link href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php  echo BASE_URL;?>static/css/on-off-switch.css" rel="stylesheet">
<link href="<?php  echo BASE_URL;?>static/css/style.css" rel="stylesheet">
<link href="<?php  echo BASE_URL;?>static/css/main.css" rel="stylesheet">
<script src="<?php  echo BASE_URL;?>static/js/canvasjs.min.js"></script>



<?php  if(isset($additional)){	echo $additional;	} ?>

<script>
var base_url='<?php echo BASE_URL;?>';
</script>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>