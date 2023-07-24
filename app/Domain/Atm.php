<?php
namespace App\Domain;

use App\Domain\Exceptions\InvalidOperationDateException;
use DateTime;
use App\Domain\Exceptions\InUseATMException;
use App\Domain\Exceptions\UnavailableATMException;
use App\Domain\Exceptions\UnavailableValueATMException;
use App\Domain\Exceptions\WithdrawalDuplicateATMException;

class Atm
{

    private bool $available = false;
    private AtmNotes $atmNotes;
    private array $operations = [];

    public function __construct()
    {
        $this->available = false;
        $this->atmNotes = new AtmNotes(0, 0, 0, 0);
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function getAtmNotes(): AtmNotes
    {
        return $this->atmNotes;
    }

    public function getOperations(): array
    {
        return $this->operations;
    }

    private function setAvailable(bool $available): void
    {
        $this->available = $available;
    }

    private function setAtmNotes(int $notesTen, int $notesTwenty, int $notesFifty, int $notesHundred): void
    {
        $this->atmNotes->addNotesTen($notesTen);
        $this->atmNotes->addNotesTwenty($notesTwenty);
        $this->atmNotes->addNotesFifty($notesFifty);
        $this->atmNotes->addNotesHundred($notesHundred);
    }

    public function supply(bool $avaliable, int $notesTen, int $notesTwenty, int $notesFifty, int $notesHundred): bool
    {
        if ($this->isAvailable()) {
            throw new InUseATMException(ErrorMessage::ATM_IN_USE->value);
        }
        $this->setAtmNotes($notesTen, $notesTwenty, $notesFifty, $notesHundred);
        $this->setAvailable($avaliable);

        return true;
    }

    public function withdraw(Operation $operation): bool
    {
        if (!$this->isAvailable()) {
            throw new UnavailableATMException(ErrorMessage::ATM_UNAVAILABLE->value);
        }

        if ($operation->getValue() > $this->getTotalInATM()) {
            throw new UnavailableValueATMException(ErrorMessage::UNAVAILABLE_VALUE->value);
        }

        if (!$this->isOlderThanExistingOperations($operation->getCreatedAt())) {
            throw new InvalidOperationDateException(ErrorMessage::INVALID_DATE->value);
        }

        if ($this->hasDuplicateWithdrawal($operation->getValue(), $operation->getCreatedAt())) {
            throw new WithdrawalDuplicateATMException(ErrorMessage::WITHDRAWAL_DUPLICATE->value);
        }

        if ($this->deductNotes($operation->getValue())) {
            $this->operations[] = $operation;
        };

        return true;
    }

    private function getTotalInATM(): int
    {
        return $this->atmNotes->getNotesTen() * 10
            + $this->atmNotes->getNotesTwenty() * 20
            + $this->atmNotes->getNotesFifty() * 50
            + $this->atmNotes->getNotesHundred() * 100;
    }

    private function deductNotes(int $withdrawalValue): bool
    {

        $notesHundred = min($this->atmNotes->getNotesHundred(), intdiv($withdrawalValue, 100));
        $withdrawalValue -= $notesHundred * 100;

        $notesFifty = min($this->atmNotes->getNotesFifty(), intdiv($withdrawalValue, 50));
        $withdrawalValue -= $notesFifty * 50;

        $notesTwenty = min($this->atmNotes->getNotesTwenty(), intdiv($withdrawalValue, 20));
        $withdrawalValue -= $notesTwenty * 20;

        $notesTen = min($this->atmNotes->getNotesTen(), intdiv($withdrawalValue, 10));
        $withdrawalValue -= $notesTen * 10;

        if ($withdrawalValue > 0) {
            throw new UnavailableValueATMException(ErrorMessage::UNAVAILABLE_VALUE->value);
        }

        $this->atmNotes->deductNotesHundred($notesHundred);
        $this->atmNotes->deductNotesFifty($notesFifty);
        $this->atmNotes->deductNotesTwenty($notesTwenty);
        $this->atmNotes->deductNotesTen($notesTen);

        return true;
    }

    private function hasDuplicateWithdrawal(int $withdrawalValue, DateTime $dateTime): bool
    {
        foreach ($this->operations as $operation) {
            /** @var Operation $operation */
            $timePreviousWithdrawal = $operation->getCreatedAt();
            $interval = $timePreviousWithdrawal->diff($dateTime);

            $minutesDiff = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

            if ($minutesDiff < 10 && $operation->getValue() === $withdrawalValue) {
                return true;
            }
        }

        return false;
    }

    private function isOlderThanExistingOperations(DateTime $operationDate): bool
    {
        foreach ($this->operations as $existingOperation) {
            if ($operationDate < $existingOperation->getCreatedAt()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return call_user_func('get_object_vars', $this);
    }
}
