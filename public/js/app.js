$('body').on('click', '.delete-user', function () {
    Swal.fire({
        title: 'Hapus ' + $(this).data("label") + '?',
        text: "Data yang telah hapus tidak dapat dikembalikan",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "DELETE",
                url: $(this).data("url"),
                data: { "_token": $(this).data('token') },
                success: function (data) {
                    Swal.fire({
                        title: 'Sukses',
                        text: 'Data berhasil dihapus',
                        icon: 'success',
                        timer: 1000,
                        showConfirmButton: false,
                    }).then((result) => {
                        location.href = data.redirect;
                    })
                },
                error: function (e) {
                    if (e.responseJSON.message) {
                        var swal_message = e.responseJSON.message;
                    } else {
                        var swal_message = "Data gagal dihapus";
                    }
                    Swal.fire({
                        title: 'Gagal menghapus data',
                        text: swal_message,
                        icon: 'error',
                        timer: 2000,
                        showConfirmButton: false,
                    });
                }
            });
        }
    })
});
