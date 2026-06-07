@props(['style' => session('flash.bannerStyle', 'success'), 'message' => session('flash.banner')])

<div x-data="{{ json_encode(['show' => true, 'style' => $style, 'message' => $message]) }}"
    :class="{ 'alert alert-success': style == 'success', 'alert alert-danger': style == 'danger', 'alert alert-warning': style == 'warning', 'alert alert-secondary': style != 'success' && style != 'danger' && style != 'warning'}"
            style="display: none; border-radius: 0;"
            x-show="show && message"
            x-on:banner-message.window="
                style = event.detail.style;
                message = event.detail.message;
                show = true;
            ">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <span class="d-flex p-2 rounded-2 me-3" :class="{ 'bg-success': style == 'success', 'bg-danger': style == 'danger', 'bg-warning': style == 'warning', 'bg-secondary': style != 'success' && style != 'danger' && style != 'warning' }">
                <svg x-show="style == 'success'" class="icon-base ti tabler-check" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg x-show="style == 'danger'" class="icon-base ti tabler-alert-circle" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <svg x-show="style == 'warning'" class="icon-base ti tabler-alert-triangle" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4v.01 0 0 " />
                </svg>
                <svg x-show="style != 'success' && style != 'danger' && style != 'warning'" class="icon-base ti tabler-info-circle" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
            </span>
            <span class="fw-medium" x-text="message"></span>
        </div>
        <button type="button" class="btn-close" aria-label="Dismiss" x-on:click="show = false"></button>
    </div>
</div>
