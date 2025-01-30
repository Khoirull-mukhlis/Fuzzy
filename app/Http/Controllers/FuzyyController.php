<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FuzyyController extends Controller
{
    public function showInputForm()
    {
        return view('input');
    }

    public function processInput(Request $request)
    {
        // Ambil nilai dari form
        $persediaan = $request->input('persediaan');
        $permintaan = $request->input('permintaan');

        // Fuzzifikasi
        $stokMembership = $this->fuzzifyStock($persediaan);
        $demandMembership = $this->fuzzifyDemand($permintaan);

        // Inferensi (aturan fuzzy sederhana)
        $outputFuzzy = $this->fuzzyInference($stokMembership, $demandMembership);

        // Defuzzifikasi (hasil output konkret)
        $outputCrisp = $this->defuzzify($outputFuzzy);

        // Grafik inferensi fuzzy (termasuk kategori 'tidak_produksi')
        $rules = [
            'Tidak Produksi' => $outputFuzzy['tidak_produksi'],
            'Produksi Kecil' => $outputFuzzy['kecil'],
            'Produksi Sedang' => $outputFuzzy['sedang'],
            'Produksi Besar' => $outputFuzzy['besar'],
        ];

        // Kirim data ke view
        return view('result', [
            'persediaan' => $persediaan,
            'permintaan' => $permintaan,
            'output' => $outputCrisp,
            'stokMembership' => $stokMembership,
            'demandMembership' => $demandMembership,
            'outputFuzzy' => $outputFuzzy,
            'rules' => $rules,
        ]);
    }

    private function fuzzifyStock($stok)
    {
        return [
            'minim' => ($stok < 30) ? 1 : (($stok > 40) ? 0 : (40 - $stok) / (40 - 30)),
            'sedang' => ($stok >= 35 && $stok <= 45) ? (
                ($stok <= 40) ? (($stok - 35) / (40 - 35)) : ((45 - $stok) / (45 - 40))
            ) : 0,
            'banyak' => ($stok > 40) ? (($stok >= 45) ? 1 : (($stok - 40) / (45 - 40))) : 0,
        ];
    }

    private function fuzzifyDemand($demand)
    {
        return [
            'rendah' => ($demand <= 10) ? 1 : (($demand >= 30) ? 0 : (30 - $demand) / (30 - 10)),
            'sedang' => ($demand >= 10 && $demand <= 40) ? (
                ($demand <= 20) ? (($demand - 10) / (20 - 10)) : ((40 - $demand) / (40 - 20))
            ) : 0,
            'tinggi' => ($demand <= 20) ? 0 : (($demand >= 40) ? 1 : ($demand - 20) / (40 - 20)),

        ];
    }

    private function fuzzyInference($stokMembership, $demandMembership)
    {
        $produksiKecil = max(
            min($stokMembership['minim'], $demandMembership['rendah']),
            min($stokMembership['sedang'], $demandMembership['sedang']),
            min($stokMembership['banyak'], $demandMembership['tinggi'])
        );

        $produksiSedang = max(
            min($stokMembership['minim'], $demandMembership['sedang']),
            min($stokMembership['sedang'], $demandMembership['tinggi'])
        );

        $produksiBesar = min($stokMembership['minim'], $demandMembership['tinggi']);

        $tidakProduksi = max(
            min($stokMembership['sedang'], $demandMembership['rendah']),
            min($stokMembership['banyak'], $demandMembership['rendah']),
            min($stokMembership['banyak'], $demandMembership['sedang'])
        );

        return [
            'tidak_produksi' => $tidakProduksi,
            'kecil' => $produksiKecil,
            'sedang' => $produksiSedang,
            'besar' => $produksiBesar,
        ];
    }

    private function defuzzify($outputFuzzy)
    {
        // Menggunakan pusat gravitasi (centroid) dengan rentang output 0 hingga 40
        $total = ($outputFuzzy['tidak_produksi'] * 0) +
            ($outputFuzzy['kecil'] * 10) +
            ($outputFuzzy['sedang'] * 25) +
            ($outputFuzzy['besar'] * 40);

        // Menjumlahkan semua derajat keanggotaan termasuk untuk tidak produksi
        $weight = $outputFuzzy['tidak_produksi'] +
            $outputFuzzy['kecil'] +
            $outputFuzzy['sedang'] +
            $outputFuzzy['besar'];
            
        // Mengembalikan nilai defuzzifikasi (rata-rata berbobot)
        return $weight > 0 ? number_format($total / $weight, 2) : number_format(0, 2);
    }
}
