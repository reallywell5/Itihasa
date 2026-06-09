@extends('layouts.app')

@section('title', 'Add Ticket')

@section('content')

<div class="bg-white p-6 rounded-2xl shadow max-w-3xl">

    <h2 class="text-2xl font-bold mb-6">
        Add Ticket
    </h2>

    <form action="{{ route('tickets.store') }}"
      method="POST">

        @csrf

        <!-- Museum -->
        <div class="mb-4">

            <label class="block mb-2 font-medium">
                Nama Museum
            </label>

            <select name="museum_id"
                    class="w-full border rounded-lg px-4 py-2">

                @foreach ($museums as $museum)

<option value="{{ $museum->id }}">

    {{ $museum->name }}

</option>

@endforeach

            </select>

        </div>

        <!-- Kategori -->
        <div class="mb-4">

            <label class="block mb-2 font-medium">
                Kategori
            </label>

            <input type="text"
                   name="ticket_name"
                   class="w-full border rounded-lg px-4 py-2">

        </div>

        <!-- Price -->
        <div class="mb-4">

            <label class="block mb-2 font-medium">
                Harga
            </label>

            <input type="number"
                   name="price"
                   class="w-full border rounded-lg px-4 py-2">

        </div>

        <!-- Total tiket-->
        <div class="mb-6">

            <label class="block mb-2 font-medium">
                Total tiket
            </label>

            <input type="number"
                   name="slot"
                   class="w-full border rounded-lg px-4 py-2">

        </div>

        <button type="submit"
                class="bg-black text-white px-6 py-2 rounded-lg">
            Save Ticket
        </button>

    </form>

</div>

@endsection
