{{-- this is a livewire view. For the livewire to work, the whole view must have a wrapping div. --}}
<div
    x-data="{show : false}"
>
    <div
        x-data="{
            isOpen : false,
        }"
        @click.away ="isOpen = false"
        @keyup.enter =" if ({{$texts->count()}} == 1) $wire.searchAction({{$texts->first()->id ?? null}})"
        class="text-gray-900 col-span-2 relative mb-4"
    >

        <div class="flex">
            <div class="py-2 px-2">
                <label class="block text-sm font-medium text-gray-700"> Category </label>
                <select wire:model="category" 
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option> Please Choose... </option>

                        <template x-for="(item, index) in {{json_encode($categories)}}" :key="index">
                            <option 
                                x-text="item"
                                :value="index"
                            ></option>
                        </template>

                </select>
            </div>
            <div class="grow py-2 px-2">
                <label class="block text-sm font-medium text-gray-700"> Search </label>
                <input
                    class="border border-gray-300 bg-white h-10 w-full px-5 pr-16 rounded-lg text-sm focus:outline-none focus:ring-indigo-900"
                    wire:model="search"
                    type="text"
                    placeholder="search"
                >
            </div>
            @error($search) 
                <span>{{ $message }}</span> 
            @enderror
        </div>

    </div>

    <div 
        class="text-sm shadow border border-gray-200 sm:rounded-lg overflow-hidden"
        @click.away="show = false"
    >
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>

                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Category
                    </th>
                    
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Text
                    </th>
                                            
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-100">
                @foreach($texts as $index => $text)
                    <tr
                        x-data="{identifier : {{json_encode($index)}} }"
                    >

                        <td class="text-gray-900 px-6 py-4 whitespace-nowrap">
                            {{$text->category}}                                        
                        </td>


                        
                        <td
                            x-show="show === identifier"
                        >
                            <div class = ''>
                                <textarea
                                    class="mt-1 focus:ring-indigo-400 focus:border-indigo-400 block w-full shadow-sm sm:text-sm border-gray-300 rounded"
                                    wire:model.defer="texts.{{$index}}.de"
                                ></textarea>
                                @error($texts[$index]['de']) 
                                    <span>{{ $message }}</span> 
                                @enderror
                            
                            </div>

                        </td>
        
                        <td 
                            class="text-gray-900 px-6 py-4 whitespace-nowrap"
                            x-show="show !== identifier"
                        >
                            {{Str::words($text->de, 15, '...')}}

                        </td>

                        <td 
                            class="text-gray-900 px-6 py-4 whitespace-nowrap"
                            x-show="show !== identifier"
                        >
                            <dyntext::btn.slot 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-indigo-500" 
                                @click="show = identifier"
                            >
                                Edit
                            </dyntext::btn.slot>
 
                        <td x-show="show === identifier">
                            <dyntext::btn.slot 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 bg-red-600 text-white hover:bg-red-700 focus:ring-red-500" 
                                @click="
                                    $wire.save(identifier);
                                    show = false;
                                " 
                            >
                                Save
                            </dyntext::btn.slot>
                        </td>
                    </tr>       
                @endforeach
            </tbody>
        </table>
    </div>
</div>
