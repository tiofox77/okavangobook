<?php

namespace App\Livewire\Admin;

use App\Models\Coupon;
use Livewire\Component;
use Livewire\WithPagination;

class CouponManagement extends Component
{
    use WithPagination;

    public $couponId;
    public $code;
    public $description;
    public $type = 'percentage';
    public $value;
    public $min_amount;
    public $max_uses;
    public $is_active = true;
    public $starts_at;
    public $expires_at;

    public $search = '';
    public $isEditing = false;
    public $showModal = false;

    protected $rules = [
        'code' => 'required|string|max:50',
        'description' => 'nullable|string|max:255',
        'type' => 'required|in:percentage,fixed',
        'value' => 'required|numeric|min:0',
        'min_amount' => 'nullable|numeric|min:0',
        'max_uses' => 'nullable|integer|min:1',
        'is_active' => 'boolean',
        'starts_at' => 'nullable|date',
        'expires_at' => 'nullable|date|after_or_equal:starts_at',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $this->couponId = $coupon->id;
        $this->code = $coupon->code;
        $this->description = $coupon->description;
        $this->type = $coupon->type;
        $this->value = $coupon->value;
        $this->min_amount = $coupon->min_amount;
        $this->max_uses = $coupon->max_uses;
        $this->is_active = $coupon->is_active;
        $this->starts_at = $coupon->starts_at ? $coupon->starts_at->format('Y-m-d') : null;
        $this->expires_at = $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : null;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'code' => strtoupper($this->code),
            'description' => $this->description,
            'type' => $this->type,
            'value' => $this->value,
            'min_amount' => $this->min_amount,
            'max_uses' => $this->max_uses,
            'is_active' => $this->is_active,
            'starts_at' => $this->starts_at,
            'expires_at' => $this->expires_at,
        ];

        if ($this->isEditing) {
            Coupon::find($this->couponId)->update($data);
            session()->flash('message', 'Cupom atualizado com sucesso!');
        } else {
            Coupon::create($data);
            session()->flash('message', 'Cupom criado com sucesso!');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        Coupon::find($id)->delete();
        session()->flash('message', 'Cupom removido com sucesso!');
    }

    public function toggleStatus($id)
    {
        $coupon = Coupon::find($id);
        $coupon->update(['is_active' => !$coupon->is_active]);
        session()->flash('message', 'Status do cupom atualizado!');
    }

    public function export()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->get();

        $filename = 'coupons_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($coupons) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Código', 'Tipo', 'Valor', 'Usos', 'Máx Usos', 'Status', 'Expira em']);
            
            foreach ($coupons as $coupon) {
                fputcsv($file, [
                    $coupon->code,
                    $coupon->type == 'percentage' ? 'Percentual' : 'Fixo',
                    $coupon->value,
                    $coupon->uses_count,
                    $coupon->max_uses ?? 'Ilimitado',
                    $coupon->is_active ? 'Ativo' : 'Inativo',
                    $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Sem limite',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->couponId = null;
        $this->code = '';
        $this->description = '';
        $this->type = 'percentage';
        $this->value = null;
        $this->min_amount = null;
        $this->max_uses = null;
        $this->is_active = true;
        $this->starts_at = null;
        $this->expires_at = null;
        $this->resetValidation();
    }

    public function render()
    {
        $coupons = Coupon::query()
            ->when($this->search, function ($query) {
                $query->where('code', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.coupon-management', [
            'coupons' => $coupons,
        ])->layout('layouts.admin');
    }
}
