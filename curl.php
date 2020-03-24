<?php
require __DIR__ . '/vendor/autoload.php';
require 'dom/simple_html_dom.php';
include 'ZipMaster.php';

use \Curl\Curl;

function klasorsil($klasor){
    if (substr($klasor, -1) != '/')
        $klasor .= '/';
    if ($handle = opendir($klasor)) {
        while ($obj = readdir($handle)) {
            if ($obj!= '.' && $obj!= '..') {
                if (is_dir($klasor.$obj)) {
                    if (!klasorsil($klasor.$obj))
                        return false;
                }elseif (is_file($klasor.$obj)) {
                    if (!unlink($klasor.$obj))
                        return false;
                }
            }
        }
        closedir($handle);
        if (!@rmdir($klasor))
            return false;
        return true;
    }
    return false;
}

$dom = new simple_html_dom();
// Curl sınıfını çağırıyoruz ve ayarlarını yapıyoruz.
$curl = new Curl();

$cookie_file = 'cookie.txt';
$curl->setCookieJar($cookie_file);
$curl->setCookieFile($cookie_file);
$curl->setOpt(CURLOPT_NOBODY, false);
$curl->setOpt(CURLOPT_FOLLOWLOCATION, true);
$curl->setOpt(CURLOPT_RETURNTRANSFER, true);
$curl->setOpt(CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);

$curl->setHeader('X-Requested-With', 'XMLHttpRequest');

//echo $_POST['username'] . $_POST['isyeri_kod'] . $_POST['password'] . $_POST['isyeri_sifre']; die();


//Curl sınıfından get elemanını çağırıp ebildirge sitesinden oturum açıp işlem yapıyoruz.
$curl->post('https://ebildirge.sgk.gov.tr/EBildirgeV2/tahakkuk/tahakkukonaylanmisTahakkukDonemBilgileriniYukle.action', array(
    'username' => $_POST['username'],
    'isyeri_kod' => $_POST['isyeri_kod'],
    'password' => $_POST['password'],
    'isyeri_sifre' => $_POST['isyeri_sifre'],
    'isyeri_guvenlik' => '3VEW8'
));


if ($curl->error) {

    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
    exit();
} else {

    // Simple html dom sınıfıyla sayfadan bilgi alıyoruz.
    $html = $dom->load($curl->exec());

    $items = array();

    foreach ($html->find('select[id=tahakkukonaylanmisTahakkukDonemSecildi_hizmet_yil_ay_index]') as $item) {

        foreach ($item->find('option') as $i) {

            $items[] = $i->attr;

        }
    }


    unset($items[0]);
    unset($items[1]);

    array_values($items);

    $baslangic = array_key_first($items);
    $bitis = array_key_last($items);


    $curl->post('https://ebildirge.sgk.gov.tr/EBildirgeV2/tahakkuk/tahakkukonaylanmisTahakkukDonemSecildi.action', array(
        'hizmet_yil_ay_index' => $baslangic,
        'hizmet_yil_ay_index_bitis' => $bitis
    ));



    if ($curl->error) {

        echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";

    } else {

        $html = $dom->load($curl->response);

        $items = array();


        foreach ($html->find('table[border=1]') as $item) {

            //var_dump($item);die();
            foreach ($html->find('td[width=70px]') as $i) {

                foreach ($i->find('a') as $item) {
                    foreach ($item->attr as $i) {
                        $items[] = $i;
                    }
                }
            }
        }


        $degerler = array_search('cursor: pointer;', $items, true);
        $list = extract($items, EXTR_PREFIX_INVALID, 'deger');
        $yeni = array_unique($items);
        array_shift($yeni);

        $string = implode(" ", $yeni);

        $deger = array_unique(explode("return", $string));

        $string = implode($deger);

        $deger = array_unique(explode(",", $string));


        array_shift($deger);

        $string = implode($deger);

        $deger = array_unique(explode("islem", $string));

        $string = implode($deger);

        $deger = array_unique(explode("'", $string));
        array_shift($deger);
        $deger = array_unique($deger);


        unset($deger[1]);
        unset($deger[2]);
        unset($deger[3]);
        unset($deger[4]);
        unset($deger[5]);
        unset($deger[6]);
        unset($deger[7]);
        array_pop($deger);
        $deger = array_values($deger);

        //var_dump($deger); die();

        $yenideger = count($deger);

        $tarih = date('dmygi');

        $ad = $_POST['username'].$tarih;

        $klasor = mkdir($ad);

        chmod($ad, 0777);

        foreach ($deger as $item => $key) {

            $dosya = fopen($ad.'/'.$key.'.pdf', 'w');
            $curl->setOpt(CURLOPT_TIMEOUT, 0);
            $curl->setOpt(CURLOPT_FILE, $dosya);

            $curl->post('https://ebildirge.sgk.gov.tr/EBildirgeV2/tahakkuk/pdfGosterim.action', array(
                'tip' => 'tahakkukonayliFisHizmetPdf',
                'bildirgeRefNo' => $key,
                'download' => 'true',
                'hizmet_yil_ay_index' => 3,
                'hizmet_yil_ay_index_bitis' => $bitis
            ));

        }

        if ($curl->error){
            echo 'bir hata var.';
        }else{
            $zip = new ZipMaster\ZipMaster($ad.'.zip', $ad);
            $zip->archive();

            klasorsil($ad);

            echo 'işlem tamamdır.<br>';
            echo 'Dosyaları bu <a href="'.$ad.'.zip">linkten</a> indirebilirsiniz.';

        }
    }

}