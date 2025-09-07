<x-filament::page>
    <h2 class="text-xl font-bold mb-4">Bill #{{ $bill->id }}</h2>

    <div class="mb-4">
        <p><strong>Patient:</strong> {{ $bill->patient->name }}</p>
        <p><strong>Department:</strong> {{ $bill->department->name }}</p>
        <p><strong>Amount:</strong> ${{ number_format($bill->amount, 2) }}</p>
        <p><strong>Bill Date:</strong> {{ $bill->bill_date->format('Y-m-d') }}</p>
        <p><strong>Status:</strong> {{ $bill->payment_status }}</p>
        <p><strong>Description:</strong> {{ $bill->description }}</p>
    </div>

    <h3 class="text-lg font-semibold mb-2">Consumed Inventories</h3>
    <table class="table-auto w-full border">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2 border">Item</th>
                <th class="px-4 py-2 border">Quantity</th>
                <th class="px-4 py-2 border">Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bill->inventories as $inventory)
                <tr>
                    <td class="px-4 py-2 border">{{ $inventory->name }}</td>
                    <td class="px-4 py-2 border">{{ $inventory->pivot->quantity }}</td>
                    <td class="px-4 py-2 border">${{ number_format($inventory->pivot->cost, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-filament::page>
    