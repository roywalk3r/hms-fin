<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;
    protected function afterCreate(): void
    {
        /** @var Appointment $appointment */
        $appointment = $this->record;

        /** @var User $user */
        $user = auth()->user();
        $allUsers = User::all();
        Notification::make()
            ->title('New Appoint')
            ->icon('heroicon-o-calendar')
            ->body("**{$appointment->patient?->name} has been booked for  {$appointment->date->format('F j, Y')}")
            ->actions([
                Action::make('View')
                    ->url(AppointmentResource::getUrl('edit', ['record' => $appointment])),
            ])
            ->sendToDatabase($allUsers);
    }

}
