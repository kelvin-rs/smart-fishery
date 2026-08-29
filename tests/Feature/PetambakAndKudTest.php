<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tambak;
use App\Models\Kud;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PetambakAndKudTest extends TestCase
{
    use RefreshDatabase;

    protected User $petambak;
    protected User $kud;
    protected Tambak $tambak;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tambak = Tambak::create([
            'alamat' => 'Sidoarjo',
            'banyak_benih' => 5000,
            'jenis_ikan' => 'Bandeng',
            'nomor' => 1,
        ]);

        $this->petambak = User::create([
            'username' => 'Petambak Test',
            'email' => 'petambak@test.com',
            'id_tambak' => $this->tambak->id,
            'password' => Hash::make('password123'),
            'role' => 'petambak',
        ]);

        $this->kud = User::create([
            'username' => 'KUD Test',
            'email' => 'kud@test.com',
            'password' => Hash::make('password123'),
            'role' => 'kud',
        ]);

        Kud::create(['jenis_ikan' => 'Bandeng', 'harga' => 20000]);
        Kud::create(['jenis_ikan' => 'Vaname', 'harga' => 45000]);
        Kud::create(['jenis_ikan' => 'Windu', 'harga' => 55000]);
    }

    public function test_petambak_can_view_all_pages(): void
    {
        $this->actingAs($this->petambak)->get(route('petambak.dashboard'))->assertStatus(200);
        $this->actingAs($this->petambak)->get(route('petambak.tambak.index'))->assertStatus(200);
        $this->actingAs($this->petambak)->get(route('petambak.kualitas-air.index'))->assertStatus(200);
        $this->actingAs($this->petambak)->get(route('petambak.prediksi.index'))->assertStatus(200);
        $this->actingAs($this->petambak)->get(route('petambak.panen.index'))->assertStatus(200);
        $this->actingAs($this->petambak)->get(route('petambak.dataset.index'))->assertStatus(200);
    }

    public function test_petambak_can_run_naive_bayes_classification(): void
    {
        $response = $this->actingAs($this->petambak)->post(route('petambak.kualitas-air.proses'), [
            'waktu' => 'Pagi',
            'suhu' => 28.0,
            'ph' => 7.7,
            'padat_tebar' => 'Normal',
            'jenis_ikan' => 'Bandeng',
        ]);

        $response->assertRedirect(route('petambak.kualitas-air.index'));
        $response->assertSessionHas('hasil_uji');
    }

    public function test_petambak_can_run_harvest_prediction(): void
    {
        $response = $this->actingAs($this->petambak)->post(route('petambak.prediksi.proses'), [
            'id_tambak' => $this->tambak->id,
            'jenis_ikan' => 'Bandeng',
            'bulan' => 5,
            'keadaan_tambak' => 'Normal',
        ]);

        $response->assertRedirect(route('petambak.prediksi.index'));
        $response->assertSessionHas('hasil_prediksi');
    }

    public function test_kud_can_view_dashboard_and_update_price(): void
    {
        $this->actingAs($this->kud)->get(route('kud.dashboard'))->assertStatus(200);
        $this->actingAs($this->kud)->get(route('kud.harga.index'))->assertStatus(200);
        $this->actingAs($this->kud)->get(route('kud.panen.index'))->assertStatus(200);

        $response = $this->actingAs($this->kud)->post(route('kud.harga.update'), [
            'jenis_ikan' => 'Bandeng',
            'harga' => 22000,
        ]);

        $response->assertRedirect(route('kud.harga.index'));
        $this->assertDatabaseHas('kud', [
            'jenis_ikan' => 'Bandeng',
            'harga' => 22000,
        ]);
    }

    public function test_iot_sensor_endpoint_can_receive_data(): void
    {
        $response = $this->postJson(route('api.sensor.store'), [
            'id_tambak' => $this->tambak->id,
            'ph' => 7.8,
            'suhu' => 29.5,
            'jenis_ikan' => 'Bandeng',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['status', 'data_sensor', 'status_tambak']);
        $this->assertDatabaseHas('ikan', [
            'id_tambak' => $this->tambak->id,
            'ph' => 7.8,
        ]);
    }

    public function test_iot_timbangan_endpoint_can_receive_data(): void
    {
        $response = $this->postJson(route('api.timbangan.store'), [
            'id_tambak' => $this->tambak->id,
            'banyak_panen' => 300,
            'jenis_ikan' => 'Bandeng',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['status', 'data_timbangan', 'total_uang']);
        $this->assertDatabaseHas('timbangan', [
            'id_tambak' => $this->tambak->id,
            'banyak_panen' => 300,
        ]);
    }
}
