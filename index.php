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

$currentPage = $_SERVER["PHP_SELF"];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO yorumlar (id, isim, email, yorum, tarih, durum) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id'], "int"),
                       GetSQLValueString($_POST['isim'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['yorum'], "text"),
                       GetSQLValueString($_POST['tarih'], "date"),
                       GetSQLValueString($_POST['durum'], "text"));

  mysql_select_db($database_baglan, $baglan);
  $Result1 = mysql_query($insertSQL, $baglan) or die(mysql_error());

  $insertGoTo = "index.html";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_baglan, $baglan);
$query_ayar = "SELECT footersol, footersag, footerlink, sitebasligi, siteadi FROM ayar";
$ayar = mysql_query($query_ayar, $baglan) or die(mysql_error());
$row_ayar = mysql_fetch_assoc($ayar);
$totalRows_ayar = mysql_num_rows($ayar);

$maxRows_yorumlar = 10;
$pageNum_yorumlar = 0;
if (isset($_GET['pageNum_yorumlar'])) {
  $pageNum_yorumlar = $_GET['pageNum_yorumlar'];
}
$startRow_yorumlar = $pageNum_yorumlar * $maxRows_yorumlar;

mysql_select_db($database_baglan, $baglan);
$query_yorumlar = "SELECT * FROM yorumlar WHERE durum = 'Yayında' ORDER BY id DESC";
$query_limit_yorumlar = sprintf("%s LIMIT %d, %d", $query_yorumlar, $startRow_yorumlar, $maxRows_yorumlar);
$yorumlar = mysql_query($query_limit_yorumlar, $baglan) or die(mysql_error());
$row_yorumlar = mysql_fetch_assoc($yorumlar);

if (isset($_GET['totalRows_yorumlar'])) {
  $totalRows_yorumlar = $_GET['totalRows_yorumlar'];
} else {
  $all_yorumlar = mysql_query($query_yorumlar);
  $totalRows_yorumlar = mysql_num_rows($all_yorumlar);
}
$totalPages_yorumlar = ceil($totalRows_yorumlar/$maxRows_yorumlar)-1;

$maxRows_pvpliste = 9;
$pageNum_pvpliste = 0;
if (isset($_GET['pageNum_pvpliste'])) {
  $pageNum_pvpliste = $_GET['pageNum_pvpliste'];
}
$startRow_pvpliste = $pageNum_pvpliste * $maxRows_pvpliste;

mysql_select_db($database_baglan, $baglan);
$query_pvpliste = "SELECT * FROM pvplist WHERE yayinlanmadurumu = 'Yayınlanmış' ORDER BY id DESC";
$query_limit_pvpliste = sprintf("%s LIMIT %d, %d", $query_pvpliste, $startRow_pvpliste, $maxRows_pvpliste);
$pvpliste = mysql_query($query_limit_pvpliste, $baglan) or die(mysql_error());
$row_pvpliste = mysql_fetch_assoc($pvpliste);

if (isset($_GET['totalRows_pvpliste'])) {
  $totalRows_pvpliste = $_GET['totalRows_pvpliste'];
} else {
  $all_pvpliste = mysql_query($query_pvpliste);
  $totalRows_pvpliste = mysql_num_rows($all_pvpliste);
}
$totalPages_pvpliste = ceil($totalRows_pvpliste/$maxRows_pvpliste)-1;

$queryString_pvpliste = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_pvpliste") == false && 
        stristr($param, "totalRows_pvpliste") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_pvpliste = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_pvpliste = sprintf("&totalRows_pvpliste=%d%s", $totalRows_pvpliste, $queryString_pvpliste);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/wordpress.dwt.php" codeOutsideHTMLIsLocked="false" -->

