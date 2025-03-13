<?php
namespace App\Filament\Widgets;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Billing;
use Illuminate\Support\Facades\Cache;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
protected static ?string $pollingInterval = null;
protected static bool $isLazy = false;


    protected function getCards(): array
    {
        $totalPatients = Cache::remember('total_patients', now()->addMinutes(5), function () {
            return Patient::count();
        });

        $appointmentsToday = Cache::remember('appointments_today', now()->addMinutes(5), function () {
            return Appointment::whereDate('date', today())->count();
        });

        $unpaidBillings = Cache::remember('unpaid_billings', now()->addMinutes(5), function () {
            return number_format(Billing::where('status', 'pending')->sum('total_amount'), 2);
        });

        return [
            Stat::make('Total Patients', $totalPatients)
                ->description('Total number of registered patients')
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Stat::make('Appointments Today', $appointmentsToday)
                ->description('Number of appointments scheduled for today')
                ->descriptionIcon('heroicon-s-calendar')
                ->color('warning'),
            Stat::make('Unpaid Billings', $unpaidBillings)
                ->description('Total amount of unpaid billings')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('danger'),
        ];
    }
}

