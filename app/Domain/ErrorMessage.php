<?php

namespace App\Domain;
enum ErrorMessage: string
{
    case ATM_IN_USE = "caixa-em-uso";
    case ATM_UNAVAILABLE = "caixa-indisponivel";
    case UNAVAILABLE_VALUE = "valor-indisponivel";
    case WITHDRAWAL_DUPLICATE = "saque-duplicado";
    case INVALID_DATE = "data-operacao-fora-ordem-cronologica";
}