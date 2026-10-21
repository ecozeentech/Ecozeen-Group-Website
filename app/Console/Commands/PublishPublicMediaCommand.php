<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishPublicMediaCommand extends Command
{
    protected $signature = 'ecozeen:publish-media';

    protected $description = 'Copy legacy public uploads from storage/app/public into public/media so images work without a storage symlink.';

    public function handle(): int
    {
        $destination = public_path('media');
        $sources = [
            storage_path('app/public'),
            public_path('storage'),
        ];

        if (! is_dir($destination) && ! File::makeDirectory($destination, 0755, true) && ! is_dir($destination)) {
            $this->error('Could not create public/media.');

            return self::FAILURE;
        }

        $copied = 0;

        foreach ($sources as $source) {
            if (! is_dir($source)) {
                continue;
            }

            $realSource = realpath($source);
            $realDestination = realpath($destination);

            if ($realSource === false || $realDestination === false || $realSource === $realDestination) {
                continue;
            }

            foreach (File::allFiles($source) as $file) {
                if (str_starts_with($file->getFilename(), '.')) {
                    continue;
                }

                $relative = ltrim(str_replace('\\', '/', $file->getRelativePathname()), '/');
                $target = $destination.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relative);

                if (is_file($target)) {
                    continue;
                }

                File::ensureDirectoryExists(dirname($target));
                File::copy($file->getPathname(), $target);
                $copied++;
            }
        }

        $this->info("Public media is ready at public/media ({$copied} file(s) copied).");

        return self::SUCCESS;
    }
}
