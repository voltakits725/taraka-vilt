import Swal from 'sweetalert2'

export function useCartAlerts() {
    // Helper: baca warna tema dari CSS variables
    const getThemeColors = () => {
        const s = getComputedStyle(document.documentElement)
        return {
            bg:     s.getPropertyValue('--bg-surface').trim(),
            text:   s.getPropertyValue('--text-main').trim(),
            muted:  s.getPropertyValue('--text-muted').trim(),
            accent: s.getPropertyValue('--color-accent').trim(),
            border: s.getPropertyValue('--border-color').trim(),
        }
    }

    const themedPopup = (popup, c) => {
        popup.style.background    = c.bg
        popup.style.color         = c.text
        popup.style.border        = `1px solid ${c.border}`
        popup.style.borderRadius  = '24px'
        popup.style.boxShadow     = `0 16px 48px rgba(0,0,0,0.3), 0 0 0 1px ${c.accent}22`
    }

    const themedToast = (popup, c) => {
        popup.style.background   = c.bg
        popup.style.border       = `1px solid ${c.border}`
        popup.style.borderRadius = '18px'
        popup.style.boxShadow    = `0 8px 30px rgba(0,0,0,0.25), 0 0 0 1px ${c.accent}22`
        popup.style.padding      = '14px 20px'
        popup.style.minWidth     = '280px'
        const bar = popup.querySelector('.swal2-timer-progress-bar')
        if (bar) bar.style.background = c.accent
    }

    const confirmRemoveItem = (item) => {
        const c = getThemeColors()
        return Swal.fire({
            background: c.bg,
            color: c.text,
            html: `
                <div style="display:flex;flex-direction:column;align-items:center;gap:12px;padding:8px 0;">
                    <img src="/images/taraka.png" style="width:48px;height:48px;object-fit:contain;border-radius:12px;" alt="Taraka" />
                    <div style="font-family:'Cormorant Garamond',Georgia,serif;font-weight:800;font-size:22px;color:${c.text};">
                        Hapus pesanan?
                    </div>
                    <div style="font-size:14px;color:${c.muted};font-weight:500;">
                        ${item.name} bakal dihapus dari keranjang.
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'swal-btn-confirm',
                cancelButton: 'swal-btn-cancel',
            },
            buttonsStyling: false,
            didOpen: (popup) => themedPopup(popup, c),
        })
    }

    const showSuccessRemove = (item) => {
        const c = getThemeColors()
        return Swal.fire({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            background: c.bg,
            color: c.text,
            html: `
                <div style="display:flex;align-items:center;gap:14px;padding:2px 0;">
                    <img src="/images/taraka.png" style="width:36px;height:36px;object-fit:contain;border-radius:10px;flex-shrink:0;" alt="Taraka" />
                    <div style="text-align:left;line-height:1.4;">
                        <div style="font-weight:800;font-size:14px;color:${c.text};font-family:'Cormorant Garamond',Georgia,serif;">
                            Berhasil dihapus 🗑️
                        </div>
                        <div style="font-size:12px;color:${c.muted};margin-top:2px;font-weight:500;">
                            ${item.name} sudah dikeluarkan dari keranjang.
                        </div>
                    </div>
                </div>
            `,
            didOpen: (popup) => themedToast(popup, c),
        })
    }

    const showValidationAlert = (selectedTime) => {
        const c = getThemeColors()
        return Swal.fire({
            background: c.bg,
            color: c.text,
            html: `
                <div style="display:flex;flex-direction:column;align-items:center;gap:12px;padding:8px 0;">
                    <img src="/images/taraka.png" style="width:48px;height:48px;object-fit:contain;border-radius:12px;" alt="Taraka" />
                    <div style="font-family:'Cormorant Garamond',Georgia,serif;font-weight:800;font-size:22px;color:${c.text};">
                        Oops...
                    </div>
                    <div style="font-size:14px;color:${c.muted};font-weight:500;">
                        ${!selectedTime ? 'Pilih jam reservasi dulu bro!' : 'Pilih lokasi meja dulu bro!'}
                    </div>
                </div>
            `,
            confirmButtonText: 'Oke',
            customClass: { confirmButton: 'swal-btn-confirm' },
            buttonsStyling: false,
            didOpen: (popup) => themedPopup(popup, c),
        })
    }

    const showCheckoutError = (errors) => {
        const c = getThemeColors()
        return Swal.fire({
            background: c.bg,
            color: c.text,
            html: `
                <div style="display:flex;flex-direction:column;align-items:center;gap:12px;padding:8px 0;">
                    <div style="width:48px;height:48px;border-radius:50%;background:#ef444420;color:#ef4444;display:flex;align-items:center;justify-content:center;font-size:24px;">❌</div>
                    <div style="font-family:'Cormorant Garamond',Georgia,serif;font-weight:800;font-size:22px;color:${c.text};">
                        Gagal Memproses
                    </div>
                    <div style="font-size:14px;color:${c.muted};font-weight:500;text-align:center;">
                        ${errors.checkout || 'Terjadi kesalahan saat memproses pesanan lo. Coba lagi.'}
                    </div>
                </div>
            `,
            confirmButtonText: 'Tutup',
            customClass: { confirmButton: 'swal-btn-cancel' },
            buttonsStyling: false,
            didOpen: (popup) => themedPopup(popup, c),
        })
    }

    return {
        confirmRemoveItem,
        showSuccessRemove,
        showValidationAlert,
        showCheckoutError
    }
}
