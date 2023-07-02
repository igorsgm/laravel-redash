<?php

namespace Igorsgm\Redash\Commands;

use Illuminate\Console\Command;

class RedashCommand extends Command
{
    public $signature = 'laravel-redash';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
