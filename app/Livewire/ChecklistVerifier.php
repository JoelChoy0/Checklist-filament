<?php

namespace App\Livewire;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\CheckboxList;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ChecklistVerifier extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'form_items' => ['email']
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                CheckboxList::make('form_items')
                    ->options([
                        'name' => 'Name',
                        'email' => 'Email',
                        'phone' => 'Phone',
                    ])
                    ->default(['email'])
                    ->columns()
                    ->disableOptionWhen(fn (string $value): bool => $value === 'email')
                    //->in(fn (CheckboxList $component): array => array_keys($component->getOptions()))
                    //TODO uncommented the "in()" constraint (using getOptions()) to fix the validation bug in my proyect

            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $title = __('Action completed successfully');
        $body  = __('New items: :data', [
            'data' => implode(', ', $data['form_items'] ?? []),
        ]);

        Notification::make()
            ->title($title)
            ->body($body)
            ->success()
            ->send();
        //
    }

    public function render(): View
    {
        return view('livewire.checklist-verifier');
    }
}
