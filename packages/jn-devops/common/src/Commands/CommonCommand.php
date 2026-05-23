<?php

namespace Homeful\Common\Commands;

use Illuminate\Console\Command;

class CommonCommand extends Command
{
    public $signature = 'common';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
