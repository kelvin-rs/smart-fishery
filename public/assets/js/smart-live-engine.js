/**
 * Smart Fishery Live Engine
 * Real-time instant search, dynamic filtering, seamless pagination,
 * and AJAX CRUD (Create, Update, Delete) without full-page reloads.
 */
(function () {
    'use strict';

    let searchTimer = null;

    // Toast SweetAlert helper
    function showLiveToast(icon, title, message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: icon,
                title: title,
                text: message || '',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                showClass: { popup: 'animate__animated animate__fadeInDown' },
                hideClass: { popup: 'animate__animated animate__fadeOutUp' }
            });
        }
    }

    // Helper to extract element from fetched HTML string
    function parseHTML(htmlString) {
        const parser = new DOMParser();
        return parser.parseFromString(htmlString, 'text/html');
    }

    // Find live container on current page
    function getLiveContainer() {
        return document.getElementById('liveTableContainer') || 
               document.getElementById('liveRightCol') || 
               document.querySelector('.card-custom:has(table)') ||
               document.querySelector('.table-responsive')?.closest('.card-custom');
    }

    // Perform live fetch for search and filters
    function performLiveFetch(form, activeInput) {
        const container = getLiveContainer();
        if (!container) return;

        // Visual loading cue
        container.style.transition = 'opacity 0.2s ease';
        container.style.opacity = '0.55';

        const action = form.action || window.location.pathname;
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);

        // Remove empty values
        for (const [key, val] of Array.from(params.entries())) {
            if (!val || val.trim() === '') {
                params.delete(key);
            }
        }

        const url = `${action}?${params.toString()}`;
        window.history.replaceState(null, '', url);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return response.text();
        })
        .then(html => {
            const doc = parseHTML(html);
            const newContainer = doc.getElementById('liveTableContainer') || 
                                 doc.getElementById('liveRightCol') ||
                                 doc.querySelector('.card-custom:has(table)') ||
                                 doc.querySelector('.table-responsive')?.closest('.card-custom');

            if (newContainer && container) {
                // If container is liveTableContainer and search input is outside, replace innerHTML
                container.innerHTML = newContainer.innerHTML;
            }

            // Sync total badge if exists
            const currentBadge = document.querySelector('[id$="TotalBadge"]');
            const newBadge = doc.querySelector('[id$="TotalBadge"]');
            if (currentBadge && newBadge) {
                currentBadge.textContent = newBadge.textContent;
            }
        })
        .catch(err => {
            console.warn('[LiveEngine Search Error]', err);
        })
        .finally(() => {
            if (container) {
                container.style.opacity = '1';
            }
        });
    }

    // --------------------------------------------------------------------------
    // 1. REAL-TIME INSTANT SEARCH (KEYUP / INPUT DEBOUNCED)
    // --------------------------------------------------------------------------
    document.addEventListener('input', function (e) {
        const input = e.target;
        if (input.matches('input[name="search"], input[name="search_kualitas"], input[name="search_prediksi"], input[type="search"]')) {
            const form = input.closest('form');
            if (!form) return;

            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                performLiveFetch(form, input);
            }, 250);
        }
    });

    // --------------------------------------------------------------------------
    // 2. INSTANT FILTER ON CHANGE (SELECT / DATE PICKER)
    // --------------------------------------------------------------------------
    document.addEventListener('change', function (e) {
        const el = e.target;
        if (el.matches('select[name="jenis_ikan"], select[name="status"], select[name="keadaan"], select[name="id_tambak"], input[name="tanggal"]')) {
            const form = el.closest('form');
            if (form && (form.method.toUpperCase() === 'GET' || !form.method)) {
                e.preventDefault();
                performLiveFetch(form);
            }
        }
    });

    // Prevent default form submit on filter forms to use AJAX
    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (form.method.toUpperCase() === 'GET' && form.querySelector('input[name="search"], select[name="jenis_ikan"], select[name="status"], select[name="id_tambak"], select[name="keadaan"], input[name="tanggal"]')) {
            e.preventDefault();
            performLiveFetch(form);
        }
    });

    // --------------------------------------------------------------------------
    // 3. SEAMLESS PAGINATION CLICKS (AJAX)
    // --------------------------------------------------------------------------
    document.addEventListener('click', function (e) {
        const pageLink = e.target.closest('.pagination a, nav[role="navigation"] a');
        if (pageLink && pageLink.href && !pageLink.href.startsWith('javascript:')) {
            e.preventDefault();
            const url = pageLink.href;
            const container = getLiveContainer();
            if (container) {
                container.style.transition = 'opacity 0.2s ease';
                container.style.opacity = '0.55';
            }

            window.history.pushState(null, '', url);

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(res => res.text())
            .then(html => {
                const doc = parseHTML(html);
                const newContainer = doc.getElementById('liveTableContainer') || 
                                     doc.getElementById('liveRightCol') ||
                                     doc.querySelector('.card-custom:has(table)');
                if (newContainer && container) {
                    container.innerHTML = newContainer.innerHTML;
                    container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            })
            .catch(err => console.warn('[Pagination Error]', err))
            .finally(() => {
                if (container) container.style.opacity = '1';
            });
        }
    });

    // --------------------------------------------------------------------------
    // 4. AJAX POST FORM SUBMISSIONS (INPUT DATA, CEK AIR, PREDIKSI, PANEN, HARGA)
    // --------------------------------------------------------------------------
    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (form.method.toUpperCase() !== 'POST') return;
        if (form.matches('.form-delete-tambak, form[action*="logout"], form[action*="profile"], form[enctype*="multipart"]')) return;

        // Check if form is one of our AJAX targets
        const isLiveForm = form.action.includes('/proses') || 
                           form.action.includes('/tambak') || 
                           form.action.includes('/hasil-panen') || 
                           form.action.includes('/harga-ikan/update');

        if (!isLiveForm) return;

        e.preventDefault();

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Memproses...';
        }

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => {
            return response.text().then(html => ({ status: response.status, ok: response.ok, html: html }));
        })
        .then(res => {
            const doc = parseHTML(res.html);

            // Check if there is an error message inside the response
            const alertError = doc.querySelector('.alert-danger');
            if (!res.ok || alertError) {
                const errMsg = alertError ? alertError.textContent.trim() : 'Gagal memproses data. Periksa inputan Anda.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Perhatian!',
                        text: errMsg,
                        confirmButtonColor: '#ef4444'
                    });
                } else {
                    alert(errMsg);
                }
                return;
            }

            // Close Bootstrap modal if form was in modal
            const modalEl = form.closest('.modal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) modalInstance.hide();
            }

            // Update liveRightCol if exists (for Kualitas Air and Prediksi Panen)
            const curRightCol = document.getElementById('liveRightCol');
            const newRightCol = doc.getElementById('liveRightCol');
            if (curRightCol && newRightCol) {
                curRightCol.innerHTML = newRightCol.innerHTML;
            }

            // Update liveTableContainer if exists
            const curTableContainer = document.getElementById('liveTableContainer');
            const newTableContainer = doc.getElementById('liveTableContainer');
            if (curTableContainer && newTableContainer) {
                curTableContainer.innerHTML = newTableContainer.innerHTML;
            }

            // Update liveHargaContainer (for KUD)
            const curHargaCard = document.getElementById('liveHargaCard');
            const newHargaCard = doc.getElementById('liveHargaCard');
            if (curHargaCard && newHargaCard) {
                curHargaCard.innerHTML = newHargaCard.innerHTML;
            }

            // Check if SweetAlert script is present in the response and execute it
            const scripts = doc.querySelectorAll('script');
            scripts.forEach(script => {
                if (script.textContent.includes('Swal.fire')) {
                    try {
                        // Extract Swal call and run it safely
                        const scriptContent = script.textContent;
                        eval(scriptContent);
                    } catch (err) {
                        // Fallback toast
                        showLiveToast('success', 'Berhasil!', 'Data berhasil disimpan dan diperbarui.');
                    }
                }
            });

            // Reset form input if it's an additive form (not update)
            if (form.action.includes('/tambak') || form.action.includes('/hasil-panen')) {
                form.reset();
            }
        })
        .catch(err => {
            console.error('[LiveEngine Submit Error]', err);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Jaringan',
                    text: 'Tidak dapat terhubung ke server. Silakan coba lagi.',
                    confirmButtonColor: '#ef4444'
                });
            }
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
        });
    });

    // --------------------------------------------------------------------------
    // 5. GLOBAL AJAX DELETE HANDLER (WITHOUT PAGE RELOAD)
    // --------------------------------------------------------------------------
    window.liveDeleteAction = function (form, label) {
        if (!form) return;

        const row = form.closest('tr');

        Swal.fire({
            title: 'Hapus ' + (label || 'Data') + '?',
            text: 'Data yang dihapus tidak dapat dipulihkan kembali.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="bi bi-trash3"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const formData = new FormData(form);
                return fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error(`HTTP ${res.status}`);
                    return res.text();
                })
                .catch(err => {
                    Swal.showValidationMessage(`Gagal menghapus: ${err.message}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                // Smoothly remove row from DOM
                if (row) {
                    row.style.transition = 'all 0.35s ease';
                    row.style.backgroundColor = '#fee2e2';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(25px)';
                    setTimeout(() => {
                        row.remove();
                        // Re-sync container from response HTML
                        const doc = parseHTML(result.value);
                        const curContainer = getLiveContainer();
                        const newContainer = doc.getElementById('liveTableContainer') || doc.getElementById('liveRightCol');
                        if (curContainer && newContainer) {
                            curContainer.innerHTML = newContainer.innerHTML;
                        }
                    }, 350);
                }

                showLiveToast('success', 'Berhasil!', 'Data berhasil dihapus dari sistem.');
            }
        });
    };

    // Override global delete triggers
    window.hapusTambak = function (event, nomor) {
        event.preventDefault();
        const form = event.target.closest('form');
        window.liveDeleteAction(form, nomor);
    };

    window.hapusRiwayat = function (event, label) {
        event.preventDefault();
        const form = event.target.closest('form');
        window.liveDeleteAction(form, label);
    };

    window.hapusPrediksi = function (event, label) {
        event.preventDefault();
        const form = event.target.closest('form');
        window.liveDeleteAction(form, label);
    };

    window.hapusPanen = function (event, label) {
        event.preventDefault();
        const form = event.target.closest('form');
        window.liveDeleteAction(form, label);
    };

    console.log('[+] Smart Fishery Live Engine initialized.');
})();
