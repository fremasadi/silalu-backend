{{-- resources/views/filament/forms/components/leaflet-map.blade.php --}}

<div class="space-y-2">
    <div class="text-sm font-medium text-gray-700 dark:text-gray-200">
        Klik pada peta untuk memilih lokasi
    </div>
    
    <div id="map" class="w-full h-96 rounded-lg border border-gray-300 dark:border-gray-600" wire:ignore></div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map - Default to Kediri, Jawa Timur
    const defaultLat = -7.8169; // Kediri latitude
    const defaultLng = 112.0176; // Kediri longitude
    
    const lat = {{ $latitude ?? -7.8169 }};
    const lng = {{ $longitude ?? 112.0176 }};
    
    const map = L.map('map').setView([lat, lng], 14);
    
    // Add tile layer dengan bounds terbatas untuk area Kediri
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18,
        minZoom: 11
    }).addTo(map);
    
    // Set bounds untuk area Kediri dan sekitarnya
    const kediriBounds = L.latLngBounds(
        [-7.9000, 111.9000], // Southwest coordinates
        [-7.7300, 112.1000]  // Northeast coordinates
    );
    
    // Batasi map view ke area Kediri
    map.setMaxBounds(kediriBounds);
    map.on('drag', function() {
        map.panInsideBounds(kediriBounds, { animate: false });
    });
    
    // Add marker
    let marker = L.marker([lat, lng]).addTo(map);
    
    // Update coordinates on map click
    map.on('click', function(e) {
        const newLat = e.latlng.lat;
        const newLng = e.latlng.lng;
        
        // Update marker position
        marker.setLatLng([newLat, newLng]);
        
        // Multiple selectors untuk Filament form structure
        const latitudeSelectors = [
            'input[name="latitude"]',
            'input[id*="latitude"]',
            'input[wire\\:model*="latitude"]',
            'input[data-field="latitude"]'
        ];
        
        const longitudeSelectors = [
            'input[name="longitude"]',
            'input[id*="longitude"]', 
            'input[wire\\:model*="longitude"]',
            'input[data-field="longitude"]'
        ];
        
        // Cari dan update latitude input
        let latitudeInput = null;
        for (let selector of latitudeSelectors) {
            latitudeInput = document.querySelector(selector);
            if (latitudeInput) break;
        }
        
        // Cari dan update longitude input
        let longitudeInput = null;
        for (let selector of longitudeSelectors) {
            longitudeInput = document.querySelector(selector);
            if (longitudeInput) break;
        }
        
        if (latitudeInput) {
            latitudeInput.value = newLat.toFixed(8);
            // Trigger berbagai event untuk memastikan Filament mendeteksi perubahan
            latitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
            latitudeInput.dispatchEvent(new Event('change', { bubbles: true }));
            latitudeInput.dispatchEvent(new Event('blur', { bubbles: true }));
        }
        
        if (longitudeInput) {
            longitudeInput.value = newLng.toFixed(8);
            // Trigger berbagai event untuk memastikan Filament mendeteksi perubahan
            longitudeInput.dispatchEvent(new Event('input', { bubbles: true }));
            longitudeInput.dispatchEvent(new Event('change', { bubbles: true }));
            longitudeInput.dispatchEvent(new Event('blur', { bubbles: true }));
        }
        
        // Fallback: cari berdasarkan label yang mengandung teks latitude/longitude
        if (!latitudeInput || !longitudeInput) {
            const allInputs = document.querySelectorAll('input[type="text"], input[type="number"]');
            allInputs.forEach(input => {
                const label = input.closest('.fi-fo-field-wrp')?.querySelector('label');
                if (label) {
                    const labelText = label.textContent.toLowerCase();
                    if (labelText.includes('latitude') || labelText.includes('lat')) {
                        input.value = newLat.toFixed(8);
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    } else if (labelText.includes('longitude') || labelText.includes('long') || labelText.includes('lng')) {
                        input.value = newLng.toFixed(8);
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }
            });
        }
    });
    
    // Update marker when input fields change
    function findAndWatchInputs() {
        const latitudeSelectors = [
            'input[name="latitude"]',
            'input[id*="latitude"]',
            'input[wire\\:model*="latitude"]'
        ];
        
        const longitudeSelectors = [
            'input[name="longitude"]',
            'input[id*="longitude"]', 
            'input[wire\\:model*="longitude"]'
        ];
        
        let latitudeInput = null;
        let longitudeInput = null;
        
        for (let selector of latitudeSelectors) {
            latitudeInput = document.querySelector(selector);
            if (latitudeInput) break;
        }
        
        for (let selector of longitudeSelectors) {
            longitudeInput = document.querySelector(selector);
            if (longitudeInput) break;
        }
        
        function updateMarkerFromInputs() {
            const lat = parseFloat(latitudeInput?.value);
            const lng = parseFloat(longitudeInput?.value);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], map.getZoom());
            }
        }
        
        latitudeInput?.addEventListener('blur', updateMarkerFromInputs);
        longitudeInput?.addEventListener('blur', updateMarkerFromInputs);
        latitudeInput?.addEventListener('change', updateMarkerFromInputs);
        longitudeInput?.addEventListener('change', updateMarkerFromInputs);
    }
    
    // Jalankan setelah DOM ready dan berikan delay untuk Livewire
    setTimeout(findAndWatchInputs, 1000);
});
</script>
@endpush