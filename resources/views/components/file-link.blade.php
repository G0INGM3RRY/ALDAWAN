@props([
    'path' => null,
    'label' => 'View Document',
    'icon' => 'fas fa-file-pdf',
    'btnClass' => 'btn btn-primary btn-sm',
    'showMissing' => true
])

@php
    use App\Helpers\FileHelper;
    $fileExists = FileHelper::exists($path);
    $fileUrl = $fileExists ? Storage::url($path) : null;
@endphp

@if($path)
    @if($fileExists)
        <!-- File exists - show view link -->
        <a href="{{ $fileUrl }}" 
           target="_blank" 
           class="{{ $btnClass }}"
           title="{{ $label }}">
            <i class="{{ $icon }} me-1"></i>{{ $label }}
        </a>
    @elseif($showMissing)
        <!-- File missing - show warning -->
        <button type="button" 
                class="btn btn-warning btn-sm" 
                disabled
                title="File not found - may have been uploaded on another device"
                data-bs-toggle="tooltip">
            <i class="fas fa-exclamation-triangle me-1"></i>File Missing
        </button>
    @endif
@else
    <!-- No path provided -->
    @if($showMissing)
        <span class="text-muted small">
            <i class="fas fa-times me-1"></i>Not uploaded
        </span>
    @endif
@endif
