$('.show_confirm').click(function(event) {
    var id = $(this).attr("data-id");
    var url = "{{ route('admin_delete_paper') }}";

    event.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            Swal.fire({
                title: 'Please Wait !',
                html: 'data uploading', // add html attribute if you want or remove
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
            });
            $.ajax({
                url: 'paper/delete',
                type: 'DELETE',
                contentType: 'application/json',
                data: JSON.stringify({
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    paper_id: id
                }),
                success: function(result) {
                    if (result) {
                        var data = JSON.parse(result);
                        if (data.code == 200) {
                            Swal.fire({
                                position: 'center',
                                type: 'success',
                                title: data.value,
                                showConfirmButton: false,
                                timer: 2000
                            });
                            $(this).parent().parent().remove();
                        } else {
                            Swal.fire({
                                position: 'center',
                                type: 'warning',
                                title: data.value,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                }.bind(this),
                error: function(e) {
                    Swal.fire({
                        position: 'center',
                        type: 'warning',
                        title: "can not delete, please try again.",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            })
        }
    });
    return;
});
