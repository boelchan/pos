<?php

namespace App\View\Components\Button;

use Illuminate\View\Component;

class Destroy extends Component
{
    public $action;

    public $label;

    public $target;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($action, $label, $target)
    {
        $this->action = $action;
        $this->label = $label;
        $this->target = $target;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.button.destroy');
    }
}
