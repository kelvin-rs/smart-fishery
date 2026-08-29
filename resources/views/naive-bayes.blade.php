<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prediksi Kualitas Tambak - Naive Bayes</title>
    <!-- Modern Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS (CDN for guaranteed styling) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    colors: {
                        primary: '#3b82f6',
                        dark: '#0f172a',
                        glass: 'rgba(255, 255, 255, 0.05)',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            color: #f8fafc;
            min-height: 100vh;
        }
        .glass-panel {
            background: var(--glass);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .input-field {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }
        .input-field:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
        }
        .btn-predict {
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            background-size: 200% auto;
            transition: 0.5s;
        }
        .btn-predict:hover {
            background-position: right center;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(139, 92, 246, 0.5);
        }
        .loading-spinner {
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top: 3px solid #fff;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in-out forwards;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="flex items-center justify-center p-6">

    <div class="max-w-4xl w-full grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Formulir Input -->
        <div class="glass-panel p-8 rounded-2xl shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
            
            <h2 class="text-3xl font-bold mb-2 bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500">Prediksi Naive Bayes</h2>
            <p class="text-gray-400 text-sm mb-6">Masukkan data kualitas air untuk memprediksi hasil panen atau kondisi tambak.</p>

            <form id="prediksiForm" class="space-y-5 relative z-10">
                <input type="hidden" name="_token" id="csrf" value="{{ csrf_token() }}">
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Kadar pH</label>
                    <input type="text" id="ph" required placeholder="Contoh: 7.2" 
                           class="input-field w-full px-4 py-3 rounded-xl">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Suhu Air (°C)</label>
                    <input type="text" id="suhu" required placeholder="Contoh: 28.5" 
                           class="input-field w-full px-4 py-3 rounded-xl">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Kesehatan / Padat Tebar</label>
                    <input type="text" id="kesehatan" required placeholder="Contoh: Normal" 
                           class="input-field w-full px-4 py-3 rounded-xl">
                </div>

                <button type="submit" id="btnSubmit" 
                        class="btn-predict w-full py-3.5 rounded-xl font-bold text-white shadow-lg flex justify-center items-center gap-2 mt-4">
                    <span>Analisis Sekarang</span>
                </button>
            </form>
        </div>

        <!-- Panel Hasil -->
        <div id="resultPanel" class="glass-panel p-8 rounded-2xl shadow-2xl flex flex-col justify-center items-center text-center hidden">
            <h3 class="text-gray-400 text-lg font-medium mb-2 uppercase tracking-widest">Hasil Keputusan</h3>
            <div id="prediksiTeks" class="text-5xl font-black mb-6 capitalize bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-emerald-600">
                -
            </div>
            
            <div class="w-full space-y-4">
                <div class="bg-black/30 p-4 rounded-xl text-left border border-white/5">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-400">Probabilitas "Normal"</span>
                        <span id="probNormal" class="font-bold text-blue-400">0%</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-2">
                        <div id="barNormal" class="bg-blue-500 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>

                <div class="bg-black/30 p-4 rounded-xl text-left border border-white/5">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-400">Probabilitas "Tidak"</span>
                        <span id="probTidak" class="font-bold text-red-400">0%</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-2">
                        <div id="barTidak" class="bg-red-500 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
            </div>
            
            <p id="pesanSukses" class="mt-6 text-sm text-green-400 font-medium hidden">
                ✓ Data berhasil disimpan ke database (Tabel hasil_naive)
            </p>
        </div>

    </div>

    <script>
        document.getElementById('prediksiForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('btnSubmit');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="loading-spinner"></span> Memproses...';
            btn.disabled = true;

            const payload = {
                ph: document.getElementById('ph').value,
                suhu: document.getElementById('suhu').value,
                kesehatan: document.getElementById('kesehatan').value,
                _token: document.getElementById('csrf').value
            };

            try {
                const response = await fetch('/hitung-prediksi', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (response.ok) {
                    const resultPanel = document.getElementById('resultPanel');
                    resultPanel.classList.remove('hidden');
                    
                    // Trigger reflow for animation
                    resultPanel.classList.remove('fade-in');
                    void resultPanel.offsetWidth;
                    resultPanel.classList.add('fade-in');

                    // Set teks prediksi
                    const teksPrediksi = document.getElementById('prediksiTeks');
                    teksPrediksi.innerText = data.prediksi_akhir;
                    
                    if(data.prediksi_akhir.toLowerCase() === 'normal') {
                        teksPrediksi.className = "text-5xl font-black mb-6 capitalize bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-emerald-600";
                    } else {
                        teksPrediksi.className = "text-5xl font-black mb-6 capitalize bg-clip-text text-transparent bg-gradient-to-r from-red-400 to-orange-500";
                    }

                    // Karena nilai probabilitas sangat kecil (karena Laplace/Naive Bayes perkalian pecahan),
                    // Kita bisa menampilkannya dalam format scientific atau menormalkannya menjadi % relasional.
                    const valNormal = parseFloat(data.hasil_normal);
                    const valTidak = parseFloat(data.hasil_tidak);
                    const total = valNormal + valTidak;
                    
                    let pctNormal = 0; let pctTidak = 0;
                    if(total > 0) {
                        pctNormal = (valNormal / total) * 100;
                        pctTidak = (valTidak / total) * 100;
                    }

                    document.getElementById('probNormal').innerText = pctNormal.toFixed(2) + '%';
                    document.getElementById('barNormal').style.width = pctNormal + '%';
                    
                    document.getElementById('probTidak').innerText = pctTidak.toFixed(2) + '%';
                    document.getElementById('barTidak').style.width = pctTidak + '%';

                    document.getElementById('pesanSukses').classList.remove('hidden');
                } else {
                    alert('Error: ' + (data.pesan || 'Terjadi kesalahan'));
                }
            } catch (err) {
                alert('Gagal terhubung ke server.');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>
