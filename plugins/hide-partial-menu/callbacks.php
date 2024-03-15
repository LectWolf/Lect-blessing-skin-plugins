<?php

use App\Services\Facades\Option;

return [
    App\Events\PluginWasEnabled::class => function () {
        Option::set('hide-partial-menu.side_menu', '{}');
    },
];
