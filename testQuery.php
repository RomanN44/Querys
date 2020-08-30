<?php

namespace RomanN44\MySqlQuery;

require_once('MySqlQuery.php');


$query = (new MySqlQuery())
    ->select(array(
        'id_person',
        array("CONCAT(first_name, second_name)" => "full_name")
    ))
    ->from(array('some_table' => 'c'))
    ->andWhere(array(">", "id", 10))
    ->orWhere(array('>=', 'age', "18"))
    ->limit(200)
    ->offset(11);



echo $query->getRaw();
?>