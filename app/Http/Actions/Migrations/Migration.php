<?php

namespace App\Http\Actions\Migrations;

use Illuminate\Support\Facades\DB;

abstract class Migration
{
    protected $allSanitized = [];
    protected $data = [];
    abstract protected function getData();
    abstract protected function processRecord($record);
    abstract protected function handle();

    protected function processDatabase($databaseDump)
    {
        self::prepareDatabase($databaseDump);
        $this->data = $this->getData();
        foreach ($this->data['records'] as $record) {
            $sanitized = $this->processRecord($record);
            $this->allSanitized[] = $sanitized;
        }

        return $this->allSanitized;

    }

    private static function prepareDatabase($databaseDump) : void{
        $statements = explode(';', $databaseDump);
        foreach ($statements as $statement) {
            $trimmedStatement = trim($statement);
            if ($trimmedStatement !== '') {
                if (strpos($trimmedStatement, 'CREATE') === false && strpos($trimmedStatement, 'ALTER') === false) {
                    try {
                        DB::unprepared($statement);
                    } catch (\Exception $e) {

                    }
                }
            }
        }
    }
}
