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
        $this->actingAs($this->petambak)->get(route('petambak.profile.edit'))->assertStatus(200);
    }

    public function test_petambak_can_create_and_delete_tambak(): void
    {
        $response = $this->actingAs($this->petambak)->post(route('petambak.tambak.store'), [
            'alamat' => 'Tambak Baru Sidoarjo',
            'banyak_benih' => 7500,
            'jenis_ikan' => 'Vaname',
            'nomor' => 99,
        ]);

        $response->assertRedirect(route('petambak.tambak.index'));
        $this->assertDatabaseHas('tambak', [
            'user_id' => $this->petambak->id,
            'nomor' => 99,
            'jenis_ikan' => 'Vaname',
        ]);

        $newTambak = \App\Models\Tambak::where('nomor', 99)->first();

        $deleteResponse = $this->actingAs($this->petambak)->delete(route('petambak.tambak.destroy', $newTambak->id));
        $deleteResponse->assertRedirect(route('petambak.tambak.index'));
        $this->assertDatabaseMissing('tambak', [
            'id' => $newTambak->id,
        ]);
    }

    public function test_petambak_can_update_profile(): void
    {
        $response = $this->actingAs($this->petambak)->put(route('petambak.profile.update'), [
            'username' => 'Petambak Updated',
            'email' => 'updated@test.com',
        ]);

        $response->assertRedirect(route('petambak.profile.edit'));
        $this->assertDatabaseHas('users', [
            'id' => $this->petambak->id,
            'username' => 'Petambak Updated',
            'email' => 'updated@test.com',
        ]);
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

    public function test_kud_can_view_and_update_profile(): void
    {
        $this->actingAs($this->kud)->get(route('kud.profile.edit'))->assertStatus(200);

        $response = $this->actingAs($this->kud)->put(route('kud.profile.update'), [
            'username' => 'KUD Admin Updated',
            'email' => 'kud_updated@test.com',
        ]);

        $response->assertRedirect(route('kud.profile.edit'));
        $this->assertDatabaseHas('users', [
            'id' => $this->kud->id,
            'username' => 'KUD Admin Updated',
            'email' => 'kud_updated@test.com',
        ]);
    }

    public function test_petambak_can_upload_dataset(): void
    {
        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent(
            'dataset.csv',
            "suhu,ph,kesehatan,ket\n28.0,7.5,Normal,Normal\n"
        );

        $response = $this->actingAs($this->petambak)->post(route('petambak.dataset.upload'), [
            'file_dataset' => $file,
            'kategori' => 'kualitas_air',
        ]);

        $response->assertRedirect(route('petambak.dataset.index', ['tab' => 'kualitas_air']));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('data_train', [
            'suhu' => '28.0',
            'ph' => '7.5',
        ]);

        $filePrediksi = \Illuminate\Http\UploadedFile::fake()->createWithContent(
            'prediksi.csv',
            "id_tambak,prediksi\n1,375.50 - 460.00 Kg\n"
        );

        $responsePrediksi = $this->actingAs($this->petambak)->post(route('petambak.dataset.upload'), [
            'file_dataset' => $filePrediksi,
            'kategori' => 'prediksi_panen',
        ]);

        $responsePrediksi->assertRedirect(route('petambak.dataset.index', ['tab' => 'prediksi_panen']));
        $responsePrediksi->assertSessionHas('success');
        $this->assertDatabaseHas('prediksi', [
            'id_tambak' => '1',
            'prediksi' => '375.50 - 460.00 Kg',
        ]);
    }

    public function test_data_integration_endpoint_can_receive_data(): void
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

    public function test_data_timbangan_endpoint_can_receive_data(): void
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
