<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class AgendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Path ke file CSV
          $filePath = database_path('seeders/csv/Minutes of Meeting (1).csv');

          // Baca file CSV
          if (!File::exists($filePath)) {
              $this->command->error("File CSV tidak ditemukan di path: {$filePath}");
              return;
          }

          $file = fopen($filePath, 'r');
          $headers = fgetcsv($file); // Ambil header kolom

          // Loop melalui setiap baris data
          while (($row = fgetcsv($file)) !== false) {
              // Mapping data dari CSV ke array
              $data = array_combine($headers, $row);

              // Konversi tanggal ke format datetime
              $dateString = $data['Date'] ?? null;
              try {
                  // Normalisasi format tanggal
                  $normalizedDate = trim(str_replace(',', '', $dateString)); // Hapus koma dan spasi ekstra

                  // Coba beberapa format tanggal yang mungkin
                  $dateFormats = [
                      'd/m/Y h:i:s A', // Contoh: 1/9/2025 4:30:00 PM
                      'd/m/Y H:i:s',   // Contoh: 20/01/2025 0:00:00
                      'm/d/Y h:i:s A', // Contoh: 1/13/2025 12:00:00 AM
                      'm/d/Y H:i:s',   // Contoh: 5/20/2025 12:00:00
                      'Y-m-d H:i:s',   // Format standar MySQL
                  ];

                  $date = null;
                  foreach ($dateFormats as $format) {
                      try {
                          $date = Carbon::createFromFormat($format, $normalizedDate)->format('Y-m-d H:i:s');
                          break; // Berhenti jika berhasil parse
                      } catch (\Exception $e) {
                          continue; // Coba format berikutnya
                      }
                  }

                  if (!$date) {
                      throw new \Exception("Tanggal tidak valid: {$normalizedDate}");
                  }
              } catch (\Exception $e) {
                  // Log error jika tanggal tidak valid
                  $this->command->warn($e->getMessage() . ". Skipping...");
                  continue; // Lewati baris ini
              }

              // Normalisasi meet_type
              $meetType = strtolower($data['Meet Type'] ?? '');
              if (strpos($meetType, ',') !== false) {
                  // Jika ada dua jenis meeting, pilih salah satu (misalnya yang pertama)
                  $meetType = explode(',', $meetType)[0];
              }
              $meetType = in_array($meetType, ['offline', 'online']) ? $meetType : null;

              // Simpan data ke tabel agendas
              DB::table('agendas')->insert([
                  'description' => $data['Description'] ?? null,
                  'date' => $date,
                  'project_id' => null, // Asumsi project_id diisi manual atau melalui relasi
                  'location' => $data['Location'] ?? null,
                  'agenda_type' => $data['Agenda Type'] === 'Meeting' ? 'meeting' : 'schedule',
                  'category_documents_id' => null, // Asumsi category_documents_id diisi manual atau melalui relasi
                  'duration' => $data['Duration'] ?? null,
                  'meet_type' => $meetType, // Normalized meet type
                  'notes' => $data['Notes'] ?? null,
                  'project_link' => $data['Project Link'] ?? null,
                  'file_support' => null, // Kolom ini tidak ada di CSV
                  'images' => null, // Kolom ini tidak ada di CSV
                  'access' => json_encode(explode(',', $data['Access'] ?? '')), // Konversi string ke JSON
                  'created_at' => now(),
                  'updated_at' => now(),
              ]);
          }

          fclose($file);
          $this->command->info('Data berhasil di-seed ke tabel agendas.');


    }
}
