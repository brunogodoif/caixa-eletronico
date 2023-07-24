<?php

function stringToJson($stringJson)
{
    $stringToJson = json_decode($stringJson, true);
    if ($stringToJson === null || json_last_error() !== JSON_ERROR_NONE) {
        echo "Erro ao decodificar o JSON. Verifique o formato e tente novamente.\n";
        return false;
    }
    return $stringToJson;
}

function jsonToReponse($object)
{
    return json_encode($object, JSON_PRETTY_PRINT);
}

?>