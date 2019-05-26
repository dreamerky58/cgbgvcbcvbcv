<?php require_once('Connections/baglan.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_baglan, $baglan);
$query_ayar = "SELECT * FROM ayar";
$ayar = mysql_query($query_ayar, $baglan) or die(mysql_error());
$row_ayar = mysql_fetch_assoc($ayar);
$totalRows_ayar = mysql_num_rows($ayar);

$colname_linkekle = "-1";
if (isset($_GET['id'])) {
  $colname_linkekle = $_GET['id'];
}
mysql_select_db($database_baglan, $baglan);
$query_linkekle = sprintf("SELECT * FROM pvplist WHERE id = %s", GetSQLValueString($colname_linkekle, "int"));
$linkekle = mysql_query($query_linkekle, $baglan) or die(mysql_error());
$row_linkekle = mysql_fetch_assoc($linkekle);
$totalRows_linkekle = mysql_num_rows($linkekle);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/wordpress.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $row_ayar['siteadi']; ?></title>
<!-- InstanceEndEditable -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>
<body>
	<div class="navbar navbar-inverse navbar-static-top">
		<div class="container">
			<a href="#" class="navbar-brand"><?php echo $row_ayar['siteadi']; ?></a>
			<button class="navbar-toggle" data-toggle="collapse" data-target=".navbarSec">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div class="collapse navbar-collapse navbarSec">
				<ul class="nav navbar-nav navbar-right">
					<li class="active"><a href="index.html">Anasayfa</a></li>
                    <li><a href="link-ekle.html">Pvp Link Ekle</a></li>
				</ul>
			</div>
		</div>
	</div>
    <div class="container">
	<!-- InstanceBeginEditable name="icerik alanı" -->

	<!-- InstanceEndEditable -->
    </div>
    <div class="container">
          <div class="alert alert-warning alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          Teşekkürler..! Eklemiş olduğunuz link yönetici tarafından en kısa sürede onaylanacaktır. <a href="index.html"><strong>Anasayfaya</strong></a> dönebilirsiniz...
          </div>
          </br></br>
</div>
     <div class="navbar navbar-default navbar-fixed-bottom">
         <div class="container">
             <p class="navbar-text pull-left"><?php echo $row_ayar['footersol']; ?></p>
             <a href="<?php echo $row_ayar['footerlink']; ?>" class="navbar-btn btn-info btn pull-right"><?php echo $row_ayar['footersag']; ?></a>
         </div>
     </div>
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="js/bootstrap.js"></script>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($ayar);

mysql_free_result($linkekle);
?>
