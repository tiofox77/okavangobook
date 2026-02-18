<?php

namespace App\Livewire\Admin;

use App\Models\Plan;
use App\Models\Subscription;
use Livewire\Component;
use Livewire\WithPagination;

class PlanManagement extends Component
{
    use WithPagination;

    // Modal state
    public bool $showModal = false;
    public bool $showSubscriptionsModal = false;
    public ?int $editingPlanId = null;
    public ?int $viewingPlanId = null;

    // Form fields
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public string $badge_color = 'blue';
    public string $icon = 'star';
    public $price_monthly = 0;
    public $price_yearly = 0;
    public int $max_hotels = 1;
    public int $max_room_types_per_hotel = 5;
    public int $max_images_per_hotel = 5;
    public int $max_images_per_room = 3;
    public bool $featured_listing = false;
    public bool $priority_support = false;
    public bool $advanced_analytics = false;
    public bool $review_responses = false;
    public bool $restaurant_management = false;
    public bool $leisure_management = false;
    public bool $custom_branding = false;
    public bool $api_access = false;
    public bool $priority_search = false;
    public bool $promotions = false;
    public bool $export_reports = false;
    public int $trial_days = 0;
    public int $sort_order = 0;
    public bool $is_active = true;
    public bool $is_popular = false;
    public bool $is_free = false;

    // Filters
    public string $search = '';

    protected function rules()
    {
        $uniqueSlug = $this->editingPlanId
            ? 'unique:plans,slug,' . $this->editingPlanId
            : 'unique:plans,slug';

        return [
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|' . $uniqueSlug,
            'description' => 'nullable|string|max:500',
            'badge_color' => 'required|string|max:20',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'max_hotels' => 'required|integer|min:1',
            'max_room_types_per_hotel' => 'required|integer|min:1',
            'max_images_per_hotel' => 'required|integer|min:1',
            'max_images_per_room' => 'required|integer|min:1',
            'trial_days' => 'required|integer|min:0',
            'sort_order' => 'required|integer|min:0',
        ];
    }

    public function updatedName($value)
    {
        if (!$this->editingPlanId) {
            $this->slug = \Illuminate\Support\Str::slug($value);
        }
    }

    public function openModal(?int $planId = null)
    {
        $this->resetValidation();
        $this->resetForm();

        if ($planId) {
            $plan = Plan::findOrFail($planId);
            $this->editingPlanId = $plan->id;
            $this->name = $plan->name;
            $this->slug = $plan->slug;
            $this->description = $plan->description ?? '';
            $this->badge_color = $plan->badge_color;
            $this->icon = $plan->icon ?? 'star';
            $this->price_monthly = $plan->price_monthly;
            $this->price_yearly = $plan->price_yearly;
            $this->max_hotels = $plan->max_hotels;
            $this->max_room_types_per_hotel = $plan->max_room_types_per_hotel;
            $this->max_images_per_hotel = $plan->max_images_per_hotel;
            $this->max_images_per_room = $plan->max_images_per_room;
            $this->featured_listing = $plan->featured_listing;
            $this->priority_support = $plan->priority_support;
            $this->advanced_analytics = $plan->advanced_analytics;
            $this->review_responses = $plan->review_responses;
            $this->restaurant_management = $plan->restaurant_management;
            $this->leisure_management = $plan->leisure_management;
            $this->custom_branding = $plan->custom_branding;
            $this->api_access = $plan->api_access;
            $this->priority_search = $plan->priority_search;
            $this->promotions = $plan->promotions;
            $this->export_reports = $plan->export_reports;
            $this->trial_days = $plan->trial_days;
            $this->sort_order = $plan->sort_order;
            $this->is_active = $plan->is_active;
            $this->is_popular = $plan->is_popular;
            $this->is_free = $plan->is_free;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'badge_color' => $this->badge_color,
            'icon' => $this->icon,
            'price_monthly' => $this->is_free ? 0 : $this->price_monthly,
            'price_yearly' => $this->is_free ? 0 : $this->price_yearly,
            'max_hotels' => $this->max_hotels,
            'max_room_types_per_hotel' => $this->max_room_types_per_hotel,
            'max_images_per_hotel' => $this->max_images_per_hotel,
            'max_images_per_room' => $this->max_images_per_room,
            'featured_listing' => $this->featured_listing,
            'priority_support' => $this->priority_support,
            'advanced_analytics' => $this->advanced_analytics,
            'review_responses' => $this->review_responses,
            'restaurant_management' => $this->restaurant_management,
            'leisure_management' => $this->leisure_management,
            'custom_branding' => $this->custom_branding,
            'api_access' => $this->api_access,
            'priority_search' => $this->priority_search,
            'promotions' => $this->promotions,
            'export_reports' => $this->export_reports,
            'trial_days' => $this->trial_days,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'is_popular' => $this->is_popular,
            'is_free' => $this->is_free,
        ];

        if ($this->editingPlanId) {
            Plan::findOrFail($this->editingPlanId)->update($data);
            session()->flash('message', 'Plano actualizado com sucesso!');
        } else {
            Plan::create($data);
            session()->flash('message', 'Plano criado com sucesso!');
        }

        $this->closeModal();
    }

