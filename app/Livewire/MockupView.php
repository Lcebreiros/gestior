<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class MockupView extends Component
{
    use WithFileUploads;

    public $computerImage;
    public $phoneImage;
    public $computerLoading = false;
    public $phoneLoading = false;

    public function uploadComputerImage($file)
    {
        $this->computerLoading = true;
        
        try {
            // Validar archivo
            $this->validate([
                'computerImage' => 'image|max:5120',
            ]);

            // Guardar imagen temporalmente
            $this->computerImage = $file->temporaryUrl();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al subir la imagen: ' . $e->getMessage());
        } finally {
            $this->computerLoading = false;
        }
    }

    public function uploadPhoneImage($file)
    {
        $this->phoneLoading = true;
        
        try {
            // Validar archivo
            $this->validate([
                'phoneImage' => 'image|max:5120',
            ]);

            // Guardar imagen temporalmente
            $this->phoneImage = $file->temporaryUrl();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al subir la imagen: ' . $e->getMessage());
        } finally {
            $this->phoneLoading = false;
        }
    }

    public function removeComputerImage()
    {
        $this->computerImage = null;
    }

    public function removePhoneImage()
    {
        $this->phoneImage = null;
    }

    public function render()
    {
        return view('livewire.mockup-view');
    }
}