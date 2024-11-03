<x-layouts.app>
  <form action="{{ route('index') }}" class="mt-12 flex justify-end items-center gap-x-3">
    <select name="per_page" id="per_page" class="border p-2 rounded-md">
      <option value="10" {{ request()->per_page == 10 ? 'selected' : '' }}>10</option>
      <option value="20" {{ request()->per_page == 20 ? 'selected' : '' }}>20</option>
      <option value="50" {{ request()->per_page == 50 ? 'selected' : '' }}>50</option>
      <option value="100" {{ request()->per_page == 100 ? 'selected' : '' }}>100</option>
    </select>
    <select name="donation_type" id="donation_type" class="border p-2 rounded-md">
      <option value="">All Types</option>
      @foreach ($donation_types as $donation_type)
        <option value="{{ $donation_type }}" {{ request()->donation_type == $donation_type ? 'selected' : '' }}>
          {{ $donation_type }}</option>
      @endforeach
    </select>
    <select name="status" id="status" class="border p-2 rounded-md">
      <option value="">All Statuses</option>
      @foreach ($statuses as $status)
        <option value="{{ $status }}" {{ request()->status == $status ? 'selected' : '' }}>{{ ucfirst($status) }}
        </option>
      @endforeach
    </select>
    <input type="text" name="search" id="search" placeholder="Search here..." class="border p-2 rounded-md"
      value="{{ request()->search }}">
    <button type="submit" class="border py-2 px-4 bg-blue-500 hover:bg-blue-700 text-white rounded-md">Filter</button>
  </form>

  <table class="overflow-auto table-auto border-collapse mt-6 w-full mx-auto">
    <thead>
      <tr>
        <th class="border p-2">#</th>
        <th class="border p-2">Donor Name</th>
        <th class="border p-2">Donation Type</th>
        <th class="border p-2">Amount</th>
        <th class="border p-2">Status</th>
        <th class="border p-2">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($donations as $donation)
        @php
          $color = '';
          switch ($donation->status) {
              case 'pending':
                  $color = 'bg-gray-500';
                  break;
              case 'success':
                  $color = 'bg-green-500';
                  break;
              case 'failed':
                  $color = 'bg-red-500';
                  break;
              case 'expired':
                  $color = 'bg-black';
                  break;
          }
        @endphp

        <tr class="text-center">
          <td class="border p-2">{{ $loop->iteration }}</td>
          <td class="border p-2">{{ $donation->donor_name }}</td>
          <td class="border p-2">{{ ucwords(str_replace('_', '', $donation->donation_type)) }}</td>
          <td class="border p-2">Rp. {{ number_format($donation->amount, 0, ',', '.') }}</td>
          <td class="border p-2"><span
              class="{{ $color }} inline-block text-white text-sm py-1 px-3 rounded-full">{{ ucfirst($donation->status) }}</span>
          </td>
          <td class="border p-2">
            @if ($donation->status == 'pending')
              <button class="border py-1 px-3 bg-green-500 hover:bg-green-700 text-white rounded-md"
                onclick="snap.pay('{{ $donation->snap_token }}')">Pay</button>
            @else
              No action needed
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="mt-6 mb-12">
    {{ $donations->links() }}
  </div>
</x-layouts.app>
