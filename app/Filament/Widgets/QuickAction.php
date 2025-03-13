<?php

namespace App\Filament\Widgets;

use AllowDynamicProperties;
use App\Filament\Resources\AppointmentResource;
use App\Filament\Resources\MedicalRecordResource;
use App\Filament\Resources\PatientResource;
use App\Filament\Resources\BillingResource;
use Filament\Widgets\Widget;

#[AllowDynamicProperties] class QuickAction extends Widget
{
    protected static string $view = 'filament.widgets.quick-actions-widget';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
protected static ?string $pollingInterval = null;

    public function mount(): void
    {
        $this->actions = [
            [
                'label' => 'New Appointment',
                'icon' => 'heroicon-o-document-plus',
                'url' => AppointmentResource::getUrl('create'),
            ],
            [
                'label' => 'View Medical Records',
                'icon' => 'heroicon-o-plus-circle',
                'url' =>MedicalRecordResource::getUrl('index'),
            ],
            [
                'label' => 'New Billing',
                'icon' => 'heroicon-o-currency-dollar',
                'url' => BillingResource::getUrl('create'),
            ],
            [
                'label' => 'Add Patient',
                'icon' => 'heroicon-o-user-plus',
                'url' => PatientResource::getUrl('create'),
            ],
        ];
    }
}
