<x-admin-layout>
    <div class="flex justify-center items-center h-screen mt-[-5%] flex-col">

        <div class="text-center relative" id="scanner-container">
            <h1 class="text-3xl mt-10 font-bold mb-2">Scan your QR here:</h1>
            <h2 class="text-2xl mt-2 font-bold mb-2">| {{ $event->name }} | </h2>

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
                class="relative w-[300px] h-[330px] border-2 border-gray-800 rounded-lg overflow-hidden mx-auto">
                <div id="scan-line" class="absolute top-0 left-0 w-full h-[3px] bg-green-500 animate-scan"></div>
            </div>
        </div>


        <div class="text-center relative" id="response-container">
            <h1 class="text-3xl text-red-500 mt-10 font-bold mb-2">Admin disabled the Scanner!!</h1>
        </div>

        <!-- Form to update remarks below the scanner, centered -->
        <div class="w-full flex justify-center mt-4">
            <form action="{{ route('admin.events.updateRemarks', $event->id) }}" method="POST" id="remarks-form">
                @csrf
                @method('PATCH') <!-- Use PATCH to update the remarks -->
                <input type="hidden" name="remarks" id="remarks-input" value="{{ $event->remarks }}">
                @role('admin')
                    <button type="submit" id="toggle-scanner" class="bg-red-500 text-white p-3 rounded-lg">
                        @if ($event->remarks == 'disable')
                            Enable Scanner
                        @else
                            Disable Scanner
                        @endif
                    </button>
                @endrole
            </form>
        </div>
    </div>

    <form id="attendance-form" action="{{ route('admin_officer.scanner.store') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="user_id" id="user_id">
        <input type="hidden" name="event_id" id="event_id" value="{{ $event->id }}">
        <input type="hidden" name="attendance_time" id="attendance_time">
        <button type="submit" class="btn btn-primary">Submit Attendance</button>
    </form>
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

<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@latest/dist/html5-qrcode.min.js"></script>

<script>
    const toggleButton = document.getElementById('toggle-scanner');
    const remarksInput = document.getElementById('remarks-input');
    const remarksForm = document.getElementById('remarks-form');
    const scannerContainer = document.getElementById('scanner-container');
    const responseContainer = document.getElementById('response-container');

    // Function to toggle scanner visibility based on remarks
    const updateScannerVisibility = () => {
        if (remarksInput.value === 'disable') {
            scannerContainer.style.display = 'none';
            responseContainer.style.display = 'block';
            if (toggleButton) toggleButton.innerHTML = 'Enable Scanner';
        } else {
            scannerContainer.style.display = 'block';
            responseContainer.style.display = 'none';
            if (toggleButton) toggleButton.innerHTML = 'Disable Scanner';
        }
    };

    // Initial check on page load
    updateScannerVisibility();

    // Check if toggleButton exists (only for admin role)
    if (toggleButton) {
        toggleButton.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default form submission behavior

            // Toggle remarks value
            remarksInput.value = remarksInput.value === 'disable' ? 'enable' : 'disable';

            // Update scanner visibility
            updateScannerVisibility();

            // Submit the form to save the remarks state
            remarksForm.submit();
        });
    }


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
