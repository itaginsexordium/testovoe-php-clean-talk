@extends('layouts.app')



@section('content')
    <div class="mb-6 px-4">


        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <form action={{ route('main') }}>
                <div class="mb-6 px-12 ">
                    <label for="ip" class="block mb-2 text-sm font-medium text-white dark:text-white">IP</label>
                    <input type="ip" value="{{ request()->ip }}" name="ip" id="ip"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="8.8.8.8" required>
                    <fieldset>
                        <div class="flex items-center mb-4">
                            <input @if (request()->type == 'view' || request()->type === null) checked @endif id="country-option-1" type="radio"
                                name="type" value="view"
                                class="w-4 h-4 border-gray-300 focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-600 dark:focus:bg-blue-600 dark:bg-gray-700 dark:border-gray-600">
                            <label for="country-option-1"
                                class="block ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                view
                            </label>

                            <input @if (request()->type == 'json') checked @endif id="country-option-2" type="radio"
                                name="type" value="json"
                                class="w-4 h-4 border-gray-300 focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-600 dark:focus:bg-blue-600 dark:bg-gray-700 dark:border-gray-600">
                            <label for="country-option-2"
                                class="block ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                json
                            </label>
                        </div>

                        @isset($error)
                            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                                role="alert">
                                <span class="font-medium">Ошибка!</span> {{ $error }}.
                            </div>
                        @endisset

                        <div class="flex items-center mb-4">
                            <button type="submit"
                                class="text-white bg-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">Вычислить</button>
                        </div>

            </form>

            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            #
                        </th>
                        <th scope="col" class="px-6 py-3">
                            ip
                        </th>
                        <th scope="col" class="px-6 py-3">
                            mask
                        </th>
                        <th scope="col" class="px-6 py-3">
                            country
                        </th>
                        <th>

                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($networks as $network)
                        <tr class="border-b bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-blue-900 whitespace-nowrap dark:text-white">
                                {{ $network->id ?? '' }}
                            </th>
                            <td class="px-6 py-4">

                                {{ $network->network_address ?? '' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $network->netmask ?? '' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $network->country->country_name ?? '' }}
                            </td>
                        </tr>

                    @empty
                        <tr class="no-data text-center">
                            <td colspan="4">
                                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                                    role="alert">
                                    <span class="font-medium">Записей не обнаруженно</span>.
                                </div>

                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if (isset($networks) && !is_array($networks) && count($networks->links()->elements[0]) > 1)
                <div>{{ $networks->withQueryString()->links('') }}</div>
            @endif
        </div>

    </div>
@endsection
