{{-- this is a livewire view. For the livewire to work, the whole view must have a wrapping div. --}}
<div x-data="{ show: false }"
    @keyup.enter=" if ({{ $texts->count() }} == 1) $wire.searchAction({{ $texts->first()->id ?? null }})"
    class="relative mb-4 space-y-6 text-gray-900">

    @section('title')
        {{ Str::ucfirst('Texts') }}
    @endsection

    @if (! $texts->isEmpty())

        <div class="max-w-lg">
            {{-- Category Select --}}
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700"> Category </label>
                <select wire:model="category"
                    class="mt-1 block w-full rounded border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                    <option value=""> Please Choose... </option>

                    <template x-for="(item, index) in {{ json_encode($categories) }}"
                        :key="index">
                        <option x-text="item"
                            :value="index"></option>
                    </template>

                </select>
            </div>
        </div>

        {{-- Search --}}
        <div class="max-w-lg">
            <label class="mb-2 block text-sm font-medium text-gray-700"> Search </label>
            <input
                class="h-10 w-full rounded-md border border-gray-300 bg-white px-5 pr-16 text-sm focus:outline-none focus:ring-indigo-600"
                wire:model="search"
                type="text"
                placeholder="search">

            {{-- Search errors --}}
            @error($search)
                <span>{{ $message }}</span>
            @enderror
        </div>

        {{-- Texts Wrapper --}}
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">Texts</label>
            {{-- Text Table Wrap --}}
            <div class="overflow-auto rounded-md border border-gray-300 text-sm shadow-sm"
                @click.away="show = false">
                {{-- Table --}}
                <table class="min-w-full divide-y divide-gray-300">
                    {{-- Table header --}}
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Category
                            </th>

                            @foreach($locales as $locale)
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                </th>
                            @endforeach

                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    {{-- Table Body --}}
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($texts as $index => $text)
                            <tr x-data="{ identifier: {{ json_encode($index) }} }">

                                {{-- Category --}}
                                <td class="px-6 py-4">
                                    {{ $text->category }}
                                </td>

                                @foreach($locales as $locale)
                                    <td class="px-6 py-4">
                                        {{-- Textarea --}}
                                        <div x-show="show === identifier">
                                            <textarea class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-400 focus:ring-indigo-400 sm:text-sm"
                                                wire:model.defer="texts.{{ $index }}.{{$locale}}" ></textarea>
                                            @error($texts[$index][$locale])
                                                <span>{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Text preview --}}
                                        <div x-show="show !== identifier">
                                            {{ Str::words($text->$locale, 15, '...') }}
                                        </div>
                                    </td>
                                @endforeach

                                <td class="px-6 py-4">
                                    {{-- Edit button --}}
                                    <div
                                        x-show="show !== identifier">
                                        {{-- Edit --}}
                                        <button @click="show = identifier"
                                            class="rounded bg-indigo-500 px-4 py-1 font-medium text-white">
                                            Edit
                                        </button>
                                    </div>

                                    {{-- Save or cancel --}}
                                    <div x-show="show === identifier" class="space-x-3">
                                        {{-- Save --}}
                                        <button
                                            class="inline-block rounded bg-green-500 px-4 py-1 font-medium text-white"
                                            @click="
                                                $wire.save(identifier);
                                                show = false;
                                            ">
                                            Save
                                        </button>
                                        {{-- Cancel --}}
                                        <button @click="show = false">Cancel</button>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        {{-- Show no results content --}}
        <div class="bg-white rounded shadow px-16 py-24 flex flex-col items-center text-center justify-center">
            <span class="block text-3xl font-semibold mb-2">There are currently no texts to show.</span>
            <span class="text-slate-700 text-lg">Visit a page with dynamic text strings to automatically populate the database.</span>
        </div>
    @endif
        
</div>