    public function toggleActive(int $planId)
    {
        $plan = Plan::findOrFail($planId);
        $plan->update(['is_active' => !$plan->is_active]);
    }

    public function togglePopular(int $planId)
    {
        $plan = Plan::findOrFail($planId);
        $plan->update(['is_popular' => !$plan->is_popular]);
    }

    public function deletePlan(int $planId)
    {
        $plan = Plan::findOrFail($planId);
        if ($plan->activeSubscriptions()->count() > 0) {
            session()->flash('error', 'Não é possível eliminar um plano com subscrições activas.');
            return;
        }
        $plan->delete();
        session()->flash('message', 'Plano eliminado com sucesso!');
    }

    public function viewSubscriptions(int $planId)
    {
        $this->viewingPlanId = $planId;
        $this->showSubscriptionsModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeSubscriptionsModal()
    {
        $this->showSubscriptionsModal = false;
        $this->viewingPlanId = null;
    }

    private function resetForm()
    {
        $this->editingPlanId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->badge_color = 'blue';
        $this->icon = 'star';
        $this->price_monthly = 0;
        $this->price_yearly = 0;
        $this->max_hotels = 1;
        $this->max_room_types_per_hotel = 5;
        $this->max_images_per_hotel = 5;
        $this->max_images_per_room = 3;
        $this->featured_listing = false;
        $this->priority_support = false;
        $this->advanced_analytics = false;
        $this->review_responses = false;
        $this->restaurant_management = false;
        $this->leisure_management = false;
        $this->custom_branding = false;
        $this->api_access = false;
        $this->priority_search = false;
        $this->promotions = false;
        $this->export_reports = false;
        $this->trial_days = 0;
        $this->sort_order = 0;
        $this->is_active = true;
        $this->is_popular = false;
        $this->is_free = false;
    }

    public function render()
    {
        $plans = Plan::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->ordered()
            ->paginate(10);

        $stats = [
            'total_plans' => Plan::count(),
            'active_plans' => Plan::where('is_active', true)->count(),
            'total_subscriptions' => Subscription::where('status', 'active')->count(),
            'monthly_revenue' => Subscription::where('status', 'active')
                ->where('billing_cycle', 'monthly')
                ->sum('amount_paid'),
        ];

        $viewingPlanSubscriptions = null;
        if ($this->viewingPlanId) {
            $viewingPlanSubscriptions = Subscription::where('plan_id', $this->viewingPlanId)
                ->with('user')
                ->latest()
                ->get();
        }

        return view('livewire.admin.plan-management', [
            'plans' => $plans,
            'stats' => $stats,
            'viewingPlanSubscriptions' => $viewingPlanSubscriptions,
        ])->layout('layouts.admin');
    }
}
