<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator Bunga Tunggal dan Bunga Majemuk</title>
    <!-- Include Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 p-6 max-w-3xl bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Kalkulator Bunga Tunggal & Majemuk</h2>
        <form id="interestForm" method="POST" class="space-y-4">
            <div>
                <label for="principal" class="block text-sm font-medium text-gray-700">Modal Awal (Rp):</label>
                <input type="number" id="principal" name="principal" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
            </div>

            <div>
                <label for="rate" class="block text-sm font-medium text-gray-700">Suku Bunga (%):</label>
                <input type="number" id="rate" name="rate" step="0.01" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
            </div>

            <div>
                <label for="time" class="block text-sm font-medium text-gray-700">Waktu (Tahun):</label>
                <input type="number" id="time" name="time" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
            </div>

            <div>
                <label for="compound" class="block text-sm font-medium text-gray-700">Frekuensi Periode Majemuk (opsional):</label>
                <input type="number" id="compound" name="compound" placeholder="Misal: 4 untuk triwulan"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
            </div>

            <!-- Opsi pilihan frekuensi bunga -->
            <div>
                <label for="timeType" class="block text-sm font-medium text-gray-700">Frekuensi Bunga:</label>
                <select id="timeType" name="timeType" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                    <option value="harian">Harian</option>
                    <option value="bulanan">Bulanan</option>
                    <option value="tahunan" selected>Tahunan</option>
                </select>
            </div>

            <div>
                <input type="submit" name="calculate" value="Hitung Bunga"
                    class="w-full bg-green-600 text-white font-bold py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 cursor-pointer">
            </div>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Ambil nilai input dari formulir dengan pengecekan tambahan untuk variabel kosong
            $principal = isset($_POST['principal']) ? $_POST['principal'] : 0;
            $rate = isset($_POST['rate']) ? $_POST['rate'] / 100 : 0;
            $time = isset($_POST['time']) ? $_POST['time'] : 0;
            $compound = isset($_POST['compound']) && !empty($_POST['compound']) ? $_POST['compound'] : 1;
            $timeType = isset($_POST['timeType']) ? $_POST['timeType'] : 'tahunan';

            // Mengatur faktor pembagian waktu sesuai dengan frekuensi yang dipilih
            switch ($timeType) {
                case 'harian':
                    $timeFactor = 365;
                    break;
                case 'bulanan':
                    $timeFactor = 12;
                    break;
                default: // Tahunan
                    $timeFactor = 1;
                    break;
            }

            // Konversi waktu ke dalam unit yang sesuai dengan pilihan (harian, bulanan, tahunan)
            $timeInYears = $time / $timeFactor;

            // Perhitungan Bunga Tunggal
            $simpleInterestResults = [];
            for ($i = 1; $i <= $timeInYears; $i++) {
                $simpleInterest = $principal * $rate * $i;
                $totalSimple = $principal + $simpleInterest;
                $simpleInterestResults[] = [
                    'time' => $i,
                    'principal' => number_format($principal, 2, ',', '.'),
                    'simpleInterest' => number_format($simpleInterest, 2, ',', '.'),
                    'totalSimple' => number_format($totalSimple, 2, ',', '.')
                ];
            }

            // Perhitungan Bunga Majemuk
            $compoundInterestResults = [];
            for ($i = 1; $i <= $timeInYears; $i++) {
                $compoundInterest = $principal * pow((1 + $rate / $compound), $compound * $i) - $principal;
                $totalCompound = $principal * pow((1 + $rate / $compound), $compound * $i);
                $compoundInterestResults[] = [
                    'time' => $i,
                    'principal' => number_format($principal, 2, ',', '.'),
                    'compoundInterest' => number_format($compoundInterest, 2, ',', '.'),
                    'totalCompound' => number_format($totalCompound, 2, ',', '.')
                ];
            }

            // Output hasil perhitungan dalam tabel
            echo "
            <div class='mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200'>
                <h3 class='text-xl font-semibold text-gray-700 mb-4'>Hasil Perhitungan:</h3>
                <p class='text-gray-700'><strong>Modal Awal:</strong> Rp " . number_format($principal, 2, ',', '.') . "</p>

                <h4 class='text-lg font-semibold text-gray-700 mt-6 mb-4'>Bunga Tunggal:</h4>
                <table class='w-full text-left border border-gray-300'>
                    <thead>
                        <tr class='bg-gray-200'>
                            <th class='px-4 py-2 border'>Waktu (Tahun)</th>
                            <th class='px-4 py-2 border'>Modal Awal (Rp)</th>
                            <th class='px-4 py-2 border'>Bunga Tunggal (Rp)</th>
                            <th class='px-4 py-2 border'>Total dengan Bunga Tunggal (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($simpleInterestResults as $result) {
                echo "
                    <tr>
                        <td class='px-4 py-2 border'>{$result['time']}</td>
                        <td class='px-4 py-2 border'>{$result['principal']}</td>
                        <td class='px-4 py-2 border'>{$result['simpleInterest']}</td>
                        <td class='px-4 py-2 border'>{$result['totalSimple']}</td>
                    </tr>";
            }

            echo "
                    </tbody>
                </table>

                <h4 class='text-lg font-semibold text-gray-700 mt-6 mb-4'>Bunga Majemuk:</h4>
                <table class='w-full text-left border border-gray-300'>
                    <thead>
                        <tr class='bg-gray-200'>
                            <th class='px-4 py-2 border'>Waktu (Tahun)</th>
                            <th class='px-4 py-2 border'>Modal Awal (Rp)</th>
                            <th class='px-4 py-2 border'>Bunga Majemuk (Rp)</th>
                            <th class='px-4 py-2 border'>Total dengan Bunga Majemuk (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($compoundInterestResults as $result) {
                echo "
                    <tr>
                        <td class='px-4 py-2 border'>{$result['time']}</td>
                        <td class='px-4 py-2 border'>{$result['principal']}</td>
                        <td class='px-4 py-2 border'>{$result['compoundInterest']}</td>
                        <td class='px-4 py-2 border'>{$result['totalCompound']}</td>
                    </tr>";
            }

            echo "
                    </tbody>
                </table>
            </div>";
        }
        ?>
    </div>

    <script>
        // JavaScript tambahan untuk validasi input dan efek tambahan
        document.getElementById('interestForm').addEventListener('submit', function(event) {
            const principal = document.getElementById('principal').value;
            const rate = document.getElementById('rate').value;
            const time = document.getElementById('time').value;

            // Validasi nilai input harus lebih dari nol
            if (principal <= 0 || rate <= 0 || time <= 0) {
                alert("Semua nilai harus lebih dari nol!");
                event.preventDefault(); // Mencegah form submit jika input tidak valid
            }
        });
    </script>
</body>
</html>
