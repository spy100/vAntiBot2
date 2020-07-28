
<?php
/*
 Copyright © 2020 Alin Tanase All rights reserved 
 If you need help with implementation contact me on telegram @Alin100 or email wdesginer2010[dot]gmail.com
*/
include 'conn.php';
?>
<!DOCTYPE html>
<html>
<head>
<style>
html,body{
  background:white;
  margin:0;
  padding:0;
}

* {
  box-sizing: border-box;
}


@font-face {
  font-family: "Roboto Condensed";
  src: url("fonts/roboto-condensedregular.eot"); 
  src: url("fonts/roboto-condensedregular.eot?#iefix") format("embedded-opentype"), 
    url("fonts/roboto-condensedregular.otf") format("opentype"), 
    url("fonts/roboto-condensedregular.svg") format("svg"), 
    url("fonts/roboto-condensedregular.ttf") format("truetype"), 
    url("fonts/roboto-condensedregular.woff") format("woff"), 
    url("fonts/roboto-condensedregular.woff2") format("woff2"); 
  font-weight: normal;
  font-style: normal;
}

label{
   font-family: 'Roboto Condensed', sans-serif;
}

.bin{
   font-size:14px;
   background:#f4f4f4;
   display:block;
   margin:auto;
   margin-top:20px;
   text-align:center;
   border:1px solid #ccc;
   padding-bottom:20px;
   padding-top:20px;
   width:420px;
}

.vidbin{
  display:block;
  width:100%;
  text-align:center;
  font-size:0px;
}

.vid{
   position:relative;
   margin:0;
   padding:0;
   display:inline-block;
}
.honey{
  display:none;
}


</style>
</head>
<body>



<div class="bin" >
<form action="index.php" method="POST">
<label>Press Unlock<br>When You See Same Person In Both Pictures ,Press I Am Human:</label>
<br><br>
<input type="submit" name="submit" value="Unlock" />
</form>

<?php
$strongcipherkey = "e(J}xYpT7ecp)Yp8cSxG^}_WWcf<ag!YmEfgS{?xVj5Gf`Vy_qua`BAzpfTB6P"; //replace chipher key with your own key ,make sure it is strong it's like a passw

//replace this with a database of famous people
$famouspeople = array("Gal Gadot","Julia Roberts","Scarlett Johansson","Melissa McCarthy","Margot Robbie","Halle Berry","Meryl Streep","Emma Stone","Mila Kunis","Charlize Theron","Jennifer Lawrence","Jenifer Aniston","Sandra Bullock","Nicole Kidman","Natalie Portman","Paul Rudd","Bradley Cooper","Brad Pitt","Dustin Hoffman","Angelina Jolie","Mike Tyson","Will Smith","Leonardo DiCaprio","Johnny Depp","George Clooney","Tom Cruise","Harrison Ford","Al Pacino");
//replace this with a database of famous people

$totalpeople = count($famouspeople);
$randomp1 = random_int(0, $totalpeople);
$randomp2 = random_int(0, $totalpeople);
$randomp3 = random_int(0, $totalpeople);
$randomp4 = random_int(0, $totalpeople);

//so we make it easy for humans, that's why we put only 4 people here 
$pp = array($famouspeople[$randomp1],$famouspeople[$randomp2],$famouspeople[$randomp3],$famouspeople[$randomp4]);

$length = 1;    
$z = substr(str_shuffle('012'),1,$length);
$length = 1;    
$a = substr(str_shuffle('012'),1,$length);

//web scraping google ain't gona like this solution 
$search_query = $pp[$z];
$search_query = urlencode( $search_query );
$html = file_get_contents( "https://www.google.com/search?q=$search_query&tbm=isch" );
$htmlDom = new DOMDocument;
@$htmlDom->loadHTML($html);
$imageTags = $htmlDom->getElementsByTagName('img');
$extractedImages = array();
foreach($imageTags as $imageTag){
  $imgSrc = $imageTag->getAttribute('src');
  $extractedImages[] = array($imgSrc);
}
unset($extractedImages[0]);

