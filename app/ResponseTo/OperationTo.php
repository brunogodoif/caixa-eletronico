<?php

namespace App\ResponseTo;

use App\Domain\Operation;

class OperationTo
{
    private int $valor;
    private string $horario;

    /**
     * @param int $valor
     * @param string $horario
     */
    public function __construct(int $valor, string $horario)
    {
        $this->valor = $valor;
        $this->horario = $horario;
    }

    /**
     * @param Operation $operation
     * @return OperationTo
     */
    public static function fromDomain(Operation $operation): OperationTo
    {
        return new OperationTo($operation->getValue(), $operation->getCreatedAt()->format('Y-m-d\TH:i:s.v\Z'));
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return call_user_func('get_object_vars', $this);
    }

}