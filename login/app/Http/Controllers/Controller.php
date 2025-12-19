<?php

namespace App\Http\Controllers;


abstract class Controller
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
