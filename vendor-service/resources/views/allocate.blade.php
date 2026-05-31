<x-app-layout>
    <x-slot:title>Vendor - Alokasi Driver</x-slot:title>

    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-1 text-gray-800">Alokasi Driver</h2>
        <p class="text-xs text-gray-500 mb-6">Kirim tugas pengiriman baru ke antrean sistem.</p>
        
        <div id="alert" class="hidden mb-4 p-3 rounded-lg text-white text-sm"></div>

        <form id="allocationForm" class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500">Order ID</label>
                <input type="text" id="order_id" placeholder="Contoh: ORD-1002" class="w-full p-2.5 border border-gray-300 rounded-lg mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" required>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500">ID Driver</label>
                <input type="number" id="driver_id" placeholder="Contoh: 15" class="w-full p-2.5 border border-gray-300 rounded-lg mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" required>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500">Nama Driver</label>
                <input type="text" id="driver_name" placeholder="Nama Lengkap" class="w-full p-2.5 border border-gray-300 rounded-lg mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" required>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500">Nama Vendor</label>
                <input type="text" id="vendor_name" placeholder="Nama Perusahaan" class="w-full p-2.5 border border-gray-300 rounded-lg mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-lg font-semibold hover:bg-blue-700 transition shadow-sm mt-2">
                Kirim ke Jaringan Distribusi 🚀
            </button>
        </form>
    </div>

    <script>
        document.getElementById('allocationForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const alertDiv = document.getElementById('alert');
            
            const data = {
                order_id: document.getElementById('order_id').value,
                driver_id: parseInt(document.getElementById('driver_id').value),
                driver_name: document.getElementById('driver_name').value,
                vendor_name: document.getElementById('vendor_name').value,
            };

            try {
                const response = await fetch('/api/v1/allocate-driver', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();

                if (response.ok) {
                    alertDiv.className = "mb-4 p-3 rounded-lg text-white text-sm bg-green-500 font-medium";
                    alertDiv.innerText = "✓ " + result.message;
                    document.getElementById('allocationForm').reset();
                } else {
                    alertDiv.className = "mb-4 p-3 rounded-lg text-white text-sm bg-red-500 font-medium";
                    alertDiv.innerText = "✗ Gagal memproses data. Cek kembali inputan.";
                }
                alertDiv.classList.remove('hidden');
            } catch (error) {
                console.error(error);
            }
        });
    </script>
</x-app-layout>