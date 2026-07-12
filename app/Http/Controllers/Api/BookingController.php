<?php

namespace App\Http\Controllers\Api;

use App\Events\ReservationCreated;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\RoomType;
use App\Models\User;
use App\Services\WebhookService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * POST /api/v1/bookings
     * Cria uma reserva a partir de um sistema externo.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'hotel_id' => ['required', 'exists:hotels,id'],
            'room_type_id' => ['required', 'exists:room_types,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:40'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
            'total_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $roomType = RoomType::where('id', $data['room_type_id'])
            ->where('hotel_id', $data['hotel_id'])
            ->first();

        if (!$roomType) {
            return response()->json(['message' => 'O tipo de quarto não pertence a este hotel.'], 422);
        }

        $nights = Carbon::parse($data['check_in'])->diffInDays(Carbon::parse($data['check_out']));
        $total = $data['total_price'] ?? (($roomType->base_price ?? 0) * max($nights, 1));

        $reservation = DB::transaction(function () use ($data, $total) {
            $user = User::firstOrCreate(
                ['email' => $data['customer_email']],
                [
                    'name' => $data['customer_name'],
                    'password' => Hash::make(Str::random(24)),
                ]
            );

            $notes = $data['special_requests'] ?? null;
            if (!empty($data['customer_phone'])) {
                $notes = trim(($notes ? $notes . ' | ' : '') . 'Tel: ' . $data['customer_phone']);
            }

            return Reservation::create([
                'user_id' => $user->id,
                'hotel_id' => $data['hotel_id'],
                'room_type_id' => $data['room_type_id'],
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'guests' => $data['guests'],
                'total_price' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'api',
                'special_requests' => $notes,
                'confirmation_code' => 'OKB-' . strtoupper(Str::random(8)),
                'is_refundable' => true,
            ]);
        });

        $reservation->load('hotel');

        // Dispara notificações internas + webhooks (via listener DispatchReservationWebhook).
        event(new ReservationCreated($reservation));

        return (new ReservationResource($reservation))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * GET /api/v1/bookings/{code}
     * Consulta o estado de uma reserva pelo código de confirmação.
     */
    public function show(string $code)
    {
        $reservation = Reservation::where('confirmation_code', $code)
            ->orWhere('id', $code)
            ->with('hotel')
            ->firstOrFail();

        return new ReservationResource($reservation);
    }
}
