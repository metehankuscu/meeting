<?php

$url = 'https://www.cimri.com/karaca';
$rules = stream_context_create(
    array(
        "http" => array(
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
        )
    )
);
$source =  file_get_contents($url, false, $rules);
preg_match_all("/<a class=\"s1tg1k8o-17 eRervV\" href=\"(.*?)\" title=\"(.*?)\">(.*?)<\/a>/i", $source, $headers);

//Kategoriler ve linkleri diziye aktarılıyor
$categoryLinks = $headers[1];
$categories = $headers[2];
//Kategoriler ve linkleri diziye aktarılıyor



//Linkler temizleniyor
for ($i = 0; $i < count($categoryLinks); $i++) {
    $categoryLinks[$i] = str_replace('amp;', '', $categoryLinks[$i]);
}
//Linkler temizleniyor


//Linklere gidiliyor ve sayfa numaraları alınıyor
$pageNumber = array();
$mainLink = "https://www.cimri.com/";
for ($i = 0; $i < count($categoryLinks); $i++) {
    $pages =  file_get_contents($mainLink . $categoryLinks[$i], false, $rules);
    preg_match_all("/<a class=\"s1pk8cwy-2 dSbtQw\" href=\"(.*?)\">(.*?)<\/a>/i", $pages, $pageNumbers);
    if (count($pageNumbers[2]) > 0) {
        $lastElement = count($pageNumbers[2]) - 1;
        array_push($pageNumber, $pageNumbers[2][$lastElement]);
    } else {
        array_push($pageNumber, 1);
    }
}
//Linklere gidiliyor ve sayfa numaraları alınıyor


// Linkler filtreleniyor
for ($q = 0; $q < count($categoryLinks); $q++) {
    $categoryLinks[$q] = str_replace('karaca?page=1', '', $categoryLinks[$q]);
}
// Linkler filtreleniyor



// Ürün bilgileri çekiliyor
$names = array();
$prices = array();
$productCategory = array();
$allProducts = array();


for ($m = 0; $m < count($pageNumber); $m++) {
    for ($j = 1; $j <= $pageNumber[$m]; $j++) {
        $source =  file_get_contents($mainLink . "karaca?page=$j" . $categoryLinks[$m], false, $rules);

        preg_match_all("/<div class=\"s179fpnv-3 fueHEf\">(.*?)<\/div>/i",$source,$productNames);
        array_push($names,$productNames[1]);

        for ($p=0; $p < count($productNames[1]); $p++) { 
            array_push($productCategory,$categories[$m]);
        }

        preg_match_all("/<div class=\"tag\">(.*?)<\/div>(.*?)<span class=\"s6nihyy-0 cNqkyF\">/i", $source, $productPrice);
        array_push($prices,$productPrice[2]);


    }
}
// Ürün bilgileri çekiliyor




// Bilgiler tek bir dizide toplanıyor
$productCount = 0;
for ($i=0; $i < count($names); $i++) { 
    for ($k=0; $k < count($names[$i]); $k++) {
        $productCount++;
        array_push($allProducts,[
            "id" => $productCount,
            "name" => $names[$i][$k],
            "price" => $prices[$i][$k],
            "category" =>  str_replace('amp;','',$productCategory[count($allProducts)]),
        ]);
    }
}
// Bilgiler tek bir dizide toplanıyor



// Ürünler xml formatında dışarı içeri aktarılıyor ve dosya oluşturuluyor
$xml = new SimpleXMLElement("<xml/>");

$products = $xml->addChild("products");

foreach ($allProducts as $row) {
    $productFeatures = $products->addChild("product");

    $productFeatures->addChild("id",htmlspecialchars($row['id']));
    $productFeatures->addChild("name",htmlspecialchars($row['name']));
    $productFeatures->addChild("price",str_replace(' TL','',htmlspecialchars($row['price'])));
    $productFeatures->addChild("category",htmlspecialchars($row['category']));
}
fopen("products.xml","w") or die("Dosya oluşturulamadı");
file_put_contents("products.xml",$xml->asXML());
// Ürünler xml formatında dışarı içeri aktarılıyor ve dosya oluşturuluyor


?>