<?php

echo "Ola mundo";

$nome = "Douglas";
$idade = 80;

echo "<br>";
echo $nome;
echo '<br> O seu nome é '. $nome;
echo "<br> O seu nome é $nome e idade: $idade";

if ($idade === 80){
    echo("<br>Verdade");
}else{
    echo("<br>Falso");
}

echo "<br><hr>";
echo "Contagem de 1 a 5 usando o laço for <br>";

for ($i=1; $i <= 5; $i++){
    echo "$i <hr>";
}

echo"<br> Contagem regressiva com While<br>";
$contador = 5;
while ($contador >=1){
    echo "$contador <hr>";
    $contador--;
}
?>