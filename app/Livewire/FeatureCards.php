<?php

namespace App\Livewire;

use Livewire\Component;

class FeatureCards extends Component
{
    public array $cards = [];

    public string $title = '';
    public string $description = '';

    public function mount(): void
    {
        $this->cards = [
            ['title' => 'Dashboard en tiempo real', 'description' => 'Métricas, estados y actividad reciente en una vista clara.'],
            ['title' => 'Permisos y roles', 'description' => 'Control granular de acceso para equipos y áreas.'],
            ['title' => 'Automatizaciones', 'description' => 'Dispara flujos al cambiar estados o recibir eventos.'],
        ];
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|min:2|max:80',
            'description' => 'nullable|string|max:200',
        ];
    }

    public function addCard(): void
    {
        $this->validate();

        $this->cards[] = [
            'title' => trim($this->title),
            'description' => trim($this->description),
        ];

        $this->reset(['title', 'description']);
    }

    public function removeCard(int $index): void
    {
        if (! isset($this->cards[$index])) {
            return;
        }

        unset($this->cards[$index]);
        $this->cards = array_values($this->cards);
    }

    public function render()
    {
        return view('livewire.feature-cards');
    }
}

