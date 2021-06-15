<?php

namespace Laraware\Bag\Item;

use Exception;

class ItemNote
{
    protected $hash;

    protected $note;

    protected $attributes;

    public function __construct($note, array $attributes = [])
    {
        $this->assertNoteIsString($note);

        $this->setNote($note);

        $this->assertAttributesIsArray($attributes);

        $this->setAttributes($attributes);

        $this->setHash();
    }

    public function getHash()
    {
        return $this->hash;
    }

    protected function setHash()
    {
        $this->hash = sha1($this->getNote().json_encode($this->getAttributes()));
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    protected function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    protected function assertAttributesIsArray($attributes)
    {
        if (!is_array($attributes)) {
            throw new Exception();
        }
    }

    public function getNote()
    {
        return $this->note;
    }

    protected function setNote(string $note)
    {
        $this->note = $note;
    }

    protected function assertNoteIsString($note)
    {
        if (!is_string($note)) {
            throw new Exception();
        }
    }
}
