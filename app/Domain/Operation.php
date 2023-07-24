<?php

namespace App\Domain;

use DateTime;
use App\Domain\Exceptions\InvalidOperationException;

class Operation
{
    /**
     * @var int
     */
    private int $value;
    /**
     * @var DateTime
     */
    private DateTime $created_at;

    /**
     * @throws \Exception
     */
    public function __construct(int $value, ?string $created_at)
    {
        $this->value = $value;
        $this->created_at = new DateTime($created_at) ?? new DateTime();
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    /**
     * @param array $data
     * @return Operation
     * @throws InvalidOperationException
     * @throws \Exception
     */
    public static function createfromJson(array $data): Operation
    {
        if (!isset($data["saque"])) {
            throw new InvalidOperationException("Operação Inválida");
        }
        return new Operation($data["saque"]["valor"], $data["saque"]["horario"]);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return call_user_func('get_object_vars', $this);
    }

}