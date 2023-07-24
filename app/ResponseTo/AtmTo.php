<?php

namespace App\ResponseTo;

use App\Domain\Atm;

class AtmTo
{
    private bool $caixaDisponivel = false;
    private array $notas = [];

    /**
     * @param bool $caixaDisponivel
     * @param array $notas
     */
    public function __construct(bool $caixaDisponivel, array $notas)
    {
        $this->caixaDisponivel = $caixaDisponivel;
        $this->notas = $notas;
    }

    /**
     * @param Atm $caixa
     * @return AtmTo
     */
    public static function fromDomain(Atm $caixa): AtmTo
    {
        return new AtmTo($caixa->isAvailable(), [
            "notasDez" => $caixa->getAtmNotes()->getNotesTen(),
            "notasVinte" => $caixa->getAtmNotes()->getNotesTwenty(),
            "notasCinquenta" => $caixa->getAtmNotes()->getNotesFifty(),
            "notasCem" => $caixa->getAtmNotes()->getNotesHundred()
        ]);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return call_user_func('get_object_vars', $this);
    }

}