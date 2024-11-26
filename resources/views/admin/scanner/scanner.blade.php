<x-admin-layout>
    <div class="flex justify-center items-center h-screen mt-[-10%]">
        <div class="text-center">
            <h1 class="text-3xl font-bold mb-8">Scan your QR here:</h1>
            <!-- QR Code Scanner Container -->
            <div id="reader" class="relative w-[300px] h-[330px] border-2 border-gray-800 rounded-lg overflow-hidden">
                <div id="scan-line" class="absolute top-0 left-0 w-full h-[3px] bg-green-500 animate-scan"></div>
            </div>
        </div>
    </div>

    <!-- Display success or error message -->
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form id="attendance-form" action="{{ route('admin_officer.scanner.store') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="user_id" id="user_id">
        <input type="hidden" name="event_id" id="event_id" value="{{ $event->id }}">
        <button type="submit" class="btn btn-primary">Submit Attendance</button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@latest/dist/html5-qrcode.min.js"></script>

    <script>
        const config = {
            fps: 30, // Frames per second
            qrbox: 200 // QR box size
        };

        const scanner = new Html5QrcodeScanner("reader", config);

        const success = (data) => {

            document.getElementById('user_id').value = data;
            document.getElementById('attendance-form').submit();

            scanner.clear();
            document.getElementById('reader').style.display = 'none';

            // console.log('Scanned Data:', data);
            // console.log('Event ID:', "{{ $event->id }}");
        };

        const error = (err) => {
            console.error("QR Code scanning failed: " + err);
        };

        // Start the scanner
        scanner.render(success, error);
    </script>

</x-admin-layout>

<!-- Tailwind Keyframes for the Scan Animation -->
<style>
    @keyframes scan {
        0% {
            transform: translateY(0);
        }

        100% {
            transform: translateY(100%);
        }
    }
</style>
