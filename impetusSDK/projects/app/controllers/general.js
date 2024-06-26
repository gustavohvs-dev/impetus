const file_base64 = {};

/** 
 * Monta as opções de um select com ajax
 */
jQuery.fn.extend({
    createSelectOptions: function ({
        url = null,
        data = {},
        optionDefault = null
    }) {
        var id = `#${$(this)[0].id}`;
        $.ajax({
            url,
            method: "GET",
            headers: {
                "Authorization": "Bearer " + $("#sessionToken").val()
            },
            data,
            dataType: "json",
        }).done(function (response) {
            $(id).empty()
            if (optionDefault) {
                $(id).append(new Option(optionDefault.text, optionDefault.value, true));
            }
            $(response).each(function () {
                $(id).append(new Option($(this)[0].text, $(this)[0].id));
            });
        });
    }
})

/**
 * Converte o arquivo em Base64
 */
jQuery.fn.extend({
    toBase64: function () {
        $(this).change(function () {
            var file = $(this).prop('files')[0]
            var reader = new FileReader()
            reader.onload = function (e) {
                file_base64.file = e.target.result
            }
            reader.readAsDataURL(file)
        })
    }
})

/**
 * Altera o ícone da foto de perfil
 */
/*$(document).ready(function () {
    if ($(userPhoto).val()) {
        $(".img-profile.rounded-circle").attr("src", $("#endPoint").val() + `${$(userPhoto).val()}`)
    }
})*/

/**
 * Gera as notificações do usuário
 */
$(document).ready(function () {
    loadNotifications()
})

loadNotifications = () => {
    axios.get($("#webservicePath").val() + `notificacoes/list`, {
        params: {
            status: "PENDENTE"
        },
        headers: { 
            Authorization : `Bearer ` + $("#sessionToken").val()
        }
    }).then((response) => {
        $("#notifications-count").hide()
        $("#notications-alert-list").empty()

        if(typeof response.data.data !== 'undefined'){

            //Gera contador de notificações
            if (response.data.data.length >= 9) {
                $("#notifications-count").html("9+")
            } else {
                $("#notifications-count").html(response.data.data.length)
            }
            $("#notifications-count").show()

            $(response.data.data).each(function () {
                var notification = `
                <a href="#" class="list-group-item">
                    <div class="row g-0 align-items-center">
                        <div class="col-2">
                            <i class="text-`+$(this)[0].cor+`" data-feather="`+$(this)[0].icone+`"></i>
                        </div>
                        <div class="col-10">
                            <div class="text-dark">`+$(this)[0].titulo+`</div>
                            <div class="text-muted small mt-1">`+$(this)[0].mensagem+`</div>
                            <div class="text-muted small mt-1">`+$(this)[0].createdAt+`</div>
                        </div>
                    </div>
                </a>
                `;

                $("#notications-alert-list").append(notification)
            });

        }else{
            var notification = `
            <a href="#" class="list-group-item">
                <div class="row g-0 align-items-center">
                    <div class="text-muted small mt-1 text-center">Nenhuma notificação pendente</div>
                </div>
            </a>
            `;

            $("#notications-alert-list").append(notification)
        }
        feather.replace();

        /*if (response.data.length > 0) {

            //Montar URL para redirecionamento
            var location_split = location.href.split('/');
            var url = "";
            console.log(location_split)
            for (let index = 0; index < location_split.length - 1; index++) {
                console.log(location_split[index]);
                url += `${location_split[index]}/` 
            }
        } else {
            $("#dropdown-list-alerts").append('<span class="dropdown-item d-flex align-items-center">Não possui nenhuma notificação!</span>')
        }*/
    }).catch((error) => {
        console.error(error);
    });
}

/**
 * Setta notificação como visualizada e redireciona o usuário para a tela da notificação
 */
/*viewNotification = (id) => {
    event.preventDefault()
    axios.put($("#endPoint").val() + `clear-notification`, {
        id: id
    }, {
        auth: {
            username: $("#userId").val(),
            password: $("#sessionToken").val()
        }
    }).then(function (response) {
        if ($(`#view-notification-${id}`).attr("data-categoria") == "noticia") {
            window.open($(`#view-notification-${id}`).attr("href"), '_blank');
        } else {
            window.location.href = $(`#view-notification-${id}`).attr("href")
        }
        loadNotifications()
    }).catch(function (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: error.response.data.error,
        })
    });
}*/