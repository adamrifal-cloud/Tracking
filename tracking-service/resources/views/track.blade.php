<x-app-layout>
    <x-slot:title>Tracking - Lacak Pengiriman</x-slot:title>

    <div class="w-full max-w-md flex flex-col gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h2 class="text-xl font-bold mb-2 text-gray-800">Lacak Pengiriman Anda</h2>
            <p class="text-xs text-gray-500 mb-4">Masukkan kode Order ID untuk melihat status kurir secara real-time.</p>
            <div class="flex space-x-2">
                <input type="text" id="search_order_id" placeholder="Contoh: ORD-1002" class="w-full p-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 text-sm">
                <button onclick="searchTracking()" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition text-sm shadow-sm">Cari</button>
            </div>
        </div>

        <div id="resultCard" class="hidden bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-4">Informasi Live Tracking</h3>
            <div class="border-b border-gray-100 pb-3 mb-4">
                <p class="text-xs text-gray-500">Nomor Resi / Order ID</p>
                <p id="res_order_id" class="text-lg font-bold text-gray-800"></p>
            </div>
            
            <div class="relative border-l-2 border-blue-500 ml-2.5 space-y-6">
                <div class="relative">
                    <div class="absolute -left-[16px] top-1 bg-blue-500 h-3.5 w-3.5 rounded-full border-2 border-white shadow-sm animate-pulse"></div>
                    <div class="pl-5">
                        <p class="font-bold text-gray-800 text-sm" id="res_status"></p>
                        <p class="text-[11px] text-gray-400 mt-0.5" id="res_time"></p>
                        <p class="text-xs text-gray-600 mt-2 bg-blue-50 p-2.5 rounded-lg border border-blue-100/50">
                            Sistem mengonfirmasi Driver ID <span id="res_driver_id" class="font-bold text-blue-700"></span> ditugaskan untuk rute ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div id="notFoundCard" class="hidden bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl text-center text-sm font-medium">
            ⚠ Nomor resi / Order ID tidak ditemukan di sistem tracking.
        </div>
    </div>

    <script>
        async function searchTracking() {
            const orderId = document.getElementById('search_order_id').value;
            const resultCard = document.getElementById('resultCard');
            const notFoundCard = document.getElementById('notFoundCard');

            if(!orderId) return;

            try {
                const response = await fetch(`/api/v1/track/${orderId}`);
                const res = await response.json();

                if (response.ok) {
                    notFoundCard.classList.add('hidden');
                    
                    document.getElementById('res_order_id').innerText = res.data.order_id;
                    document.getElementById('res_status').innerText = res.data.status_pengiriman;
                    document.getElementById('res_driver_id').innerText = res.data.driver_id;
                    
                    const date = new Date(res.data.terakhir_diupdate);
                    document.getElementById('res_time').innerText = date.toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' });

                    resultCard.classList.remove('hidden');
                } else {
                    resultCard.classList.add('hidden');
                    notFoundCard.classList.remove('hidden');
                }
            } catch (error) {
                console.error(error);
            }
        }
    </script>
</x-app-layout>