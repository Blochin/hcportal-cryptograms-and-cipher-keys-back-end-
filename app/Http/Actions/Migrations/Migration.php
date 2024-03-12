<?php

namespace App\Http\Actions\Migrations;

abstract class Migration
{
    protected $allSanitized = [];
    protected $data = [];
    abstract protected function getData();
    abstract protected function processRecord($record);
    abstract protected function handle();
    abstract static function prepareDatabase();

    protected function processDatabase()
    {
        $this->prepareDatabase();
        $this->data = $this->getData();
        foreach ($this->data['records'] as $record) {
            $sanitized = $this->processRecord($record);
            $this->allSanitized[] = $sanitized;
        }

        return $this->allSanitized;

    }
}