<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<link href="css/sosyal.css" rel="stylesheet">
<!-- include Cycle plugin -->

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $row_ayar['siteadi']; ?></title>
<!-- InstanceEndEditable -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/aramayap.css">
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>
<body>
	<div class="navbar navbar-inverse navbar-static-top">
		<div class="container">
			<a href="index.html" class="navbar-brand"><?php echo $row_ayar['siteadi']; ?></a>
			<button class="navbar-toggle" data-toggle="collapse" data-target=".navbarSec">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div class="collapse navbar-collapse  navbarSec">
				<ul class="nav navbar-nav navbar-right">
				<li class="active"><a href="index.html"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>  <?php echo $dil["anasayfa"];?></a></li>
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
	<div class="col-xs-12 col-md-8 table-responsive">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Pvp Server Listesi</h3>
						<div class="pull-right">
							<span class="clickable filter" data-toggle="tooltip" title="Filtre" data-container="body">Filtre
								<i class="glyphicon glyphicon-filter"></i>
							</span>
						</div>
					</div>
					<div class="panel-body">
						<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filtre" />
					</div>
					<table class="table table-hover" id="dev-table">
						<thead>
							<tr>
								<th><span class="glyphicon glyphicon-tasks" aria-hidden="true"></span></th>
								<th><?php echo $dil["baslik"];?></th>
								<th><?php echo $dil["git"];?></th>
								<th><?php echo $dil["durum"];?></th>
								<th><?php echo $dil["servertipi"];?></th>
								<th>Uridium</th>
							</tr><?php do { ?>
						</thead>
						<tbody>
							<tr>
								<td><img src="http://www.google.com/s2/favicons?domain=<?php echo $row_pvpliste['link']; ?>" alt="favicon" /></td>
								<td><?php echo $row_pvpliste['baslik']; ?></td>
								<td><span class="glyphicon glyphicon-link" aria-hidden="true"></span>&nbsp;<a target="_blank" href="<?php echo $row_pvpliste['link']; ?>" rel="nofollow"><?php echo $dil["git"];?></a></td>
								<td><?php
									$metin = $row_pvpliste['link'];
									$bul = array('http://');
									$degistir = array('');
									$degistirilmis = str_replace( $bul, $degistir, $metin );
									if (!$socket = @fsockopen("$degistirilmis", 80, $errno, $errstr, 2)) 
									{
									echo "<span style='font-family: 'Comic Sans MS';'><font color='dd3333'><span class='glyphicon glyphicon-thumbs-down' aria-hidden='true'></span>&nbsp;{$dil['kapali']}</font></font></span>"; 
									}
									else 
									{
									echo "<span style='font-family: 'Comic Sans MS';'><font color='01e426'><span class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></span>&nbsp;{$dil['acik']}</font></font></span>"; 
									fclose($socket); 
									}
									?>
								</td>
								<td><?php echo $row_pvpliste['servertipi']; ?></td>
								<td><?php echo $row_pvpliste['uridium']; ?></td>
							</tr><?php } while ($row_pvpliste = mysql_fetch_assoc($pvpliste)); ?>
						</tbody>
					</table>
					<nav>
						<ul class="pager">
						<?php if ($pageNum_pvpliste > 0) { // Show if not first page ?>
						<li><a href="<?php printf("%s?pageNum_pvpliste=%d%s", $currentPage, max(0, $pageNum_pvpliste - 1), $queryString_pvpliste); ?>"><?php echo $dil["onceki"];?></a></li>
						<?php } // Show if not first page ?>
						<?php if ($pageNum_pvpliste < $totalPages_pvpliste) { // Show if not last page ?>
						<li><a href="<?php printf("%s?pageNum_pvpliste=%d%s", $currentPage, min($totalPages_pvpliste, $pageNum_pvpliste + 1), $queryString_pvpliste); ?>"><?php echo $dil["sonraki"];?></a></li>
						<?php } // Show if not last page ?>
						</ul>
					</nav>
				</div>
			<!-- bitiş -->
	</div>
	<div class="row"><!-- Başlangıç -->
		<div class="col-xs-12 col-md-4">
		<button class="btn btn-primary" type="button">
		  <?php echo $dil["online"];?> <span class="badge"><?php include( 'online.php' ); ?></span>
		</button></br>
		<p><strong><?php echo $dil["yorumbirak"];?></strong></p>
			<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
				<div class="input-group">
					<span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
					<input type="text" name="isim" class="form-control" placeholder="<?php echo $dil["adsoyad"];?>" aria-describedby="sizing-addon2" required>
				</div></br>
				<div class="input-group">
					<span class="input-group-addon" id="sizing-addon2">@</span>
					<input type="text" name="email" class="form-control" placeholder="<?php echo $dil["email"];?>" aria-describedby="sizing-addon2" required>
				</div></br>
				<div class="input-group">
					<span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-import" aria-hidden="true"></span></span>
					<textarea name="yorum" class="form-control" placeholder="<?php echo $dil["yorum"];?>" required></textarea>
				</div></br>
				<div class="input-group">
					<button type="submit" class="btn btn-primary" class="form-control" aria-describedby="sizing-addon2" ><?php echo $dil["gonder"];?></button>
					<input type="hidden" name="id" value="" />
					<input type="hidden" name="tarih" value="" />
					<input type="hidden" name="durum" value="Taslak" />
					<input type="hidden" name="MM_insert" value="form1" />
				</div>
			</form></br>
		</div>
		
		<div class="row"><!-- Başlangıç -->
	<div class="col-md-4 col-xs-12">
		<div class="slideshow">
		<?php do { ?>
			<div class="media">
				<div class="media-left">
					<a href="#">
					<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
					</a>
				</div>
				<div class="media-body">
					<h4 class="media-heading"><?php echo $row_yorumlar['isim']; ?></h4>
					<h5 class="media-heading"><?php echo $row_yorumlar['tarih']; ?></h5>
					<?php echo $row_yorumlar['yorum']; ?>
				</div></br></br></br></br>
				</div>
					<hr />
					<?php } while ($row_yorumlar = mysql_fetch_assoc($yorumlar)); ?>
				</div>
			</div>
		</div>
	</div>
</br></br>
     <div class="navbar navbar-default navbar-fixed-bottom">
         <div class="container">
			 <a href="https://www.facebook.com/okandiyebirsayfasi"><i id="social" class="navbar-btn fa fa-facebook-square fa-3x social-fb pull-right"></i></a>
			 <a href="http://twitter.com/okan_diye_biri"><i id="social" class="navbar-btn fa fa-twitter-square fa-3x social-tw pull-right"></i></a>
			 <a href="https://plus.google.com/u/0/116106333253369257329"><i id="social" class="navbar-btn fa fa-google-plus-square fa-3x social-gp pull-right"></i></a>
			 <a href="mailto:okansibut@gmail.com"><i id="social" class="navbar-btn fa fa-envelope-square fa-3x social-em pull-right"></i></a>
             <a href="<?php echo $row_ayar['footerlink']; ?>" class="navbar-btn btn-primary btn"><?php echo $row_ayar['footersol']; ?> - <?php echo date("o"); ?></a>
         </div>
     </div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script type="text/javascript" src="js/malsup.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.slideshow').cycle({
		fx: 'fade' // choose your transition type, ex: fade, scrollUp, shuffle, etc...
	});
});
</script>
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/kayanyazi.js"></script>
<script src="js/aramayap.js"></script>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($ayar);

mysql_free_result($yorumlar);

mysql_free_result($pvpliste);
?>
