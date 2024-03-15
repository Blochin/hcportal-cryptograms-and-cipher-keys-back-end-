<?php

namespace App\Http\Actions\Migrations;

abstract class Migration
{
    private $from;
    private $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }
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
            if($this->from <= $record->id && $this->to >= $record->id){
                $sanitized = $this->processRecord($record);
                $this->allSanitized[] = $sanitized;
            }
        }

        return $this->allSanitized;

    }
}
