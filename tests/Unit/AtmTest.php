<?php

namespace Tests\Unit;

use App\Domain\Atm;
use App\Domain\ErrorMessage;
use App\Domain\Exceptions\InUseATMException;
use App\Domain\Exceptions\InvalidOperationDateException;
use App\Domain\Exceptions\UnavailableATMException;
use App\Domain\Exceptions\UnavailableValueATMException;
use App\Domain\Exceptions\WithdrawalDuplicateATMException;
use App\Domain\Operation;
use PHPUnit\Framework\TestCase;

class AtmTest extends TestCase
{
    public function testCreateInstance()
    {
        $atm = new Atm();
        $this->assertInstanceOf(Atm::class, $atm);
    }

    public function testSupplyAvaliableATM()
    {
        $atm = new Atm();
        $result = $atm->supply(true, 10, 20, 30, 40);
        $this->assertTrue($result);
    }

    public function testSupplyUnavailableATM()
    {
        $atm = new Atm();
        $atm->supply(true, 10, 20, 50, 100);
        $this->expectException(InUseATMException::class);
        $this->expectExceptionMessage(ErrorMessage::ATM_IN_USE->value);
        $atm->supply(true, 100, 200, 500, 1000);
    }

    public function testWithdrawValid()
    {
        $atm = new Atm();
        $atm->supply(true, 10, 20, 50, 100);
        $operation = new Operation(150, "2023-07-22 12:00:00");
        $result = $atm->withdraw($operation);
        $this->assertTrue($result);
    }

    public function testWithdrawUnavailableATM()
    {
        $atm = new Atm();
        $atm->supply(false, 10, 20, 50, 100);
        $operation = new Operation(150, "2023-07-22 12:00:00");
        $this->expectException(UnavailableATMException::class);
        $this->expectExceptionMessage(ErrorMessage::ATM_UNAVAILABLE->value);
        $atm->withdraw($operation);
    }

    public function testWithdrawUnavailableValueATM()
    {
        $atm = new Atm();
        $atm->supply(true, 0, 0, 0, 1);
        $operation = new Operation(150, "2023-07-22 12:00:00");
        $this->expectException(UnavailableValueATMException::class);
        $this->expectExceptionMessage(ErrorMessage::UNAVAILABLE_VALUE->value);
        $atm->withdraw($operation);
    }

    public function testWithdrawInvalidOperationDate()
    {
        $atm = new Atm();
        $atm->supply(true, 10, 20, 50, 100);
        $operation1 = new Operation(100, "2023-07-22 12:00:00");
        $operation2 = new Operation(100, "2023-07-22 12:30:00");
        $operation3 = new Operation(100, "2000-07-22 10:00:00");

        $atm->withdraw($operation1);
        $atm->withdraw($operation2);

        $this->expectException(InvalidOperationDateException::class);
        $this->expectExceptionMessage(ErrorMessage::INVALID_DATE->value);
        $atm->withdraw($operation3);
    }

    public function testHasDuplicateWithdrawal()
    {
        $atm = new Atm();
        $atm->supply(true, 10, 20, 50, 100);
        $operation1 = new Operation(100, "2023-07-22 12:00:00");
        $operation2 = new Operation(100, "2023-07-22 12:05:00");

        $atm->withdraw($operation1);

        $this->expectException(WithdrawalDuplicateATMException::class);
        $this->expectExceptionMessage(ErrorMessage::WITHDRAWAL_DUPLICATE->value);
        $atm->withdraw($operation2);
    }

    public function testDeductNotesValid()
    {
        $atm = new Atm();
        $atm->supply(true, 10, 20, 50, 100);
        $operation = new Operation(150, "2023-07-22 12:00:00");
        $atm->withdraw($operation);
        $atmNotes = $atm->getAtmNotes();
        $this->assertEquals(99, $atmNotes->getNotesHundred());
        $this->assertEquals(49, $atmNotes->getNotesFifty());
    }

}