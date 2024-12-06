<x-admin-layout>
    <div class="flex justify-center items-center h-screen mt-[-10%]">
        <div class="text-center">
            <h1 class="text-3xl mt-20 font-bold mb-8">Scan your QR here:</h1>

            <!-- Dropdown for selecting time -->
            <div class="mb-6">
                <label for="attendance-time" class="block text-lg font-semibold mb-2">Select Attendance Time:</label>
                <select id="attendance-time" class="w-full p-2 border border-gray-300 rounded-md">
                    <option value="morning_in">Morning In</option>
                    <option value="morning_out">Morning Out</option>
                    <option value="afternoon_in">Afternoon In</option>
                    <option value="afternoon_out">Afternoon Out</option>
                </select>
            </div>

            <!-- QR Code Scanner Container -->
            <div id="reader"
                class="relative w-[300px] h-[330px] border-2 border-gray-800 rounded-lg overflow-hidden">
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
        <input type="hidden" name="attendance_time" id="attendance_time">
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
            const attendanceTime = document.getElementById('attendance-time').value;
            document.getElementById('user_id').value = data;
            document.getElementById('attendance_time').value = attendanceTime;
            document.getElementById('attendance-form').submit();

            scanner.clear();
            document.getElementById('reader').style.display = 'none';
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
