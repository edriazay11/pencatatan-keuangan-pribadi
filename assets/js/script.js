/**
 * JavaScript untuk Sistem Pencatatan Keuangan Pribadi
 */

// Form Validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;

    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = '#f44336';
            isValid = false;
        } else {
            input.style.borderColor = '#ddd';
        }
    });

    // Validasi jumlah (harus positif)
    const jumlahInput = form.querySelector('input[name="jumlah"]');
    if (jumlahInput && jumlahInput.value) {
        const jumlah = parseFloat(jumlahInput.value);
        if (jumlah <= 0) {
            alert('Jumlah harus lebih dari 0');
            jumlahInput.style.borderColor = '#f44336';
            isValid = false;
            return false;
        }
    }

    // Validasi tanggal
    const tanggalInput = form.querySelector('input[name="tanggal"]');
    if (tanggalInput && tanggalInput.value) {
        const tanggal = new Date(tanggalInput.value);
        const hariIni = new Date();
        if (tanggal > hariIni) {
            alert('Tanggal tidak boleh melebihi hari ini');
            tanggalInput.style.borderColor = '#f44336';
            isValid = false;
            return false;
        }
    }

    return isValid;
}

// Konfirmasi Delete
function confirmDelete(message) {
    return confirm(message || 'Apakah Anda yakin ingin menghapus data ini?');
}

// Format Rupiah
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(angka);
}

// Auto format input jumlah
document.addEventListener('DOMContentLoaded', function() {
    const jumlahInputs = document.querySelectorAll('input[name="jumlah"]');
    
    jumlahInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value) {
                const value = parseFloat(this.value);
                if (!isNaN(value)) {
                    this.value = value.toFixed(2);
                }
            }
        });

        // Validasi input hanya angka
        input.addEventListener('keypress', function(e) {
            const char = String.fromCharCode(e.which);
            if (!(/[0-9.]/.test(char))) {
                e.preventDefault();
            }
        });
    });
});

// Filter dan Search
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const categoryFilter = document.getElementById('filter-category');
    const dateFilter = document.getElementById('filter-date');
    
    if (searchInput || categoryFilter || dateFilter) {
        // Trigger search saat input berubah
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                filterData();
            });
        }
        
        if (categoryFilter) {
            categoryFilter.addEventListener('change', function() {
                filterData();
            });
        }
        
        if (dateFilter) {
            dateFilter.addEventListener('change', function() {
                filterData();
            });
        }
    }

    function filterData() {
        const searchValue = searchInput ? searchInput.value.toLowerCase() : '';
        const categoryValue = categoryFilter ? categoryFilter.value : '';
        const dateValue = dateFilter ? dateFilter.value : '';
        
        // Implementasi filter akan dilakukan di PHP atau AJAX
        // Untuk sekarang, ini adalah placeholder
        console.log('Filter:', searchValue, categoryValue, dateValue);
    }
});

// Set default date ke hari ini
document.addEventListener('DOMContentLoaded', function() {
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        if (!input.value) {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            input.value = `${year}-${month}-${day}`;
        }
    });
});

// Smooth Scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
});

