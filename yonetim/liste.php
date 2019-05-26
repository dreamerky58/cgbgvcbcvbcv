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

mysql_select_db($database_baglan, $baglan);
$query_ayar = "SELECT * FROM ayar";
$ayar = mysql_query($query_ayar, $baglan) or die(mysql_error());
$row_ayar = mysql_fetch_assoc($ayar);
$totalRows_ayar = mysql_num_rows($ayar);

mysql_select_db($database_baglan, $baglan);
$query_pvpliste = "SELECT * FROM pvplist ORDER BY id DESC";
$pvpliste = mysql_query($query_pvpliste, $baglan) or die(mysql_error());
$row_pvpliste = mysql_fetch_assoc($pvpliste);
$totalRows_pvpliste = mysql_num_rows($pvpliste);
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
      <div class="panel-heading">Pvp Listesi</div>
  <table class="table">
  <tr>
        <td><b>İcon</b></td>
        <td><b>Başlık</b></td>
        <td><b>Durum</b></td>
        <td><b>Link</b></td>
        <td><b>Server Tipi</b></td>
        <td><b>Uridium</b></td>
        <td><b>Yayınlanma Durumu</b></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php do { ?>
        <tr>
		  <td><img src="http://www.google.com/s2/favicons?domain=<?php echo $row_pvpliste['link']; ?>" alt="favicon" /></td>
		  <!--<td><img src="<?php echo $row_pvpliste['link']; ?>/favicon.ico" alt="favicon" /></td>-->
          <td><?php echo $row_pvpliste['baslik']; ?></td>
          <td><?php echo $row_pvpliste['durum']; ?></td>
          <td><a href="<?php echo $row_pvpliste['link']; ?>"><?php echo $row_pvpliste['link']; ?></a></td>
          <td><?php echo $row_pvpliste['servertipi']; ?></td>
          <td><?php echo $row_pvpliste['uridium']; ?></td>
          <td><?php echo $row_pvpliste['yayinlanmadurumu']; ?></td>
          <td><a href="pvp-link-<?php echo $row_pvpliste['id']; ?>-duzenle.html"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
          <td><a href="liste-sil.php?id=<?php echo $row_pvpliste['id']; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
        </tr>
        <?php } while ($row_pvpliste = mysql_fetch_assoc($pvpliste)); ?>
  </table>
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
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($ayar);

mysql_free_result($pvpliste);
?>
