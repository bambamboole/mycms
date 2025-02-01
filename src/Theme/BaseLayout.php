<?php declare(strict_types=1);

namespace Bambamboole\MyCms\Theme;

use Bambamboole\MyCms\Models\BasePostType;
use Illuminate\View\Component;

class BaseLayout extends Component
{
    public function __construct(protected BasePostType $post) {}

    public static function getId()
    {
        return 'base-layout';
    }

    public function render()
    {
        return $this->view('mycms::themes.blank.layouts.base', ['post' => $this->post]);
    }
}
