<x-layouts.app>
  <form action="#" method="POST" id="donation_form" class="mt-12 w-3/4 mx-auto">
    <div class="mb-6 md:flex justify-center gap-3">
      <div class="w-full">
        <label class="block mb-2" for="donor_name">Donor Name</label>
        <input type="text" name="donor_name" id="donor_name" placeholder="Enter your name..."
          class="border p-3 rounded-md w-full" required>
      </div>
      <div class="w-full">
        <label class="block mb-2" for="donor_email">Donor Email</label>
        <input type="email" name="donor_email" id="donor_email" placeholder="Enter your email..."
          class="border p-3 rounded-md w-full" required>
      </div>
    </div>
    <div class="mb-6 md:flex justify-center gap-3">
      <div class="w-full">
        <label class="block mb-2" for="donation_type">Donation Type</label>
        <select name="donation_type" id="donation_type" class="border p-3 rounded-md w-full" required>
          <option value="">Select donation type...</option>
          @foreach ($donation_types as $donation_type)
            <option value="{{ $donation_type }}">{{ $donation_type }}</option>
          @endforeach
        </select>
      </div>
      <div class="w-full">
        <label class="block mb-2" for="amount">Amount</label>
        <input type="number" name="amount" id="amount" placeholder="Enter amount..."
          class="border p-3 rounded-md w-full" required>
      </div>
    </div>
    <div class="mb-6">
      <label class="block mb-2" for="note">Note (Optional)</label>
      <textarea rows="6" name="note" id="note" placeholder="Add note..." class="border p-3 rounded-md w-full"></textarea>
    </div>
    <div class="mb-12">
      <button class="border py-2 px-4 bg-blue-500 hover:bg-blue-700 text-white rounded-md"
        type="submit">Donate</button>
    </div>
  </form>

  @push('script')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('donation_form').addEventListener('submit', function(event) {
          event.preventDefault();
          axios.post('/api/donate', {
            _token: "{{ csrf_token() }}",
            donor_name: document.getElementById('donor_name').value,
            donor_email: document.getElementById('donor_email').value,
            donation_type: document.getElementById('donation_type').value,
            amount: document.getElementById('amount').value,
            note: document.getElementById('note').value,
          }).then(response => {
            console.log(response.data);
            snap.pay(response.data.snap_token, {
              onSuccess: function(result) {
                console.log(JSON.stringify(result, null, 2));
                window.location.href = "{{ route('index') }}";
              },
              onPending: function(result) {
                console.log(JSON.stringify(result, null, 2));
                window.location.href = "{{ route('index') }}";
              },
              onError: function(result) {
                console.log(JSON.stringify(result, null, 2));
                window.location.href = "{{ route('index') }}";
              }
            });
            return false;
          });
        });
      });
    </script>
  @endpush
</x-layouts.app>
