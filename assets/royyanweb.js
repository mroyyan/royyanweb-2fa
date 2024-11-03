document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('twofa-form');
    const inputContainer = form.querySelector('.input-container');
    const inputs = form.querySelectorAll('.input-box');

    // Handle paste pada container
    inputContainer.addEventListener('paste', function(e) {
        e.preventDefault();
        
        const pastedText = (e.clipboardData || window.clipboardData)
            .getData('text')
            .trim()
            .replace(/\D/g, '');
        
        if (pastedText.length > 0) {
            // Distribusi angka ke input boxes
            for (let i = 0; i < inputs.length; i++) {
                if (i < pastedText.length) {
                    inputs[i].value = pastedText[i];
                    // Trigger event input untuk konsistensi
                    const event = new Event('input', { bubbles: true });
                    inputs[i].dispatchEvent(event);
                }
            }
            
            // Focus ke input yang sesuai
            if (pastedText.length >= 6) {
                inputs[5].focus();
            } else {
                inputs[Math.min(pastedText.length, 5)].focus();
            }
        }
    });

    // Handle input untuk setiap kotak
    inputs.forEach((input, index) => {
        // Handle perubahan input
        input.addEventListener('input', function(e) {
            // Membersihkan input dari karakter non-numerik
            const cleanedValue = this.value.replace(/\D/g, '');
            
            // Mengambil karakter pertama saja
            this.value = cleanedValue.slice(0, 1);
            
            // Jika ada nilai yang valid
            if (this.value !== '') {
                // Pindah ke input berikutnya jika bukan input terakhir
                if (index < inputs.length - 1) {
                    setTimeout(() => {
                        inputs[index + 1].focus();
                    }, 0);
                }
            }
        });

        // Mencegah input non-numerik
        input.addEventListener('keypress', function(e) {
            if (!/^\d$/.test(e.key)) {
                e.preventDefault();
            }
        });

        // Handle backspace
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace') {
                if (this.value === '') {
                    // Jika input kosong dan bukan input pertama, pindah ke input sebelumnya
                    if (index > 0) {
                        inputs[index - 1].focus();
                        inputs[index - 1].value = '';
                        e.preventDefault();
                    }
                } else {
                    // Jika input ada isinya, kosongkan dulu
                    this.value = '';
                    e.preventDefault();
                }
            } else if (e.key === 'ArrowLeft' && index > 0) {
                inputs[index - 1].focus();
                e.preventDefault();
            } else if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                inputs[index + 1].focus();
                e.preventDefault();
            }
        });

        // Handle paste pada masing-masing input
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedText = (e.clipboardData || window.clipboardData)
                .getData('text')
                .trim()
                .replace(/\D/g, '');
                
            if (pastedText.length > 0) {
                // Distribusi angka ke input boxes
                for (let i = 0; i < inputs.length; i++) {
                    if (i < pastedText.length) {
                        inputs[i].value = pastedText[i];
                        // Trigger event input untuk konsistensi
                        const event = new Event('input', { bubbles: true });
                        inputs[i].dispatchEvent(event);
                    }
                }
            }
        });
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        const code = Array.from(inputs).map(input => input.value).join('');
        if (code.length !== 6) {
            e.preventDefault();
            alert('Silakan masukkan 6 digit kode verifikasi.');
        }
    });
});