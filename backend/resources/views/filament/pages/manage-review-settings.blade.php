<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        @if (($data['display_mode'] ?? 'manual') === 'iframe')
            <x-filament::section heading="Hasil bacaan">
                @if ($iframeError)
                    <p class="text-sm text-danger-600">{{ $iframeError }}</p>
                @elseif ($iframeReading)
                    <dl class="grid gap-3 text-sm sm:grid-cols-2">
                        <div>
                            <dt class="font-semibold">Penyedia</dt>
                            <dd>{{ $iframeReading['provider_label'] }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold">Domain</dt>
                            <dd>{{ $iframeReading['host'] }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold">Ukuran</dt>
                            <dd>{{ $iframeReading['width'] ?? 'Otomatis' }} × {{ $iframeReading['height'] ?? 'Otomatis' }} px</dd>
                        </div>
                        <div>
                            <dt class="font-semibold">Judul</dt>
                            <dd>{{ $iframeReading['title'] ?? 'Tidak dicantumkan' }}</dd>
                        </div>
                        @if ($iframeReading['place_name'])
                            <div class="sm:col-span-2">
                                <dt class="font-semibold">Tempat</dt>
                                <dd>{{ $iframeReading['place_name'] }}</dd>
                            </div>
                        @endif
                        @if ($iframeReading['youtube_id'])
                            <div class="sm:col-span-2">
                                <dt class="font-semibold">ID Video YouTube</dt>
                                <dd>{{ $iframeReading['youtube_id'] }}</dd>
                            </div>
                        @endif
                    </dl>

                    <div class="mt-4 overflow-hidden rounded-lg border">
                        <iframe
                            src="{{ $iframeReading['src'] }}"
                            title="{{ $iframeReading['title'] ?? 'Pratinjau iframe' }}"
                            width="100%"
                            height="{{ min(max($iframeReading['height'] ?? 360, 300), 600) }}"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            sandbox="allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"
                        ></iframe>
                    </div>
                @else
                    <p class="text-sm text-gray-500">Tempel kode iframe lalu keluar dari kolom untuk membaca dan menampilkan pratinjau.</p>
                @endif
            </x-filament::section>
        @endif

        <x-filament-panels::form.actions :actions="$this->getFormActions()" />
    </x-filament-panels::form>
</x-filament-panels::page>
