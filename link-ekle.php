<?php require_once('Connections/baglan.php'); ?>
<?php
	session_start();
	if (!$_SESSION["dil"]){
		require("dil/tr.php");
	}else {
		require("dil/".$_SESSION["dil"].".php");
	}
?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO pvplist (id, baslik, durum, link, servertipi, uridium, yayinlanmadurumu) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id'], "int"),
                       GetSQLValueString($_POST['baslik'], "text"),
                       GetSQLValueString($_POST['durum'], "text"),
                       GetSQLValueString($_POST['link'], "text"),
                       GetSQLValueString($_POST['servertipi'], "text"),
                       GetSQLValueString($_POST['uridium'], "text"),
                       GetSQLValueString($_POST['yayinlanmadurumu'], "text"));

  mysql_select_db($database_baglan, $baglan);
  $Result1 = mysql_query($insertSQL, $baglan) or die(mysql_error());

  $insertGoTo = "tesekkurler.html";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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
<link href="css/sosyal.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
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
					<li class="active"><a href="index.html"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> <?php echo $dil["anasayfa"];?></a></li>
                    <li><a href="link-ekle.html"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> <?php echo $dil["pvplinkekle"];?></a></li>
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-flag" aria-hidden="true"></span> <?php echo $dil["dilseciniz"];?> <span class="caret"></span></a>
					  <ul class="dropdown-menu" role="menu">
						<li><a href="dil.php?dil=tr"><img src="dil/img/tr.png" alt="<?php echo $dil["trdil"];?>"> <?php echo $dil["trdil"];?></a></li>
						<li><a href="dil.php?dil=en"><img src="dil/img/en.png" alt="<?php echo $dil["ingdil"];?>"> <?php echo $dil["ingdil"];?></a></li>
						<li><a href="dil.php?dil=de"><img src="dil/img/de.png" alt="<?php echo $dil["dedil"];?>"> <?php echo $dil["dedil"];?></a></li>
						<li><a href="dil.php?dil=ru"><img src="dil/img/ru.png" alt="<?php echo $dil["rudil"];?>"> <?php echo $dil["rudil"];?></a></li>
					  </ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
    <div class="container">
	<!-- InstanceBeginEditable name="icerik alanı" -->
<div class="row"><!-- Başlangıç -->
	<div class="col-md-4 col-md-offset-4">
	<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
	<div class="input-group">
	<span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span></span>
	<input type="text" name="baslik" class="form-control" placeholder="<?php echo $dil["sitebasligi"];?>" aria-describedby="sizing-addon2" required>
	</div></br>
	<div class="input-group">
	<span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> <?php echo $dil["durum"];?></span>
	<select name="durum" class="form-control" aria-describedby="sizing-addon2">
	<option value="Açık" <?php if (!(strcmp("Açık", ""))) {echo "SELECTED";} ?>><?php echo $dil["acik"];?></option>
	<option value="Kapalı" <?php if (!(strcmp("Kapalı", ""))) {echo "SELECTED";} ?>><?php echo $dil["kapali"];?></option>
	</select>
	</div></br>
	<div class="input-group">
	<span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></span>
	<input type="text" name="link" class="form-control" placeholder="http://" required>
	</div></br>
	<div class="input-group">
	<span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span> <?php echo $dil["servertipi"];?></span>
	<select name="servertipi" class="form-control" aria-describedby="sizing-addon2">
	<option value="Kasılmalık" <?php if (!(strcmp("Kasılmalık", ""))) {echo "SELECTED";} ?>><?php echo $dil["kasilmalik"];?></option>
	<option value="VSlik" <?php if (!(strcmp("VSlik", ""))) {echo "SELECTED";} ?>><?php echo $dil["vslik"];?></option>
	<option value="Bilinmiyor" <?php if (!(strcmp("Bilinmiyor", ""))) {echo "SELECTED";} ?>><?php echo $dil["bilinmiyor"];?></option>
	</select>
	</div></br>
	<div class="input-group">
	<span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-transfer" aria-hidden="true"></span> Uridium</span>
	<input type="text" name="uridium" class="form-control" placeholder="<?php echo $dil["rakamsaldeger"];?>" required>
	</div></br>
	<div class="input-group">
	<input type="submit" value="<?php echo $dil["gonder"];?>" class="form-control">
	</div></br>
	<div class="input-group">
	</div>
	<input type="hidden" name="yayinlanmadurumu" value="Taslak" />
	<input type="hidden" name="MM_insert" value="form1" />
	</form></br></br>
	</div>
</div>
	<!-- InstanceEndEditable -->
    </div>
     <div class="navbar navbar-default navbar-fixed-bottom">
         <div class="container">
			 <a href="https://www.facebook.com/okandiyebirsayfasi"><i id="social" class="navbar-btn fa fa-facebook-square fa-3x social-fb pull-right"></i></a>
			 <a href="http://twitter.com/okan_diye_biri"><i id="social" class="navbar-btn fa fa-twitter-square fa-3x social-tw pull-right"></i></a>
			 <a href="https://plus.google.com/u/0/116106333253369257329"><i id="social" class="navbar-btn fa fa-google-plus-square fa-3x social-gp pull-right"></i></a>
			 <a href="mailto:okansibut@gmail.com"><i id="social" class="navbar-btn fa fa-envelope-square fa-3x social-em pull-right"></i></a>
             <a href="<?php echo $row_ayar['footerlink']; ?>" class="navbar-btn btn-primary btn"><?php echo $row_ayar['footersol']; ?> - <?php echo date("o"); ?></a>
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
