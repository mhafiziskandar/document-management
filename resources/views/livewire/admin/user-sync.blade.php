<div>
    <div class="mt-3" x-cloak x-data="{ isVisible: @entangle('isVisible').defer }">
        <div class="d-flex flex-lg-row flex-column align-items-center justify-content-between">
            <button @click.prevent="$wire.request()" class="btn btn-sm btn-primary mb-3" wire:loading.attr="disabled" wire:target="request" {{ $isVisible ? '' : 'disabled' }}>
                <span wire:loading.remove.delay.longer wire:target="request"><i class="fa-solid fa-rotate me-2"></i>Sync User</span>
                <span wire:loading.delay.longer wire:target="request">Syncing user please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
    </div>
</div>
