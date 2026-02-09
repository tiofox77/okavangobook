<?php

namespace App\Livewire\Admin;

use App\Models\Reservation;
use Livewire\Component;
use Carbon\Carbon;

class ReservationReports extends Component
{
    public $startDate;
    public $endDate;
    public $status = 'all';
    public $paymentMethod = 'all';
    public $paymentStatus = 'all';

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function export()
    {
        $reservations = $this->getFilteredReservations();
        
        $filename = 'reservations_report_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reservations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Hotel', 'Cliente', 'Check-in', 'Check-out', 'Status', 'Método Pagamento', 'Status Pagamento', 'Valor']);

            foreach ($reservations as $reservation) {
                fputcsv($file, [
                    $reservation->id,
                    $reservation->hotel->name ?? '',
                    $reservation->user->name ?? $reservation->guest_name,
                    $reservation->check_in->format('d/m/Y'),
                    $reservation->check_out->format('d/m/Y'),
                    $reservation->status,
                    $reservation->payment_method,
                    $reservation->payment_status,
                    number_format($reservation->total_price, 2),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getFilteredReservations()
    {
        $query = Reservation::with(['hotel', 'user'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate . ' 23:59:59']);

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        if ($this->paymentMethod !== 'all') {
            $query->where('payment_method', $this->paymentMethod);
        }

        if ($this->paymentStatus !== 'all') {
            $query->where('payment_status', $this->paymentStatus);
        }

        return $query->latest()->get();
    }

    private function getStatistics()
    {
        $reservations = $this->getFilteredReservations();

        return [
            'total' => $reservations->count(),
            'confirmed' => $reservations->where('status', 'confirmed')->count(),
            'pending' => $reservations->where('status', 'pending')->count(),
            'cancelled' => $reservations->where('status', 'cancelled')->count(),
            'paid' => $reservations->where('payment_status', 'paid')->count(),
            'payment_pending' => $reservations->where('payment_status', 'pending')->count(),
            'total_revenue' => $reservations->where('payment_status', 'paid')->sum('total_price'),
            'pending_revenue' => $reservations->where('payment_status', 'pending')->sum('total_price'),
            'by_method' => [
                'cash' => $reservations->where('payment_method', 'cash')->count(),
                'transfer' => $reservations->where('payment_method', 'transfer')->count(),
                'tpa_onsite' => $reservations->where('payment_method', 'tpa_onsite')->count(),
            ],
        ];
    }

    public function render()
    {
        $stats = $this->getStatistics();
        $reservations = $this->getFilteredReservations();

        return view('livewire.admin.reservation-reports', [
            'stats' => $stats,
            'reservations' => $reservations,
        ])->layout('layouts.admin');
    }
}
