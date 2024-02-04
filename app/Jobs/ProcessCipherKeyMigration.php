<?php

namespace App\Jobs;

use App\Http\Actions\CreateCipherKey;
use App\Http\Actions\CreateCryptogram;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCipherKeyMigration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sanitized;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chunk)
    {
        $this->sanitized = $chunk;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $handler = new CreateCipherKey();
        try{
            $handler->handle($this->sanitized);
        }catch (Exception $e){

        }
    }

    public function failed(Exception $exception)
    {
        echo $exception->getMessage();
    }
}
