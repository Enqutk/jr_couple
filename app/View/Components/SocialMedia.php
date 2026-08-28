<?php
namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\SocialRef;
use App\Enums\StatusEnum;
class SocialMedia extends Component {
    public $socialRefs;

    public function __construct() {
        $this->socialRefs = SocialRef::where('status', StatusEnum::active)
            ->orderBy('order')
            ->get();
    }

    public function render(): View|Closure|string {
        // don't pass manually, Laravel auto-binds public properties
        return view('components.social-media');
    }
}
