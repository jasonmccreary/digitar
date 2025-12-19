<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{

    /**
     * Setup the layout used by the controller.
     */
    protected function setupLayout(): void
    {
        if (! is_null($this->layout)) {
            $this->layout = view($this->layout);
        }
    }
}
