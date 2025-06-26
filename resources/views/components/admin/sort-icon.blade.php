@props(['field' => null, 'sortField' => null, 'sortDirection' => 'asc'])

@if($sortField !== $field)
    <i class="fas fa-sort text-gray-400 ml-1"></i>
@elseif($sortDirection === 'asc')
    <i class="fas fa-sort-up text-indigo-600 dark:text-indigo-400 ml-1"></i>
@else
    <i class="fas fa-sort-down text-indigo-600 dark:text-indigo-400 ml-1"></i>
@endif
