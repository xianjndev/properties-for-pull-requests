<?php

namespace Homeful\Properties\Commands;

use Illuminate\Console\Command;

class PropertiesCommand extends Command
{
    public $signature = 'properties';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