//web scraping google ain't gona like this solution 
$search_query1 = $pp[$a];
$search_query1 = urlencode( $search_query1 );
$html1 = file_get_contents( "https://www.google.com/search?q=$search_query1&tbm=isch" );
$htmlDom1 = new DOMDocument;
@$htmlDom1->loadHTML($html1);
$imageTags1 = $htmlDom1->getElementsByTagName('img');
$extractedImages1 = array();
foreach($imageTags1 as $imageTag1){
  $imgSrc1 = $imageTag1->getAttribute('src');
  $extractedImages1[] = array($imgSrc1);
}
unset($extractedImages1[0]);


echo "<br><div class=\"vidbin\" >";

foreach($extractedImages[random_int(1, 3)] as $img){
  echo "<div class=\"vid\" ><img src=\"".$img."\" alt=\"\" width=\"180px\" height=\"200px\" /></div>";
}

foreach($extractedImages1[random_int(4, 7)] as $img1){
  echo "<div class=\"vid\" ><img src=\"".$img1."\" alt=\"\" width=\"180px\" height=\"200px\" /></div>";
}
echo "</div>";
 









if($z == $a){
  //here we encrypt the good key so we can send it via post with the hash start 
  $key = bin2hex(random_bytes(64));
  $u = password_hash($key, PASSWORD_BCRYPT);
}else{
  //here the fake key
  $key1 = bin2hex(random_bytes(64));
  $u = password_hash($key1, PASSWORD_BCRYPT);
  $key = bin2hex(random_bytes(64));
}


$plaintext = $key;
$ckey = substr(hash('sha256', $strongcipherkey, true), 0, 32);
$cipher = 'aes-256-gcm';
$iv_len = openssl_cipher_iv_length($cipher);
$tag_length = 16;
$iv = openssl_random_pseudo_bytes($iv_len);
$tag = "";
$ciphertext = openssl_encrypt($plaintext, $cipher, $ckey, OPENSSL_RAW_DATA, $iv, $tag, "", $tag_length);
$encryptedtxt = base64_encode($iv.$tag.$ciphertext);



$sql = "SELECT vkey,vhash FROM vAntibot";
if($result = mysqli_query($link, $sql)){
  if(mysqli_num_rows($result) > 0){ 

    
    $sql2 = "UPDATE vAntibot SET vkey = '$encryptedtxt',vhash='$u' WHERE id = '1'";
    if(mysqli_query($link, $sql2)){
    }else{ 
      echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }  

  }else{

    $sql = "INSERT INTO vAntibot (vkey,vhash) VALUES ('$encryptedtxt','$u')";
    if (mysqli_query($link, $sql)) {
    } else {
      echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }

  }
}else{
  echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}



    echo "<br><div class=\"ihuman\" >
    <form action=\"index.php\" method=\"post\" ><input class=\"honey\" type=\"text\" name=\"honey\" value=\"\" >
     <input type=\"submit\" name=\"submitunlock\" value=\"I am Human\" />
     </form></div>";

?>




<?php 
  if (isset($_POST['submitunlock'])) {

    if(!empty($_POST['honey'])){
      header("Location: http://google.com");
      exit;
    }

    //tiny fix to do here 

    $sql2 = "SELECT vkey,vhash FROM vAntibot WHERE id ='1'";
    $myarr = array();
   if($result2 = mysqli_query($link, $sql2)){
     if(mysqli_num_rows($result2) > 0){ 
       while ($row2 = mysqli_fetch_array($result2)){
         $vkey = $row2['vkey'];
         $vhash = $row2['vhash'];


         $encrypted = base64_decode($vkey);
         $ckey = substr(hash('sha256', $strongcipherkey, true), 0, 32);
         $cipher = 'aes-256-gcm';
         $iv_len = openssl_cipher_iv_length($cipher);
         $tag_length = 16;
         $iv = substr($encrypted, 0, $iv_len);
         $tag = substr($encrypted, $iv_len, $tag_length);
         $ciphertext = substr($encrypted, $iv_len + $tag_length);
         $decrypted = openssl_decrypt($ciphertext, $cipher, $ckey, OPENSSL_RAW_DATA, $iv, $tag);
     
         
         if(password_verify($decrypted, $vhash)){
           echo "<br><label>You are human</label>";
         }else{
           echo "<br><label>You are a bot</label>";
           //Redirect visitor or ban him,or log how many failed attempts...
         }



       }
     }else{
       echo "";
     }
    }else{
      echo "ERROR: Could not able to execute $sql2. " . mysqli_error($link);
    }

  }
?>
</div>

</body>
</html>
