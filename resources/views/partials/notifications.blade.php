@php
    $notificationMap = [
        'success' => ['class' => 'border-green-200 bg-green-50 text-green-700', 'label' => 'Success'],
        'deleted' => ['class' => 'border-green-200 bg-green-50 text-green-700', 'label' => 'Deleted'],
        'cancelled' => ['class' => 'border-amber-200 bg-amber-50 text-amber-700', 'label' => 'Cancelled'],
        'registered' => ['class' => 'border-green-200 bg-green-50 text-green-700', 'label' => 'Registration'],
        'error' => ['class' => 'border-red-200 bg-red-50 text-red-700', 'label' => 'Error'],
        'warning' => ['class' => 'border-amber-200 bg-amber-50 text-amber-700', 'label' => 'Warning'],
        'info' => ['class' => 'border-blue-200 bg-blue-50 text-blue-700', 'label' => 'Info'],
        'status' => ['class' => 'border-blue-200 bg-blue-50 text-blue-700', 'label' => 'Notice'],
    ];
@endphp

<div class="fixed left-1/2 top-4 z-[100] w-[min(30rem,94vw)] -translate-x-1/2 space-y-3">
    @if ($errors->any())
        <div class="notification-banner rounded-2xl border border-red-200/70 bg-white/90 px-4 py-3 text-sm text-red-700 shadow-lg ring-1 ring-black/5 backdrop-blur" data-timeout="8000">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 flex h-7 w-7 items-center justify-center rounded-full bg-red-100 text-red-600">
                    !
                </div>
                <div class="flex-1">
                    <div class="font-semibold">Please fix the errors below.</div>
                    <div class="mt-1">{{ $errors->first() }}</div>
                </div>
                <button type="button" class="rounded-full px-2 py-1 text-red-500/70 hover:bg-red-50 hover:text-red-600" data-notification-close aria-label="Close">✕</button>
            </div>
        </div>
    @endif

    @foreach ($notificationMap as $key => $config)
        @if (session($key))
            <div class="notification-banner rounded-2xl border px-4 py-3 text-sm shadow-lg ring-1 ring-black/5 backdrop-blur {{ $config['class'] }} {{ str_contains($config['class'], 'red') ? 'bg-white/90' : (str_contains($config['class'], 'amber') ? 'bg-white/90' : (str_contains($config['class'], 'blue') ? 'bg-white/90' : 'bg-white/90')) }}" data-timeout="6000">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex h-7 w-7 items-center justify-center rounded-full {{ str_contains($config['class'], 'red') ? 'bg-red-100 text-red-600' : (str_contains($config['class'], 'amber') ? 'bg-amber-100 text-amber-600' : (str_contains($config['class'], 'blue') ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600')) }}">
                        {{ str_contains($config['class'], 'red') ? '!' : (str_contains($config['class'], 'amber') ? '!' : (str_contains($config['class'], 'blue') ? 'i' : '✓')) }}
                    </div>
                    <div class="flex-1">
                        <span class="font-semibold">{{ $config['label'] }}:</span>
                        <span>{{ session($key) }}</span>
                    </div>
                    <button type="button" class="rounded-full px-2 py-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600" data-notification-close aria-label="Close">✕</button>
                </div>
            </div>
        @endif
    @endforeach
</div>

<script>
    (function () {
        const banners = document.querySelectorAll('.notification-banner');
        banners.forEach((banner) => {
            const timeout = parseInt(banner.getAttribute('data-timeout') || '0', 10);
            const closeBtn = banner.querySelector('[data-notification-close]');

            const hide = () => {
                banner.style.transition = 'opacity 250ms ease, transform 250ms ease, max-height 250ms ease';
                banner.style.opacity = '0';
                banner.style.transform = 'translateY(-4px)';
                banner.style.maxHeight = '0';
                banner.style.margin = '0';
                banner.style.padding = '0';
                setTimeout(() => banner.remove(), 300);
            };

            if (closeBtn) closeBtn.addEventListener('click', hide);
            if (timeout > 0) setTimeout(hide, timeout);
        });
    })();
</script>
