<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class TimeAndDate extends Widget
{
    protected static string $view = 'filament.widgets.time-and-date-widget';
    protected static ?int $sort = -2;
    protected static ?string $pollingInterval = '58s';
    protected static bool $isLazy = false;

    public $currentDateTime;
    public $dayOfWeek;
    public $dayOfYear;
    public $amPm;


    public function mount()
    {
        $this->currentDateTime = now();
        $this->dayOfWeek = $this->currentDateTime->format('l');
        $this->dayOfYear = $this->currentDateTime->dayOfYear;

    }


    public function getListeners()
    {
        return [
            'updateDateTime' => 'updateDateTime',
        ];
    }

    public function updateDateTime()
    {
        $this->currentDateTime = now();
        $this->dayOfWeek = $this->currentDateTime->format('l');
        $this->dayOfYear = $this->currentDateTime->dayOfYear;
        $this->amPm = $this->currentDateTime->format('A');
    }
}
