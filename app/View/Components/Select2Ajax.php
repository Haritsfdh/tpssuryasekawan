<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select2Ajax extends Component
{
    public string $id;
    public string $name;
    public string $route;
    public string $placeholder;
    public string $required;
    public ?string $width;
    /**
     * Create a new component instance.
     */
    public function __construct(
        string $id,
        string $name,
        string $route,
        string $placeholder,
        string $required = '',
        ?string $width = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->route = $route;
        $this->placeholder = $placeholder;

        $this->required = $required;
        $this->width = $width;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select2-ajax');
    }
}
