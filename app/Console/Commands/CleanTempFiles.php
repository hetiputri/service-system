<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;

class CleanTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-temp-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */


    public function handle()
    {
        $files = Storage::disk('public')->files('temp');

        foreach ($files as $file) {

            $lastModified = Storage::disk('public')->lastModified($file);

            if (now()->timestamp - $lastModified > 86400) { // 24 jam
                Storage::disk('public')->delete($file);
            }
        }

        $this->info('Temp files cleaned.');
    }
}
