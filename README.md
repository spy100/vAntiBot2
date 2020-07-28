# vAntiBot2
php human verification / antibot v2

vAntibot v2 is based on human recognition 

You have to identify if in both pictures is the same person 

Demo:https://www.youtube.com/watch?v=NHfn_Isr0-I

How it works (The principle) : The visitor has to play a game of "Head & Tails" , to see if he has won the visitor has to see if the 2 people from the 2 images are the same person ... if he proves that he knows the 2 people are the same person then he is human .if not he is bot.


Installation Guide:

$strongcipherkey = "e(J}xYpT7ecp)Yp8cSxG^}_WWcf<ag!YmEfgS{?xVj5Gf`Vy_qua`BAzpfTB6P"; //replace chipher key with your own key ,make sure it is strong it's like a passw


Replace e(J}xYpT7ecp)Yp8cSxG^}_WWcf<ag!YmEfgS{?xVj5Gf`Vy_qua`BAzpfTB6P with your own strong cipher key.



Create a database or a table in your database

CREATE TABLE vAntibot (
      id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      vhash VARCHAR(255) NOT NULL
);


Edit in conn.php
$link = mysqli_connect("localhost", "user", "passw", "databasename"); 
