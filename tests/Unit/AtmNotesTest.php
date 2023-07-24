<?php

namespace Tests\Unit;

use App\Domain\AtmNotes;
use PHPUnit\Framework\TestCase;

class AtmNotesTest extends TestCase
{
    public function testCreateInstance()
    {
        $atmNotes = new AtmNotes(0, 0, 0, 0);
        $this->assertInstanceOf(AtmNotes::class, $atmNotes);
    }

    public function testAddNotesTen()
    {
        $atmNotes = new AtmNotes(0, 0, 0, 0);
        $atmNotes->addNotesTen(5);
        $this->assertEquals(5, $atmNotes->getNotesTen());
    }

    public function testAddNotesTwenty()
    {
        $atmNotes = new AtmNotes(0, 0, 0, 0);
        $atmNotes->addNotesTwenty(10);
        $this->assertEquals(10, $atmNotes->getNotesTwenty());
    }

    public function testAddNotesFifty()
    {
        $atmNotes = new AtmNotes(0, 0, 0, 0);
        $atmNotes->addNotesFifty(20);
        $this->assertEquals(20, $atmNotes->getNotesFifty());
    }

    public function testAddNotesHundred()
    {
        $atmNotes = new AtmNotes(0, 0, 0, 0);
        $atmNotes->addNotesHundred(50);
        $this->assertEquals(50, $atmNotes->getNotesHundred());
    }

    public function testDeductNotesTen()
    {
        $atmNotes = new AtmNotes(10, 0, 0, 0);
        $atmNotes->deductNotesTen(5);
        $this->assertEquals(5, $atmNotes->getNotesTen());
    }

    public function testDeductNotesTwenty()
    {
        $atmNotes = new AtmNotes(0, 20, 0, 0);
        $atmNotes->deductNotesTwenty(10);
        $this->assertEquals(10, $atmNotes->getNotesTwenty());
    }

    public function testDeductNotesFifty()
    {
        $atmNotes = new AtmNotes(0, 0, 50, 0);
        $atmNotes->deductNotesFifty(25);
        $this->assertEquals(25, $atmNotes->getNotesFifty());
    }

    public function testDeductNotesHundred()
    {
        $atmNotes = new AtmNotes(0, 0, 0, 100);
        $atmNotes->deductNotesHundred(75);
        $this->assertEquals(25, $atmNotes->getNotesHundred());
    }

}