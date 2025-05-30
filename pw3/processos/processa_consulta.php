<?php
/*
$db = new mysqli('127.0.0.1:3306','root','','db_agenda');
if ($db->connect_error > 0){
die ('Erro ao tentar conectar o banco de dados');
}
else {
$sql = "select * from contato";
}

if (!$result=$db->query($sql)){
die ('Ha um erro de execução na query['.$db->error.']');
}
else
{
while($row = $result->fetch_assoc()){
echo "$row[nome] $row[endereco] $row[telefone] $row[email].<br>";
// fetch_assoc=Retorna um array associativo representando a linha buscada
}
}






























