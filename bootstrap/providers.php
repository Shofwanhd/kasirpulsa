<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\DashboardPanelProvider;
use App\Providers\LabaService;

return [
    AppServiceProvider::class,
    DashboardPanelProvider::class,
    LabaService::class,
];
