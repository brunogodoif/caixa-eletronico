<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once("app/utils/applicatinMenu.php");
require_once("app/utils/jsonUtil.php");

use App\Domain\Atm;
use App\Domain\Exceptions\InUseATMException;
use App\Domain\Exceptions\UnavailableATMException;
use App\Domain\Exceptions\UnavailableValueATMException;
use App\Domain\Exceptions\WithdrawalDuplicateATMException;
use App\Domain\Operation;
use App\ResponseTo\AtmTo;
use App\ResponseTo\OperationTo;


echo "Bem-vindo!\n";
$atm = new Atm();

while (true) {

    showMenu();
    $option = readline("Opção Selecionada: ");
    echo "...\n";

    switch ($option) {
        case 1:
            echo "--> Realizar Abastacimento <-- \n";

            $jsonStringSupply = readline("Infome os data do abastecimento: ");
            $dataSupply = stringToJson($jsonStringSupply);

            if (!$dataSupply) {
                break;
            }

            try {

                $atm->supply(
                    $dataSupply["caixa"]["caixaDisponivel"],
                    $dataSupply["caixa"]["notas"]["notasDez"],
                    $dataSupply["caixa"]["notas"]["notasVinte"],
                    $dataSupply["caixa"]["notas"]["notasCinquenta"],
                    $dataSupply["caixa"]["notas"]["notasCem"]
                );
                $atmResponseTo = AtmTo::fromDomain($atm);
                echo jsonToReponse($atmResponseTo->toArray());

            } catch (InUseATMException $e) {
                $atmResponseTo = AtmTo::fromDomain($atm)->toArray();
                $atmResponseTo["erros"] = [$e->getMessage()];
                echo jsonToReponse($atmResponseTo);
            }

            break;
        case 2:
            echo "--> Realizar Saque <-- \n";

            try {

                $jsonStringSaque = readline("Infome os dados para o saque: ");
                $dataOperation = stringToJson($jsonStringSaque);

                if (!$dataOperation) {
                    break;
                }

                $operation = Operation::createfromJson($dataOperation);
                $atm->withdraw($operation);
                $atmResponseTo = AtmTo::fromDomain($atm);
                echo jsonToReponse($atmResponseTo->toArray());

            } catch (
            UnavailableATMException|UnavailableValueATMException|WithdrawalDuplicateATMException $e) {
                $atmResponseTo = AtmTo::fromDomain($atm)->toArray();
                $atmResponseTo["erros"] = [$e->getMessage()];
                echo jsonToReponse($atmResponseTo);
            }

            break;
        case 3:

            echo "--> Listar operações já realizadas <-- \n";
            $listOperationsToResponse = array_map(function ($operation) {
                return OperationTo::fromDomain($operation)->toArray();
            }, $atm->getOperations());

            echo jsonToReponse($listOperationsToResponse);
            break;

        case 0:

            echo "--> Encerrado! <-- \n";
            exit(0);

        default:
            echo "Opção inválida, Tente novamente.\n";
    }
}


?>