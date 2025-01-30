<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Produksi Fuzzy</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <h1>Hasil Perhitungan Fuzzy</h1>
    <p><strong>Persediaan:</strong> {{ $persediaan }}</p>
    <p><strong>Permintaan:</strong> {{ $permintaan }}</p>
    <p><strong>Jumlah Produksi yang Disarankan:</strong> {{ $output }}</p>
    <h2>Keanggotaan Stok</h2>
    <ul>
        <li>Minim: {{ $stokMembership['minim'] }}</li>
        <li>Sedang: {{ $stokMembership['sedang'] }}</li>
        <li>Banyak: {{ $stokMembership['banyak'] }}</li>
    </ul>

    <h2>Keanggotaan Permintaan</h2>
    <ul>
        <li>Rendah: {{ $demandMembership['rendah'] }}</li>
        <li>Sedang: {{ $demandMembership['sedang'] }}</li>
        <li>Tinggi: {{ $demandMembership['tinggi'] }}</li>
    </ul>
    <h2>Detail Inferensi Fuzzy</h2>
    <ul>
        <li>Tidak Produksi: {{ $outputFuzzy['tidak_produksi'] }}</li>
        <li>Produksi Kecil: {{ $outputFuzzy['kecil'] }}</li>
        <li>Produksi Sedang: {{ $outputFuzzy['sedang'] }}</li>
        <li>Produksi Besar: {{ $outputFuzzy['besar'] }}</li>
    </ul>
    <!-- Grafik Keanggotaan -->
    <h2>1. Grafik Keanggotaan</h2>
    <canvas id="membershipChart" width="800" height="400"></canvas>

    <!-- Grafik Inferensi -->
    <h2>2. Grafik Inferensi Fuzzy</h2>
    <canvas id="inferenceChart" width="800" height="400"></canvas>

    <div>
        <button onclick="window.location.href='{{ url('input') }}'"
            style="margin-top: 20px; padding: 10px 20px; font-size: 16px;">
            Back to Input
        </button>
    </div>

    <script>
        // data JSON diteruskan ke JavaScript
        const stokMembership = JSON.parse('<?= json_encode($stokMembership) ?>');
        const demandMembership = JSON.parse('<?= json_encode($demandMembership) ?>');
        const rules = JSON.parse('<?= json_encode($rules) ?>');
        // Grafik Keanggotaan
        const ctxMembership = document.getElementById('membershipChart').getContext('2d');
        new Chart(ctxMembership, {
            type: 'line',
            data: {
                labels: ['minim', 'Sedang', 'banyak'],
                datasets: [{
                        label: 'Persediaan',
                        data: [
                            stokMembership.minim,
                            stokMembership.sedang,
                            stokMembership.banyak,
                        ],
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        fill: false, // Tidak mengisi area bawah garis
                        tension: 0.4, // Mengatur kelengkungan garis
                    },
                    {
                        label: 'Permintaan',
                        data: [
                            demandMembership.rendah,
                            demandMembership.sedang,
                            demandMembership.tinggi,
                        ],
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        fill: false, // Tidak mengisi area bawah garis
                        tension: 0.4, // Mengatur kelengkungan garis
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Derajat Keanggotaan',
                        },
                        min: 0,
                        max: 1,
                    },
                },
            },
        });
        // Grafik Inferensi Fuzzy
        const ctxInference = document.getElementById('inferenceChart').getContext('2d');
        new Chart(ctxInference, {
            type: 'line',
            data: {
                labels: Object.keys(rules),
                datasets: [{
                    label: 'Output Fuzzy',
                    data: Object.values(rules),
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: false, // Tidak mengisi area bawah garis
                    tension: 0.4, // Mengatur kelengkungan garis
                }, ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Nilai Inferensi',
                        },
                        min: 0,
                        max: 1,
                    },
                },
            },
        });
    </script>
</body>

</html>
