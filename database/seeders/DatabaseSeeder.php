<?php

namespace Database\Seeders;

use App\Models\Claim;
use App\Models\ClaimEvidenceLink;
use App\Models\Dataset;
use App\Models\Evidence;
use App\Models\Finding;
use App\Models\Literature;
use App\Models\PaperSection;
use App\Models\ProjectDocument;
use App\Models\ResearchIdea;
use App\Models\ResearchOutput;
use App\Models\ResearchProject;
use App\Models\ResearchQuestion;
use App\Models\ResearchTask;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Primary Default Researcher User
        $user = User::updateOrCreate(
            ['email' => 'trifebriansah321@gmail.com'],
            [
                'name' => 'Tri Febriansah',
                'password' => Hash::make('12344321'),
                'email_verified_at' => now(),
            ]
        );

        // Also ensure researcher@research-os.org exists for test compatibility
        User::updateOrCreate(
            ['email' => 'researcher@research-os.org'],
            [
                'name' => 'Dr. Aris Kusuma, M.Eng.',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Also ensure test@example.com exists for Breeze defaults
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Prof. Research Fellow',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Ideas in Idea Repository
        $idea1 = ResearchIdea::create([
            'user_id' => $user->id,
            'title' => 'Smart Soil System: Low-Power Edge-IoT Sensor Mesh for Precision Soil Monitoring',
            'description' => 'Sistem monitoring kelembapan tanah, suhu, dan konduktivitas NPK berbasis mikrokontroler ultra low-power dengan transmisi LoRaWAN mesh untuk pertanian berundak tradisional Subak.',
            'field' => 'IoT & Smart Agriculture',
            'research_type' => 'experimental',
            'priority' => 'high',
            'potential_outputs' => ['Journal Scopus Q1', 'Conference Paper', 'Pengabdian Masyarakat', 'Dataset Open Access'],
            'status' => 'promoted',
            'ai_analysis' => [
                'field_classification' => 'Smart Agriculture / Embedded Edge Computing',
                'research_gap' => 'Kebanyakan sensor komersial membutuhkan daya tinggi (>250mW) atau sinyal seluler yang sulit menjangkau lembah berundak dengan kelembapan kanopi tinggi.',
                'novelty' => 'Kombinasi kalibrasi kapasitif multi-kedalaman dengan routing LoRa adaptif sleep-wake cycle.',
                'recommended_methods' => ['Design Science Research', 'Lab Calibration vs Oven Drying', 'In-field 30-day Deployment'],
            ],
        ]);

        $idea2 = ResearchIdea::create([
            'user_id' => $user->id,
            'title' => 'Edge-AI Autonomous Coral Bleaching Early Detection Drone using Lightweight YOLOv10',
            'description' => 'Deteksi dini pemutihan terumbu karang menggunakan kamera bawah air mikrokontroler dengan inferensi on-device untuk konservasi bahari pesisir Nusa Penida.',
            'field' => 'Computer Vision & Marine Science',
            'research_type' => 'system_dev',
            'priority' => 'high',
            'potential_outputs' => ['Journal Scopus Q2', 'Technical Report', 'Dataset Citra Karang'],
            'status' => 'captured',
            'ai_analysis' => [
                'field_classification' => 'Deep Learning & Marine Conservation',
                'research_gap' => 'Citra bawah air rentan bias chromatic aberration dan distorsi kedalaman cahaya biru-hijau.',
                'novelty' => 'Penerapan modul underwater dehazing adaptif sebelum inferensi deteksi bleaching klasifikasi 4 kelas.',
                'recommended_methods' => ['Underwater Color Correction (UDCP)', 'YOLOv10-Nano Quantized INT8', 'ROV Field Testing'],
            ],
        ]);

        $idea3 = ResearchIdea::create([
            'user_id' => $user->id,
            'title' => 'Microplastic Acoustic Separation in Estuarine Flow using Ultrasonic Standing Waves',
            'description' => 'Pemisahan mikroplastik dari sampel air muara sungai tanpa filter mekanik membran menggunakan akustofluidik gelombang ultrasonik tegak.',
            'field' => 'Environmental Engineering & Fluid Mechanics',
            'research_type' => 'experimental',
            'priority' => 'medium',
            'potential_outputs' => ['Journal International', 'Prototype Patent'],
            'status' => 'captured',
            'ai_analysis' => [
                'field_classification' => 'Environmental Nanotechnology & Microfluidics',
                'research_gap' => 'Filter membran konvensional cepat tersumbat bio-fouling sedimen muara.',
                'novelty' => 'Fokus akustik piezoelektrik 2 MHz bebas kontak fisik.',
                'recommended_methods' => ['Acoustophoretic Chamber Design', 'Micro-PIV Particle Tracking', 'Spectroscopic Quantification'],
            ],
        ]);

        // 3. Research Project (Developed from Idea 1)
        $project = ResearchProject::create([
            'user_id' => $user->id,
            'research_idea_id' => $idea1->id,
            'title' => 'Smart Soil System: Low-Power Edge-IoT Sensor Mesh for Precision Soil Monitoring in Tropical Subak Agriculture',
            'slug' => Str::slug('Smart Soil System Low Power Edge IoT Subak Agriculture'),
            'field' => 'IoT & Smart Agriculture',
            'status' => 'paper_writing',
            'summary' => 'Penelitian ini mengembangkan arsitektur sensor tanah hemat energi (edge-IoT) berbasis transmisi LoRa mesh berdaya rendah untuk pemantauan kelembapan tanah, suhu perakaran, dan estimasi salinitas ion pada lanskap persawahan berundak Subak di Bali. Hasil pengujian menunjukkan akurasi pengukuran tinggi (R² = 0.984 vs gravimetri oven) dan penghematan konsumsi daya sebesar 87.3%.',
            'target_deadline' => now()->addMonths(2),
            'target_outputs' => ['Scopus Q1 Manuscript', 'IEEE Conference Paper', 'Laporan Pengabdian Masyarakat', 'Zenodo Open Dataset'],

            // Research Foundation
            'problem_statement' => 'Pertanian presisi pada sawah berundak tradisional tropis menghadapi kendala topografi ekstrem dan kanopi lebat yang menghalangi jangkauan seluler standar. Petani kesulitan mengatur debit air berkala karena ketiadaan data kelembapan tanah real-time, menyebabkan pemborosan air hingga 40% dan keterlambatan deteksi kekeringan perakaran.',
            'research_gap' => 'Sistem monitoring tanah IoT yang ada saat ini sebagian besar didesain untuk lahan datar perkebunan monokultur Eropa/Amerika dengan modul GSM/4G boros daya (>200 mW) dan kalibrasi sensor kapasitif standar yang tidak akurat pada tanah andosol vulkanik tropis berkadar humus tinggi.',
            'novelty' => '1. Algoritma Adaptive Synchronous Duty-Cycling (ASDC) berbasis event kelembapan tanah yang menekan konsumsi idle node ke level 14.2 µA.\n2. Kalibrasi transfer function non-linear khusus karakteristik tanah vulkanik tropis (Andosol & Vertisol).\n3. Integrasi topologi mesh LoRa multihop toleran halangan terasering sawah tanpa repeater bertenaga listrik PLN.',
            'contribution' => '1. Bukti empiris akurasi sensor kapasitif pada tanah andosol tropis dengan deviasi RMSE < 1.8%.\n2. Model matematika konsumsi energi LoRa mesh bertingkat pada topografi terasering elevasi 25-40 derajat.\n3. Skema penghematan air irigasi 34.6% yang dapat diaplikasikan langsung pada organisasi Subak masyarakat lokal.',

            // Methodology
            'methodology_design' => 'Experimental Field Deployment & Design Science Research (DSR)',
            'sample_population' => 'Plot persawahan seluas 2.5 hektar di Subak Jatiluwih, Tabanan, Bali (ketinggian 700 mdpl, kontur bertingkat 8 teras). 12 titik simpul sensor edge dan 1 gateway koordinator.',
            'variables' => 'Variabel Bebas: Kedalaman sensor (10cm, 20cm, 40cm), interval duty-cycle LoRa, topologi hop LoRa (1-hop s/d 3-hop).\nVariabel Terikat: Konsumsi daya (mA/mW), Packet Delivery Ratio (PDR %), RMSE kelembapan vs gravimetri oven lab.',
            'instruments' => 'Mikrokontroler ESP32-S3 + Semtech SX1262 LoRa, Probe Kapasitif Teros-12 & DIY Stainless Steel, Tektronix MDO3024 Oscilloscope untuk power profiling, Lab Oven Memmert UN55 untuk uji gravimetri baku mutu ISO 11465.',
            'procedure' => '1. Kalibrasi laboratorium 120 sampel tanah pada variasi kadar air 10% - 60%.\n2. Pengukuran profil arus sleep, transmit, dan receive di lingkungan lab.\n3. Instalasi 12 unit sensor node di 8 teras sawah aktif Subak.\n4. Pengambilan data telemetri kontinu selama 30 hari periode tanam vegetatif.',
            'evaluation_metrics' => 'Root Mean Square Error (RMSE), Mean Absolute Percentage Error (MAPE), Coefficient of Determination (R²), Packet Delivery Ratio (PDR %), Node Battery Lifetime (Hari/Tahun).',
            'readiness_score' => 88,
        ]);

        $idea1->update(['research_project_id' => $project->id]);

        // 4. Research Questions
        ResearchQuestion::create([
            'research_project_id' => $project->id,
            'question' => 'Bagaimana akurasi kalibrasi sensor kelembapan kapasitif edge-node dibandingkan dengan metode standar baku oven gravimetri pada tanah andosol vulkanik tropis?',
            'objective' => 'Mengevaluasi akurasi dan tingkat kesalahan (RMSE) pembacaan sensor kapasitif edge-node pada tanah andosol.',
            'status' => 'answered',
            'order' => 1,
        ]);

        ResearchQuestion::create([
            'research_project_id' => $project->id,
            'question' => 'Seberapa besar efisiensi konsumsi energi yang dapat dicapai dengan algoritma Adaptive Synchronous Duty-Cycling (ASDC) pada jaringan LoRa mesh sawah bertingkat?',
            'objective' => 'Mengukur dan membandingkan profil konsumsi daya node sensor saat idle, wake, dan transmit multi-hop.',
            'status' => 'answered',
            'order' => 2,
        ]);

        ResearchQuestion::create([
            'research_project_id' => $project->id,
            'question' => 'Apakah pemantauan kelembapan tanah berbasis threshold otomatis dapat menghemat volume air irigasi Subak tanpa menurunkan biomassa tanaman padi?',
            'objective' => 'Mengukur dampak agronomi penghematan debit air irigasi berkala terhadap tinggi tanaman dan jumlah anakan produktif.',
            'status' => 'investigating',
            'order' => 3,
        ]);

        // 5. Literatures (Literature Repository & Matrix)
        Literature::create([
            'user_id' => $user->id,
            'research_project_id' => $project->id,
            'title' => 'Energy-Efficient LoRa-Based Sensor Networks for Precision Irrigation in Agriculture',
            'authors' => 'Zhao, M., Sanchez, F., & Al-Hassani, K.',
            'year' => 2024,
            'venue' => 'IEEE Internet of Things Journal, 11(4), 5821-5834',
            'doi' => '10.1109/JIOT.2024.3361280',
            'url' => 'https://doi.org/10.1109/JIOT.2024.3361280',
            'abstract' => 'This paper presents an energy-efficient wireless sensor network leveraging LoRa for large-scale wheat field soil monitoring. Evaluated on flat continental terrain with standard sandy-loam soil.',
            'key_findings' => 'Duty cycling achieved 3 months battery life on 2500mAh battery. PDR was 94.2% within 1.5km range.',
            'methodology_used' => 'Star topology LoRaWAN gateway, single depth capacitive sensor, static 15-minute sleep cycle.',
            'limitations' => 'Did not test multi-hop mesh, flat terrain only, static sleep timer causes missed flood transition events.',
            'our_differentiation' => 'Kami menggunakan topologi multi-hop mesh untuk mengatasi terasering pegunungan dan duty cycle adaptif berbasis dinamika laju perkolasi air.',
            'citation_key' => 'zhao2024energy',
        ]);

        Literature::create([
            'user_id' => $user->id,
            'research_project_id' => $project->id,
            'title' => 'Soil Moisture Sensing in High-Organic Volcanic Soils: Dielectric Anomalies and Calibration Techniques',
            'authors' => 'Pradnya, I. W., & Wijaya, T.',
            'year' => 2023,
            'venue' => 'Geoderma Regional, Vol. 32, e00612',
            'doi' => '10.1016/j.geodrs.2023.e00612',
            'abstract' => 'Tropical volcanic soils exhibit high dielectric dispersion due to allophane and amorphous clay minerals, causing commercial FDR sensors to underestimate volumetric water content by up to 14%.',
            'key_findings' => 'Standard Topp equation failed with 12.8% error. Quadratic empirical fit reduced error to 3.4%.',
            'methodology_used' => 'Laboratory TDR vs gravimetric comparison across 8 Indonesian volcanic soil series.',
            'limitations' => 'Lab experiments only; no continuous field validation or low-cost IoT edge calibration formula.',
            'our_differentiation' => 'Kami mengimplementasikan formula kalibrasi polinomial teroptimasi langsung pada firmware mikrokontroler edge secara real-time.',
            'citation_key' => 'pradnya2023soil',
        ]);

        Literature::create([
            'user_id' => $user->id,
            'research_project_id' => $project->id,
            'title' => 'Subak Traditional Water Allocation vs. Modern Precision Scheduling: A Comparative Hydrological Study',
            'authors' => 'Sutrisna, G., & Wardana, A.',
            'year' => 2025,
            'venue' => 'Agricultural Water Management, 291, 108620',
            'doi' => '10.1016/j.agwat.2025.108620',
            'abstract' => 'Investigated water distribution equity in Subak systems under fluctuating seasonal monsoon rains.',
            'key_findings' => 'Upstream sub-districts consumed 38% more water than downstream plots due to lack of sub-surface soil moisture information.',
            'methodology_used' => 'Weir flow rate logging, manual hydrological surveys.',
            'limitations' => 'Tidak menyediakan platform sensor nirkabel otomatis real-time bagi petani.',
            'our_differentiation' => 'Memberikan visualisasi telemetry sensor real-time yang selaras dengan siklus pinjam-air Subak.',
            'citation_key' => 'sutrisna2025subak',
        ]);

        // 6. Datasets
        $dataset1 = Dataset::create([
            'research_project_id' => $project->id,
            'name' => 'Subak Jatiluwih 30-Day IoT Telemetry Stream (12 Nodes)',
            'source_type' => 'sensor',
            'description' => 'Data telemetri kelembapan tanah (10cm, 20cm, 40cm), suhu tanah, tegangan baterai Li-ion, RSSI/SNR LoRa dari 12 node terdistribusi di 8 teras sawah Subak Jatiluwih.',
            'collection_date' => now()->subDays(10),
            'location' => 'Subak Jatiluwih, Tabanan, Bali (-8.3685, 115.1311)',
            'record_count' => 14400,
            'status' => 'verified',
            'file_path' => 'datasets/subak_jatiluwih_telemetry_30d.csv',
            'metadata' => [
                'sampling_interval' => '10 minutes',
                'active_nodes' => 12,
                'gateway' => 'SX1302 8-channel Outdoor Gateway',
                'data_columns' => ['timestamp', 'node_id', 'v_moisture_10', 'v_moisture_20', 'temp_c', 'battery_mv', 'rssi_dbm', 'snr_db'],
            ],
        ]);

        $dataset2 = Dataset::create([
            'research_project_id' => $project->id,
            'name' => 'Laboratory Gravimetric Oven Soil Core Calibration (ISO 11465)',
            'source_type' => 'experiment',
            'description' => 'Hasil uji destruktif gravimetri oven 105°C selama 24 jam terhadap 120 sampel core tanah andosol sawah Subak untuk kalibrasi ground-truth kurva sensor kapasitif.',
            'collection_date' => now()->subDays(25),
            'location' => 'Laboratorium Ilmu Tanah Universitas Udayana',
            'record_count' => 120,
            'status' => 'verified',
            'file_path' => 'datasets/lab_gravimetric_groundtruth_120.csv',
            'metadata' => [
                'method' => 'ISO 11465 / ASTM D2216',
                'oven_temp' => '105 ± 5 °C',
                'drying_duration' => '24 hours',
            ],
        ]);

        // 7. Evidences
        $evidence1 = Evidence::create([
            'research_project_id' => $project->id,
            'dataset_id' => $dataset2->id,
            'title' => 'Sensor Calibration Curve: Capacitive Output vs Gravimetric Oven Moisture',
            'evidence_type' => 'data_point',
            'description' => 'Regresi kalibrasi polinomial derajat dua menunjukkan koefisien determinasi R² = 0.984 dengan Root Mean Square Error (RMSE) 1.82% VWC pada rentang 15% - 55% kadar air tanah andosol.',
            'data_payload' => [
                'r_squared' => 0.9842,
                'rmse_pct' => 1.82,
                'mape_pct' => 2.15,
                'sample_size' => 120,
                'p_value' => '<0.0001',
            ],
            'collected_at' => now()->subDays(20),
            'quality_status' => 'ground_truth',
        ]);

        $evidence2 = Evidence::create([
            'research_project_id' => $project->id,
            'dataset_id' => $dataset1->id,
            'title' => 'LoRa Multi-Hop Mesh Network Uptime & Packet Delivery Ratio (PDR)',
            'evidence_type' => 'test_log',
            'description' => 'Uji transmisi 30 hari dengan total 14.400 paket menghasilkan Packet Delivery Ratio (PDR) rata-rata 99.2% melintasi kontur terasering 8 tingkat tanpa kehilangan paket berkepanjangan.',
            'data_payload' => [
                'total_packets_sent' => 14400,
                'packets_received' => 14285,
                'pdr_percentage' => 99.20,
                'average_rssi' => -104.5,
                'average_snr' => 7.8,
            ],
            'collected_at' => now()->subDays(10),
            'quality_status' => 'verified',
        ]);

        $evidence3 = Evidence::create([
            'research_project_id' => $project->id,
            'dataset_id' => $dataset1->id,
            'title' => 'Tektronix Oscilloscope Current Draw Profiling: Sleep vs Active Wake',
            'evidence_type' => 'experiment_metric',
            'description' => 'Pengukuran arus menggunakan shunt resistor 10Ω pada oscilloscope Tektronix menunjukkan arus sleep sebesar 14.2 µA dan arus transmit puncak sebesar 48.6 mA (durasi 82 ms), menekan rata-rata konsumsi harian menjadi 2.18 mAh.',
            'data_payload' => [
                'deep_sleep_current_ua' => 14.2,
                'sensor_read_current_ma' => 8.4,
                'lora_tx_current_ma' => 48.6,
                'tx_duration_ms' => 82,
                'daily_consumption_mah' => 2.18,
                'projected_battery_days' => 142,
            ],
            'collected_at' => now()->subDays(15),
            'quality_status' => 'verified',
        ]);

        $evidence4 = Evidence::create([
            'research_project_id' => $project->id,
            'dataset_id' => $dataset1->id,
            'title' => 'Subak Jatiluwih Terraced Field Deployment Photo at Station #4',
            'evidence_type' => 'photo',
            'description' => 'Foto instalasi simpul sensor di pematang sawah terasering tingkat ke-4 dengan panel surya mini 1W dan probe multi-kedalaman.',
            'media_path' => 'evidences/subak_station4_deploy.jpg',
            'collected_at' => now()->subDays(28),
            'quality_status' => 'verified',
        ]);

        $evidence5 = Evidence::create([
            'research_project_id' => $project->id,
            'dataset_id' => null,
            'title' => 'Wawancara Pekaseh (Ketua Subak) Jatiluwih tentang Kemudahan Sistem',
            'evidence_type' => 'quote',
            'description' => '"Sebelum ada sensor ini, kami harus berjalan kaki mengecek pematang atas setiap subuh untuk tahu apakah air dari saluran temuku mengalir cukup. Sekarang data kelembapan bisa dilihat di balai Subak, pembagian air jadi lebih adil."',
            'collected_at' => now()->subDays(8),
            'quality_status' => 'verified',
        ]);

        // 8. Findings
        $finding1 = Finding::create([
            'research_project_id' => $project->id,
            'title' => 'Koreksi Dielektrik Polinomial Mengurangi Kesalahan Sensor Menjadi 1.82%',
            'statement' => 'Sensor kapasitif edge-node yang dikalibrasi dengan persamaan polinomial kuadratik berhasil menekan Root Mean Square Error (RMSE) menjadi 1.82% VWC (R² = 0.984), jauh melampaui kurva Topp bawaan pabrik yang memiliki galat 11.4%.',
            'finding_type' => 'quantitative',
            'metrics_summary' => [
                'calibrated_rmse' => 1.82,
                'default_factory_rmse' => 11.40,
                'improvement_pct' => 84.0,
                'r_squared' => 0.984,
            ],
            'confidence_score' => 95,
            'interpretation' => 'Kandungan bahan organik tinggi pada andosol memerlukan koreksi koefisien permitivitas semu secara lokal pada firmware.',
        ]);

        $finding2 = Finding::create([
            'research_project_id' => $project->id,
            'title' => 'Algoritma ASDC Menekan Konsumsi Energi Rata-rata Sebesar 87.3%',
            'statement' => 'Dibandingkan pengiriman periodik kontinu (1 menit), algoritma Adaptive Synchronous Duty-Cycling (ASDC) berbasis delta kelembapan tanah memperpanjang estimasi usia baterai Li-ion 18650 dari 18 hari menjadi 142 hari (penghematan 87.3%).',
            'finding_type' => 'comparative',
            'metrics_summary' => [
                'baseline_days' => 18,
                'asdc_days' => 142,
                'energy_savings_pct' => 87.3,
                'packet_loss_rate_pct' => 0.8,
            ],
            'confidence_score' => 92,
            'interpretation' => 'Pergerakan air di zona perakaran sawah memiliki inersia lambat sehingga interval transmisi dapat melonggar saat kondisi tanah jenuh/stabil tanpa kehilangan resolusi data penting.',
        ]);

        // 9. Claims (Scientific Claims mapped to Evidence & Anti-Hallucination Guardrails)
        $claim1 = Claim::create([
            'research_project_id' => $project->id,
            'finding_id' => $finding1->id,
            'claim_text' => 'The calibrated capacitive sensor edge-node achieves high volumetric water content fidelity with an RMSE of 1.82% across high-organic tropical volcanic andosol soils.',
            'section_target' => 'results',
            'anti_hallucination_status' => 'grounded',
            'is_verified' => true,
        ]);
        ClaimEvidenceLink::create([
            'claim_id' => $claim1->id,
            'evidence_id' => $evidence1->id,
            'relevance_note' => 'Langsung didukung oleh data kalibrasi lab ISO 11465 (120 sampel core tanah).',
        ]);

        $claim2 = Claim::create([
            'research_project_id' => $project->id,
            'finding_id' => $finding2->id,
            'claim_text' => 'Dynamic duty-cycle scheduling reduces average node daily energy consumption to 2.18 mAh, extending single-cell 18650 battery autonomy to 142 days in terraced subak conditions.',
            'section_target' => 'results',
            'anti_hallucination_status' => 'grounded',
            'is_verified' => true,
        ]);
        ClaimEvidenceLink::create([
            'claim_id' => $claim2->id,
            'evidence_id' => $evidence3->id,
            'relevance_note' => 'Terverifikasi melalui profiling oscilloscope Tektronix dan data log 30 hari.',
        ]);
        ClaimEvidenceLink::create([
            'claim_id' => $claim2->id,
            'evidence_id' => $evidence2->id,
            'relevance_note' => 'Menunjukkan PDR 99.2% tetap terjaga selama transmisi daya rendah.',
        ]);

        // Demonstration of Anti-Hallucination Flag: A claim with NO evidence!
        $claim3 = Claim::create([
            'research_project_id' => $project->id,
            'finding_id' => null,
            'claim_text' => 'Implementation of the sensor network completely eliminated 100% of bacterial leaf blight infections across the paddy fields without any agrochemical treatment.',
            'section_target' => 'discussion',
            'anti_hallucination_status' => 'missing_evidence',
            'is_verified' => false,
        ]);

        // 10. Paper Sections
        PaperSection::create([
            'research_project_id' => $project->id,
            'section_type' => 'abstract',
            'title' => 'Abstract & Keywords',
            'order' => 1,
            'content' => "Precision agriculture in tropical mountainous terrains is challenged by irregular terraced topography and high humidity, which impair conventional wireless telemetry and sensor fidelity. In this study, we present the Smart Soil System, an ultra-low-power edge-IoT sensor mesh tailored for traditional Subak terraced rice paddies in Bali, Indonesia. The system integrates multi-depth capacitive dielectric sensing with an on-device polynomial calibration algorithm and an Adaptive Synchronous Duty-Cycling (ASDC) protocol over LoRa mesh topology. Rigorous laboratory calibration against ISO 11465 oven-drying standards demonstrates that the custom polynomial calibration reduces Root Mean Square Error (RMSE) to 1.82% volumetric water content (R² = 0.984), outperforming default empirical equations (RMSE = 11.40%). Field deployment across 2.5 hectares of terraced terrain over 30 days verified a 99.2% Packet Delivery Ratio and an 87.3% reduction in node energy consumption, extending continuous operation on a single 18650 cell to 142 days. These findings provide an evidence-backed blueprint for low-cost, sustainable agro-telemetry in complex tropical landscapes.\n\nKeywords: Edge-IoT, Precision Agriculture, Soil Moisture, LoRa Mesh, Subak Irrigation, Low-Power Sensing.",
            'word_count' => 172,
            'is_drafted' => true,
        ]);

        PaperSection::create([
            'research_project_id' => $project->id,
            'section_type' => 'introduction',
            'title' => '1. Introduction',
            'order' => 2,
            'content' => "Water scarcity and uncoordinated distribution pose significant risks to food security in Southeast Asia's wet rice cultivation ecosystems. In Bali, the century-old Subak cooperative irrigation system relies on gravitational canal networks regulated by communal agreements. However, downstream plots frequently encounter intermittent drought during dry spell transitions due to the absence of real-time sub-surface moisture telemetry.\n\nWhile Internet of Things (IoT) solutions have flourished in flat commercial farm layouts (Zhao et al., 2024), their direct adoption in terraced tropical volcanic basins remains severely hindered. First, volcanic andosol soils possess unique amorphous mineral compositions that induce dielectric dispersion, rendering standard factory sensor readings inaccurate by up to 14% (Pradnya & Wijaya, 2023). Second, dense vegetation canopies and steep 30° terrace slopes create severe non-line-of-sight (NLOS) signal attenuation that drains conventional battery nodes in fewer than three weeks.\n\nTo overcome these barriers, this paper contributes:\n1. A calibrated low-power capacitive sensor node achieving high fidelity (RMSE = 1.82%) in tropical andosol soil.\n2. An Adaptive Synchronous Duty-Cycling (ASDC) mechanism that slashes active transmitter draw while maintaining >99% telemetry reliability across 8 elevation terraces.\n3. Empirical field validation demonstrating significant energy autonomy and socio-technical acceptance within local Subak governance.",
            'word_count' => 195,
            'is_drafted' => true,
        ]);

        PaperSection::create([
            'research_project_id' => $project->id,
            'section_type' => 'literature_review',
            'title' => '2. Related Work & Literature Matrix',
            'order' => 3,
            'content' => "Recent efforts in wireless agro-monitoring have explored LoRaWAN star architectures (Zhao et al., 2024). While effective in flat wheat fields, star topologies suffer significant packet drop in steep terraced valleys where intermediate ridges obstruct direct line-of-sight to central gateways. In contrast, multi-hop mesh forwarding provides spatial diversity without requiring expensive high-gain towers.\n\nRegarding sensor calibration, Pradnya & Wijaya (2023) established that conventional Topp dielectric formulas underestimate moisture in Indonesian volcanic soils due to bound water around allophane nanoparticles. While they proposed lab-based quadratic corrections, on-node embedded computation remained unaddressed. Furthermore, hydrological analyses by Sutrisna & Wardana (2025) underscored that communal water conflict in Subak arises predominantly from information asymmetry rather than total absolute water deficit. Our work bridges these disparate domains by pairing edge-calibrated telemetry directly with low-overhead LoRa mesh protocols.",
            'word_count' => 148,
            'is_drafted' => true,
        ]);

        PaperSection::create([
            'research_project_id' => $project->id,
            'section_type' => 'methodology',
            'title' => '3. System Architecture & Methodology',
            'order' => 4,
            'content' => "The proposed Smart Soil System comprises three interdependent layers: (i) the edge sensor probe assembly, (ii) the multi-hop LoRa mesh network, and (iii) the telemetry analytics engine.\n\nA. Hardware & Probe Calibration\nEach sensor node incorporates an ESP32-S3 microcontroller coupled with a Semtech SX1262 LoRa transceiver operating at 920-923 MHz. Moisture sensing utilizes a dual-prong capacitive probe calibrated using 120 soil core samples subjected to standard oven drying at 105°C for 24 hours (ISO 11465).\n\nB. Adaptive Synchronous Duty-Cycling (ASDC)\nUnlike fixed-interval timers, the ASDC algorithm monitors the rate of change of volumetric water content (dV/dt). During rapid drainage or irrigation flooding, the sampling interval dynamically compresses to 3 minutes; under steady-state conditions, nodes enter deep-sleep (14.2 µA), awakening synchronously every 30 minutes to transmit delta logs.\n\nC. Field Deployment\nTwelve nodes were installed across eight terrace tiers spanning 2.5 hectares at Subak Jatiluwih, Tabanan, Bali (-8.3685, 115.1311), running continuously for 30 days during the vegetative crop stage.",
            'word_count' => 165,
            'is_drafted' => true,
        ]);

        PaperSection::create([
            'research_project_id' => $project->id,
            'section_type' => 'results',
            'title' => '4. Results & Evidence Evaluation',
            'order' => 5,
            'content' => "A. Sensor Measurement Accuracy\nLaboratory calibration across 120 soil cores confirmed that the edge polynomial algorithm achieved a strong correlation (R² = 0.984, p < 0.0001) with a Root Mean Square Error of 1.82% VWC [Evidence #1: Lab Calibration Data]. The Mean Absolute Percentage Error (MAPE) was restricted to 2.15%, compared to 11.40% error when applying standard linear dielectric assumptions.\n\nB. Network Reliability in Terraced Contours\nOver the 30-day monitoring period, 14,400 packets were generated across the 12 nodes. The network achieved an aggregate Packet Delivery Ratio (PDR) of 99.20% [Evidence #2: Telemetry Log]. Re-routing hops through intermediate terrace crests successfully circumvented steep terrace dampening.\n\nC. Power Profiling & Battery Autonomy\nOscilloscope trace evaluations [Evidence #3: Current Draw Log] confirmed deep sleep draw at 14.2 µA and peak transmission draw at 48.6 mA over an 82 ms transmit burst. Under ASDC operation, the average daily consumption was measured at 2.18 mAh, delivering a projected 142 days of operational lifespan on a single 2600 mAh 18650 cell.\n\n[WARNING: Anti-Hallucination Guardrail]\nNote: A preliminary hypothesis regarding total blight disease suppression was excluded from this section as it lacks backing laboratory or field evidence [Missing Evidence Flagged].",
            'word_count' => 198,
            'is_drafted' => true,
        ]);

        PaperSection::create([
            'research_project_id' => $project->id,
            'section_type' => 'discussion',
            'title' => '5. Discussion & Agronomic Implications',
            'order' => 6,
            'content' => "The empirical findings validate that low-power edge computing can reliably overcome the dual challenges of complex terrain attenuation and dielectric non-linearity in tropical agriculture. Compared to earlier agricultural LoRa implementations (Zhao et al., 2024), our adaptive duty-cycling achieves an 87.3% energy reduction while preserving event-driven responsiveness during sudden flash monsoon rains.\n\nQualitative feedback from local Subak leaders [Evidence #5: Farmer Interview] revealed that communal water disputes decreased noticeably when farmers were able to verify moisture saturation levels objectively at communal gathering points. This illustrates how empirical data collection supports traditional participatory water management.\n\nLimitations: The current probe design requires careful manual insertion to prevent air pockets in rocky volcanic substrata. Future revisions will investigate self-burrowing sensor casings.",
            'word_count' => 135,
            'is_drafted' => true,
        ]);

        PaperSection::create([
            'research_project_id' => $project->id,
            'section_type' => 'conclusion',
            'title' => '6. Conclusion',
            'order' => 7,
            'content' => 'This study introduced the Smart Soil System, demonstrating that evidence-grounded edge-IoT architectures can achieve high precision (RMSE = 1.82%) and prolonged operational autonomy (142 days) in mountainous terraced agriculture. By grounding every scientific claim directly in verified sensor telemetry and standard gravimetric baselines, the system eliminates reliance on speculative models. Future investigations will expand the mesh to multi-subak watersheds and incorporate decentralized irrigation actuator valves.',
            'word_count' => 67,
            'is_drafted' => true,
        ]);

        PaperSection::create([
            'research_project_id' => $project->id,
            'section_type' => 'references',
            'title' => 'References',
            'order' => 8,
            'content' => "[1] M. Zhao, F. Sanchez, and K. Al-Hassani, \"Energy-Efficient LoRa-Based Sensor Networks for Precision Irrigation in Agriculture,\" IEEE Internet of Things Journal, vol. 11, no. 4, pp. 5821-5834, Feb. 2024. doi: 10.1109/JIOT.2024.3361280.\n\n[2] I. W. Pradnya and T. Wijaya, \"Soil Moisture Sensing in High-Organic Volcanic Soils: Dielectric Anomalies and Calibration Techniques,\" Geoderma Regional, vol. 32, p. e00612, Sep. 2023. doi: 10.1016/j.geodrs.2023.e00612.\n\n[3] G. Sutrisna and A. Wardana, \"Subak Traditional Water Allocation vs. Modern Precision Scheduling: A Comparative Hydrological Study,\" Agricultural Water Management, vol. 291, p. 108620, Jan. 2025. doi: 10.1016/j.agwat.2025.108620.\n\n[4] ISO 11465:1993, \"Soil quality - Determination of dry matter and water content on a mass basis - Gravimetric method,\" International Organization for Standardization, Geneva, Switzerland.",
            'word_count' => 120,
            'is_drafted' => true,
        ]);

        // 11. Multi-Output Engine & Publication Tracker
        ResearchOutput::create([
            'research_project_id' => $project->id,
            'title' => 'Low-Power Edge-IoT Sensor Mesh for Precision Soil Telemetry in Terraced Tropical Subak',
            'output_type' => 'journal_manuscript',
            'target_venue' => 'IEEE Internet of Things Journal',
            'indexing' => 'Scopus Q1 (IF: 10.6)',
            'status' => 'under_review',
            'submission_date' => now()->subDays(14),
            'doi_or_url' => 'https://manuscriptcentral.com/jiot-2026-subak',
            'notes' => 'Submitted after reviewer internal check. Rebuttal preparation scheduled for Month 3.',
        ]);

        ResearchOutput::create([
            'research_project_id' => $project->id,
            'title' => 'Adaptive Duty-Cycling in LoRa Mesh for Mountainous Paddy Irrigation Monitoring',
            'output_type' => 'conference_paper',
            'target_venue' => 'IEEE International Conference on Cybernetics and Agro-Informatics (ICCAI 2026)',
            'indexing' => 'IEEE Xplore / Scopus',
            'status' => 'accepted',
            'submission_date' => now()->subMonths(2),
            'doi_or_url' => 'https://doi.org/10.1109/ICCAI61234.2026.10456',
            'notes' => 'Oral presentation scheduled in Denpasar, Bali. Camera-ready version submitted.',
        ]);

        ResearchOutput::create([
            'research_project_id' => $project->id,
            'title' => 'Penerapan Sensor Kelembapan Tanah Berbasis IoT untuk Efisiensi Air pada Kelompok Tani Subak Jatiluwih',
            'output_type' => 'community_service',
            'target_venue' => 'Jurnal Pengabdian Kepada Masyarakat (SINTA 2) / Kemendikbud Ristek',
            'indexing' => 'Sinta 2',
            'status' => 'published',
            'submission_date' => now()->subMonths(4),
            'doi_or_url' => 'https://doi.org/10.22146/jpkm.88210',
            'notes' => 'Luaran wajib hibah pengabdian skema terapan kemitraan masyarakat.',
        ]);

        ResearchOutput::create([
            'research_project_id' => $project->id,
            'title' => 'Dataset: Subak Terraced Soil Moisture, Temperature and LoRa Mesh Telemetry (30 Days)',
            'output_type' => 'dataset_repository',
            'target_venue' => 'Zenodo Open Science Repository / CERN',
            'indexing' => 'Zenodo Open Access DOI',
            'status' => 'published',
            'submission_date' => now()->subDays(5),
            'doi_or_url' => 'https://doi.org/10.5281/zenodo.10892341',
            'notes' => 'Open research data dengan lisensi CC-BY 4.0 lengkap dengan skrip pemrosesan Python.',
        ]);

        ResearchOutput::create([
            'research_project_id' => $project->id,
            'title' => 'Technical Blueprint & Firmware Repository: ESP32 LoRa ASDC V2.1',
            'output_type' => 'technical_report',
            'target_venue' => 'Institutional Repository & GitHub Open Source',
            'indexing' => 'Technical Report No. TR-2026-EE04',
            'status' => 'drafting',
            'submission_date' => null,
            'doi_or_url' => 'https://github.com/research-os/smart-soil-firmware',
            'notes' => 'Dokumentasi skematik Eagle CAD dan firmware Arduino C++.',
        ]);

        // 12. Research Tasks & Dynamic Checklist
        $tasks = [
            ['phase' => 'foundation', 'task_name' => 'Rumuskan Problem Statement dan Research Gap spesifik tanah andosol', 'is_completed' => true, 'priority' => 'high'],
            ['phase' => 'foundation', 'task_name' => 'Definisikan 3 Research Questions terukur', 'is_completed' => true, 'priority' => 'high'],
            ['phase' => 'methodology', 'task_name' => 'Susun protokol kalibrasi ISO 11465 oven lab 120 sampel', 'is_completed' => true, 'priority' => 'high'],
            ['phase' => 'methodology', 'task_name' => 'Rancang topologi LoRa mesh 8 teras sawah', 'is_completed' => true, 'priority' => 'medium'],
            ['phase' => 'data', 'task_name' => 'Lakukan pengujian profil konsumsi daya oscilloscope Tektronix', 'is_completed' => true, 'priority' => 'high'],
            ['phase' => 'data', 'task_name' => 'Koleksi 14.400 telemetri data stream 30 hari di lapangan', 'is_completed' => true, 'priority' => 'high'],
            ['phase' => 'analysis', 'task_name' => 'Hubungkan Finding kalibrasi R² = 0.984 ke Evidence #1', 'is_completed' => true, 'priority' => 'high'],
            ['phase' => 'analysis', 'task_name' => 'Verifikasi klaim penghematan baterai dengan ground-truth oscilloscope', 'is_completed' => true, 'priority' => 'high'],
            ['phase' => 'writing', 'task_name' => 'Reviewer Mode: Periksa dan eliminasi klaim tak berdasar (Anti-Hallucination check)', 'is_completed' => true, 'priority' => 'high'],
            ['phase' => 'writing', 'task_name' => 'Finalisasi draft Results & Discussion dengan callout evidence', 'is_completed' => true, 'priority' => 'high'],
            ['phase' => 'publication', 'task_name' => 'Submit manuscript ke IEEE Internet of Things Journal', 'is_completed' => true, 'priority' => 'high'],
            ['phase' => 'publication', 'task_name' => 'Upload dataset open science ke Zenodo', 'is_completed' => true, 'priority' => 'medium'],
            ['phase' => 'publication', 'task_name' => 'Persiapkan slide oral presentation ICCAI 2026', 'is_completed' => false, 'priority' => 'medium'],
        ];

        foreach ($tasks as $idx => $t) {
            ResearchTask::create([
                'research_project_id' => $project->id,
                'phase' => $t['phase'],
                'task_name' => $t['task_name'],
                'is_completed' => $t['is_completed'],
                'priority' => $t['priority'],
                'order' => $idx + 1,
            ]);
        }

        // 13. Project Documents (Uploaded Documents)
        Storage::disk('public')->makeDirectory('documents/'.$project->id);

        $doc1Content = '%PDF-1.4 Proposal Hibah Penelitian & Inovasi Smart Agriculture Subak 2026. Peneliti Utama: Tri Febriansah.';
        Storage::disk('public')->put('documents/'.$project->id.'/proposal_hibah_subak_2026.pdf', $doc1Content);

        ProjectDocument::create([
            'research_project_id' => $project->id,
            'user_id' => $user->id,
            'title' => 'Proposal Hibah Inovasi Subak 2026',
            'document_category' => 'proposal',
            'file_path' => 'documents/'.$project->id.'/proposal_hibah_subak_2026.pdf',
            'file_name' => 'Proposal_Hibah_Inovasi_Subak_2026.pdf',
            'file_size' => 1258291, // 1.2 MB
            'file_extension' => 'pdf',
            'description' => 'Dokumen proposal lengkap skema pendanaan hilirisasi riset sensor mesh pertanian tropis.',
        ]);

        $doc2Content = '%PDF-1.4 Protokol Pengujian Kalibrasi Sensor Tanah ISO 11465 vs Oven Gravimetri.';
        Storage::disk('public')->put('documents/'.$project->id.'/protokol_kalibrasi_iso11465.pdf', $doc2Content);

        ProjectDocument::create([
            'research_project_id' => $project->id,
            'user_id' => $user->id,
            'title' => 'Protokol Uji Kalibrasi ISO 11465',
            'document_category' => 'instrument',
            'file_path' => 'documents/'.$project->id.'/protokol_kalibrasi_iso11465.pdf',
            'file_name' => 'Protokol_Uji_Kalibrasi_ISO11465.pdf',
            'file_size' => 842100, // 822 KB
            'file_extension' => 'pdf',
            'description' => 'Instrumen baku perbandingan akurasi gravimetri oven 105C dengan sensor kapasitif.',
        ]);

        $doc3Content = "timestamp,node_id,moisture,temperature,rssi\n2026-09-01T08:00:00,NODE-1,64.2,26.5,-88\n2026-09-01T08:05:00,NODE-1,64.1,26.6,-87";
        Storage::disk('public')->put('documents/'.$project->id.'/raw_telemetry_dump_sept2026.csv', $doc3Content);

        ProjectDocument::create([
            'research_project_id' => $project->id,
            'user_id' => $user->id,
            'title' => 'Raw Telemetry Dump Log Lapangan',
            'document_category' => 'raw_data',
            'file_path' => 'documents/'.$project->id.'/raw_telemetry_dump_sept2026.csv',
            'file_name' => 'Raw_Telemetry_Dump_Sept2026.csv',
            'file_size' => 2411724, // 2.3 MB
            'file_extension' => 'csv',
            'description' => 'File dump data sensor lapangan mentah 14.400 baris sebelum proses cleansing.',
        ]);
    }
}
