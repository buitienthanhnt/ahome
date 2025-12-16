<script type="text/javascript">
    $(document).ready(function() {
        $('.onDelete').click(function(e) {
            url = $(this).attr('data-url');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to delete this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "delete",
                        url: url,
                        data: {},
                        dataType: '',
                        success: function(response) {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Your file has been deleted.",
                                    icon: "success",
                                    timer: 1500, // time for hide modal slide.
                                });
                            }
                            $(this).parent().parent().parent().remove();
                            if ($('.onDelete').length === 0) {
                                location.reload();
                            }

                        }.bind(this),
                        error: function(e) {
                            console.log(e);
                        }
                    });
                }
            });
        });
    })
</script>
