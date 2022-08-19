$(document).ready(function(){
    $("#provinsiIndex").on("change", function () {
        provinsiIndex();
    });

    function provinsiIndex() {
        var provinsi = $("#provinsiIndex").val();
        if (provinsi) {
            $.ajax({
                url: "/kabupaten/" + provinsi,
                // dataType: 'json',
                beforeSend: function (request) {
                    $("#kabupatenIndex option").remove();
                    $("#kabupatenIndex").append(
                        '<option value="">Tunggu sebentar ...</option>'
                    );
                },
                success: function (data) {
                    if (data.success) {
                        let option = '<option value="{id}">{name}</option>';

                        $("#kabupatenIndex option").remove();
                        data.kabupaten.forEach(function (item) {
                            $("#kabupatenIndex").append(
                                option
                                    .replace(/{id}/g, item.id)
                                    .replace(/{name}/g, item.name)
                            );
                        });
                    } else {
                        $("#kabupatenIndex").val("").trigger("change");
                    }
                },
            });
        } else {
            $("#kabupatenIndex").val("").trigger("change");
        }
    }

});