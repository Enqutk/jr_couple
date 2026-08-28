<x-filament-widgets::widget>
    @php
        $organizationUrl = route('filament.mgt.resources.organizations.index');
    @endphp
    <x-filament::section heading="Quick links" description="Jump to the parts of JR Couple you manage most often.">
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <a
                href="{{ route('filament.mgt.resources.entities.index') }}"
                class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-4 transition hover:border-primary-500 hover:shadow-sm dark:border-gray-700 dark:bg-gray-900"
            >
                <x-filament::icon icon="heroicon-o-shopping-bag" class="h-6 w-6 text-primary-500" />
                <span>
                    <strong class="block text-sm font-semibold text-gray-950 dark:text-white">Store products</strong>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Prices, images, featured hero</span>
                </span>
            </a>

            <a
                href="{{ route('filament.mgt.resources.services.index') }}"
                class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-4 transition hover:border-primary-500 hover:shadow-sm dark:border-gray-700 dark:bg-gray-900"
            >
                <x-filament::icon icon="heroicon-o-squares-2x2" class="h-6 w-6 text-primary-500" />
                <span>
                    <strong class="block text-sm font-semibold text-gray-950 dark:text-white">JR brands</strong>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Ketema, Mobile, Real Estate, Hair</span>
                </span>
            </a>

            <a
                href="{{ $organizationUrl }}"
                class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-4 transition hover:border-primary-500 hover:shadow-sm dark:border-gray-700 dark:bg-gray-900"
            >
                <x-filament::icon icon="heroicon-o-cog-6-tooth" class="h-6 w-6 text-primary-500" />
                <span>
                    <strong class="block text-sm font-semibold text-gray-950 dark:text-white">Site settings</strong>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Theme, payment, logo</span>
                </span>
            </a>

            <a
                href="{{ route('store.index') }}"
                target="_blank"
                rel="noopener"
                class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-4 transition hover:border-primary-500 hover:shadow-sm dark:border-gray-700 dark:bg-gray-900"
            >
                <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="h-6 w-6 text-primary-500" />
                <span>
                    <strong class="block text-sm font-semibold text-gray-950 dark:text-white">View live store</strong>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Open /store in a new tab</span>
                </span>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
