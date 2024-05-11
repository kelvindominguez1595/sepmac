<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Form extends Component
{
    public $btntext;
    public $method;
    public $isPut;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($btntext = "Guardar", $method = "POST", $isPut = '')
    {
        $this->btntext = $btntext;
        $this->method = $method;
        $this->isPut = $isPut;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form');
    }
}
