<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class BookingConfirm extends Component
{
    public Reservation $booking;
    
    /**
     * Inicializar componente
     */
    public function mount(int $booking): void
    {
        $this->booking = Reservation::with(['hotel', 'roomType', 'room', 'user'])
            ->findOrFail($booking);
    }
    
    /**
     * Confirmar a reserva
     */
    public function confirmBooking(): void
    {
        // Log para debug
        \Log::info('confirmBooking method called for booking ID: ' . $this->booking->id);
        
        try {
            $this->booking->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);
            
            session()->flash('success', 'Reserva confirmada com sucesso!');
            
            // Usar redirect sem route helper primeiro para testar
            $this->redirect('/booking/success/' . $this->booking->id);
            
        } catch (\Exception $e) {
            \Log::error('Error confirming booking: ' . $e->getMessage());
            session()->flash('error', 'Erro ao confirmar reserva. Tente novamente.');
        }
    }
    
    /**
     * Cancelar a reserva
     */
    public function cancelBooking(): void
    {
        // Log para debug
        \Log::info('cancelBooking method called for booking ID: ' . $this->booking->id);
        
        try {
            $this->booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);
            
            session()->flash('info', 'Reserva cancelada.');
            
            // Usar redirect sem route helper primeiro para testar
            $this->redirect('/');
            
        } catch (\Exception $e) {
            \Log::error('Error cancelling booking: ' . $e->getMessage());
            session()->flash('error', 'Erro ao cancelar reserva. Tente novamente.');
        }
    }
    
    /**
     * Método de teste para verificar se Livewire funciona
     */
    public function testLivewire(): void
    {
        \Log::info('testLivewire method called - Livewire is working!');
        session()->flash('success', 'Teste Livewire: Funciona perfeitamente!');
    }
    
    /**
     * Render do componente
     */
    public function render(): View
    {
        return view('livewire.booking-confirm')
            ->layout('layouts.app');
    }
}
