<?php

namespace App\Helpers;

class InstalledFileManager
{

    public function create()
    {
        file_put_contents(storage_path('installed'), '2.0.1');
    }

    public function update()
    {
        return $this->create();
    }
}
