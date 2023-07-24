<?php

namespace App\Domain;

class AtmNotes
{
    private int $notesTen;
    private int $notesTwenty;
    private int $notesFifty;
    private int $notesHundred;

    public function __construct(int $notesTen, int $notesTwenty, int $notesFifty, int $notesHundred)
    {
        $this->notesTen = $notesTen;
        $this->notesTwenty = $notesTwenty;
        $this->notesFifty = $notesFifty;
        $this->notesHundred = $notesHundred;
    }

    public function getNotesTen(): int
    {
        return $this->notesTen;
    }

    public function getNotesTwenty(): int
    {
        return $this->notesTwenty;
    }

    public function getNotesFifty(): int
    {
        return $this->notesFifty;
    }

    public function getNotesHundred(): int
    {
        return $this->notesHundred;
    }

    public function addNotesTen(int $quantity): void
    {
        $this->notesTen += $quantity;
    }

    public function addNotesTwenty(int $quantity): void
    {
        $this->notesTwenty += $quantity;
    }

    public function addNotesFifty(int $quantity): void
    {
        $this->notesFifty += $quantity;
    }

    public function addNotesHundred(int $quantity): void
    {
        $this->notesHundred += $quantity;
    }

    public function deductNotesTen(int $quantity): void
    {
        $this->notesTen -= $quantity;
    }

    public function deductNotesTwenty(int $quantity): void
    {
        $this->notesTwenty -= $quantity;
    }

    public function deductNotesFifty(int $quantity): void
    {
        $this->notesFifty -= $quantity;
    }

    public function deductNotesHundred(int $quantity): void
    {
        $this->notesHundred -= $quantity;
    }

}