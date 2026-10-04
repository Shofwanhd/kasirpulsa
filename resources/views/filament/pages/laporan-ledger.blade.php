<x-filament-panels::page>
    <div class="md:max-w-3xl md:mx-auto bg-white p-6 rounded-xl shadow dark:bg-zinc-700" id="print-area">
        <div class="pt-4">
            {{ $this->form }}
        </div>

        <div style="margin-top: 1.5rem;">
            <div>
                <x-filament::button type="button" color="primary" wire:click="downloadExcel" wire:loading.attr="disabled"
                    wire:target="downloadExcel">
                    Download Excel
                </x-filament::button>
            </div>
        </div>
    </div>
</x-filament-panels::page>
