
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
  if (isset($_POST['submitunlock'])) {

    if(!empty($_POST['honey'])){
      header("Location: http://google.com");
      exit;
    }

    $zkey = $_POST['key'];

    $sql2 = "SELECT vhash FROM vAntibot WHERE id ='1'";
   if($result2 = mysqli_query($link, $sql2)){
     if(mysqli_num_rows($result2) > 0){ 
       while ($row2 = mysqli_fetch_array($result2)){
         $vhash = $row2['vhash'];
         if(password_verify($zkey, $vhash)){
           $msg = "<br><label>You are human</label>";
         }else{
           $msg = "<br><label>You are a bot</label>";
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

//replace this with a database of famous people
$famouspeople = array("Gal Gadot","Julia Roberts","Scarlett Johansson","Melissa McCarthy","Margot Robbie","Halle Berry","Meryl Streep","Emma Stone","Mila Kunis","Charlize Theron","Jennifer Lawrence","Jenifer Aniston","Sandra Bullock","Nicole Kidman","Natalie Portman","Paul Rudd","Bradley Cooper","Brad Pitt","Dustin Hoffman","Angelina Jolie","Mike Tyson","Will Smith","Leonardo DiCaprio","Johnny Depp","George Clooney","Tom Cruise","Harrison Ford","Al Pacino");
//replace this with a database of famous people

$totalpeople = count($famouspeople);
$randomp1 = random_int(0, $totalpeople-1);
$randomp2 = random_int(0, $totalpeople-1);
$randomp3 = random_int(0, $totalpeople-1);
$randomp4 = random_int(0, $totalpeople-1);

//so we make it easy for humans, that's why we put only 4 people here 
$pp = array($famouspeople[$randomp1],$famouspeople[$randomp2],$famouspeople[$randomp3],$famouspeople[$randomp4]);

$length = 1;    
$z = substr(str_shuffle('012'),1,$length);
$length = 1;    
$a = substr(str_shuffle('012'),1,$length);



function Scraping($search_query,$random){
  $search_query = urlencode( $search_query );
  $context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
  $html = file_get_contents( "https://www.google.com/search?q=$search_query&tbm=isch",false,$context);
  $htmlDom = new DOMDocument;
  @$htmlDom->loadHTML($html);
  $imageTags = $htmlDom->getElementsByTagName('img');
  $extractedImages = array();
  foreach($imageTags as $imageTag){
    $imgSrc = $imageTag->getAttribute('src');
    $extractedImages[] = array($imgSrc);
  }
  unset($extractedImages[0]);
  return $extractedImages[$random];
}




echo "<br><div class=\"vidbin\" >";
foreach(Scraping($pp[$z],random_int(1, 3)) as $img){
  echo "<div class=\"vid\" ><img src=\"".$img."\" alt=\"\" width=\"180px\" height=\"200px\" /></div>";
}

foreach(Scraping($pp[$a],random_int(4, 7)) as $img1){
  echo "<div class=\"vid\" ><img src=\"".$img1."\" alt=\"\" width=\"180px\" height=\"200px\" /></div>";
}
echo "</div>";
 


function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

if($z == $a){
  //here we encrypt the good key so we can send it via post 
  $key = bin2hex(random_bytes(64));
  $u = password_hash($key, PASSWORD_BCRYPT);
}else{
  //here the fake key
  $key = bin2hex(random_bytes(64));
  $u = password_hash(generateRandomString(), PASSWORD_BCRYPT);
}


$sql = "SELECT vhash FROM vAntibot";
if($result = mysqli_query($link, $sql)){
  if(mysqli_num_rows($result) > 0){     
    $sql2 = "UPDATE vAntibot SET vhash='$u' WHERE id = '1'";
    if(mysqli_query($link, $sql2)){
    }else{ 
      echo "ERROR: Could not able to execute $sql2. " . mysqli_error($link);
    }  
  }else{

    $sql = "INSERT INTO vAntibot (vhash) VALUES ('$u')";
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
    <input type=\"hidden\" name=\"key\" value=\"$key\" >
     <input type=\"submit\" name=\"submitunlock\" value=\"I am Human\" />
     </form></div>";


if(!empty($msg)){
echo $msg;
}


?>



</div>

</body>
</html>
