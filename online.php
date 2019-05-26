<?php 
$sure=1; // Zaman Aşımı dakika cinsinden
$dosya="ip.txt"; // İp ve Sürenin Yazılacağı Dosya yazılabilinir olmalıdır
$ip=$_SERVER['REMOTE_ADDR']; // Gelenin ip adresi
if(!intval($ip)){ // Fake İp Adresine Karşı basit bir Önlem Alıyoruz
echo 'Geçersiz İp adresi';
exit();
}
 
if (!is_writable($dosya)) { // Dosya Yazılabilinirmi diye Check ediyoruz
echo $dosya."'sı Yazılabilinir Deği";
exit();
} $simdi=time(); // Şimdiki tarih
$desen='|ip:(.*)-zaman:(.*)\\n|siU'; // Desenimiz
$ac=fopen($dosya,"r");// Dosyayı Okumak İçin açıyoruz
@$kaynak = fread($ac, filesize($dosya)); // Dosya İçeriğini Alıyoruz
fclose($ac); // Dosyayı Kapatıyoruz
preg_match_all($desen,$kaynak,$cik); // Kaynağı parçalıyoruz
$say=count($cik[1])-1; // Kaç tane ip olduğunu buluyoruz -1 for döngüsünde $i=0 dediğimiz için
for($i=0;$i<=$say;$i++){// For Döngüsü Başlıyor
$kip=$cik[1][$i]; // Eleman İp adresi 
$ksure=$cik[2][$i]; // İp adresinin yazılış süresi
$degerz="ip:".$kip."-zaman:".$ksure."\n"; // Yazılacak olan metin
$fark=$simdi-$ksure; // Şimdi Zaman İle İp Adresinin yazılışı arasındaki süreyi buluyoruz
$gecis=$sure * 60;// Yukarıdaki zaman aşımını saniye cinsinden hesaplıyoruz
if($fark > $gecis ){
$kaynak=str_replace($degerz,"",$kaynak); // Eğer Fark zaman aşımından büyükse dosyamızdan veriyi siliyoruz.
}
}// For Döngüsü Bitiyor
if(!strpos($kaynak,$ip)){ // Kaynak'ta eğer Sayfayı Görüntüleyenin ip adresi yoksa
$deger="ip:".$ip."-zaman:".$simdi."\n"; // Yazılacak Metin
$kaynak=$kaynak.$deger; // Kaynağ'a ekliyoruz
}
$t=fopen($dosya,"w"); // Dosyamızı Yazabilecek şekilde açıyoruz içindekiler siliniyor.
fwrite($t,$kaynak); // Dosyamıza kaynağı yazıyoruz
fclose($t); // Dosyayı kapatıyoruz
preg_match_all($desen,$kaynak,$sonuc); // Kaynağı Parçalıyoruz
$online=count($sonuc[1]) // Kaç adet ip adresi olduğunu buluyoruz
?>
 
<? echo $online; ?>