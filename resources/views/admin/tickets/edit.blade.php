@extends('layouts.app')

@section('title', 'Edit Ticket')

@section('content')

<div class="bg-white p-6 rounded-2xl shadow max-w-3xl">

    <h2 class="text-2xl font-bold mb-6">
        Edit Ticket
    </h2>

    <form action="{{ route('tickets.update', $ticket->id) }}"
      method="POST">

        @csrf
        @method('PUT')

        <div class="mb-4">

            <label class="block mb-2 font-medium">
                Museum
            </label>

            <select name="museum_id"
                    class="w-full border rounded-lg px-4 py-2">

                @foreach ($museums as $museum)

<option value="{{ $museum->id }}" {{ $ticket->museum_id == $museum->id ? 'selected' : '' }}>

    {{ $museum->name }}

</option>

@endforeach

            </select>

        </div>

        <div class="mb-4">

            <label class="block mb-2 font-medium">
                Kategori
            </label>

            <input type="text"
                   value="{{ $ticket->ticket_name }}"
                   class="w-full border rounded-lg px-4 py-2">

        </div>

        <div class="mb-4">

            <label class="block mb-2 font-medium">
                Price
            </label>

            <input type="number"
                   value="{{ $ticket->price }}"
                   class="w-full border rounded-lg px-4 py-2">

        </div>

        <div class="mb-6">

            <label class="block mb-2 font-medium">
                Total tiket
            </label>

            <input type="number"
                   value="{{ $ticket->slot }}"
                   class="w-full border rounded-lg px-4 py-2">

        </div>

        <button type="submit"
                class="bg-black text-white px-6 py-2 rounded-lg">
            Update Ticket
        </button>

    </form>

</div>

@endsection
