<?php require_once('../Connections/baglan.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../index.html";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "giris.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE ayar SET kullaniciadi=%s, sifre=%s, footersol=%s, footersag=%s, footerlink=%s, sitebasligi=%s, siteadi=%s WHERE id=%s",
                       GetSQLValueString($_POST['kullaniciadi'], "text"),
                       GetSQLValueString(md5($_POST['sifre']), "text"),
                       GetSQLValueString($_POST['footersol'], "text"),
                       GetSQLValueString($_POST['footersag'], "text"),
                       GetSQLValueString($_POST['footerlink'], "text"),
                       GetSQLValueString($_POST['sitebasligi'], "text"),
                       GetSQLValueString($_POST['siteadi'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_baglan, $baglan);
  $Result1 = mysql_query($updateSQL, $baglan) or die(mysql_error());

  $updateGoTo = "ayarlar.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_baglan, $baglan);
$query_ayar = "SELECT * FROM ayar";
$ayar = mysql_query($query_ayar, $baglan) or die(mysql_error());
$row_ayar = mysql_fetch_assoc($ayar);
$totalRows_ayar = mysql_num_rows($ayar);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/wordpress.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $row_ayar['siteadi']; ?></title>
<!-- InstanceEndEditable -->
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/kaydet.css">
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
					<li class="active"><a href="index.php">Anasayfa</a></li>
                    <li><a href="ayarlar.php">Ayarlar</a></li>
                    <li><a href="liste.php">Pvp Liste</a></li>
                  	<li><a href="pvp-ekle.php">Pvp Ekle</a></li>
                    <li><a href="yorumlar.php">Yorumlar</a></li>
                    <li><a href="../index.html">Site Anasayfa</a></li>
                    <li><a href="<?php echo $logoutAction ?>">Çıkış</a></li>
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
	<span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
	<input type="text" name="kullaniciadi" value="<?php echo htmlentities($row_ayar['kullaniciadi'], ENT_COMPAT, 'utf-8'); ?>" class="form-control" aria-describedby="sizing-addon2" required>
	</div></br>
	<div class="input-group">
	<span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span> Şifre</span>
	<input type="text" name="sifre" value="" class="form-control" aria-describedby="sizing-addon2" >
	</div></br>
	<div class="input-group">
	<span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-copyright-mark" aria-hidden="true"></span> Copright</span>
	<input type="text" name="footersol" value="<?php echo htmlentities($row_ayar['footersol'], ENT_COMPAT, 'utf-8'); ?>" class="form-control" required>
	</div></br>
	<div class="input-group">
	<span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> Footer Link</span>
	<input type="text" name="footerlink" value="<?php echo htmlentities($row_ayar['footerlink'], ENT_COMPAT, 'utf-8'); ?>" class="form-control" required>
	</div></br>
	<div class="input-group">
	<span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Site Başlığı</span>
	<input type="text" name="sitebasligi" value="<?php echo htmlentities($row_ayar['sitebasligi'], ENT_COMPAT, 'utf-8'); ?>" class="form-control" required>
	</div></br>
	<div class="input-group">
	<span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> Site Adı</span>
	<input type="text" name="siteadi" value="<?php echo htmlentities($row_ayar['siteadi'], ENT_COMPAT, 'utf-8'); ?>" class="form-control" required>
	</div></br>
	<div class="input-group progress-demo">
	<button class="btn btn-primary ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">Ayarları Kaydet</span></button>
	</div></br>
	<div class="input-group">
	</div>
	<input type="hidden" name="MM_update" value="form1" />
	<input type="hidden" name="id" value="<?php echo $row_ayar['id']; ?>" />
	</form></br></br>
	</div>
</div>
	<!-- InstanceEndEditable -->
    </div>
    <div class="container">
          <div class="alert alert-warning alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          Oluşabilecek her hata için <a href="http://okandiyebiri.com/pvp-listesi-scripti/"><strong>destek</strong></a> sitesini ziyaret edin.
          </div>
          </br></br>
</div>
     <div class="navbar navbar-default navbar-fixed-bottom">
         <div class="container">
             <a href="<?php echo $row_ayar['footerlink']; ?>" class="navbar-btn btn-primary btn"><?php echo $row_ayar['footersol']; ?> - <?php echo date("o"); ?></a>
         </div>
     </div>
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/bekleyin.js"></script>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($ayar);
?>
