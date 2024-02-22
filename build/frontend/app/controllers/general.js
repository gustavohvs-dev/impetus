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
/*$(document).ready(function () {
    loadNotifications()
})

loadNotifications = () => {
    axios.get($("#endPoint").val() + `list-notifications`, {
        params: {
            usuarioId: $("#userId").val()
        },
        auth: {
            username: $("#userId").val(),
            password: $("#sessionToken").val()
        }
    }).then((response) => {
        $("#dropdown-list-alerts").empty()
        $("#alertsDropdown > span").empty()
        if (response.data.length > 0) {

            //Gera contador de notificações
            if (response.data.length > 30) {
                $("#alertsDropdown").append('<span class="badge badge-danger badge-counter">30+</span>')
            } else {
                $("#alertsDropdown").append(`<span class="badge badge-danger badge-counter">${response.data.length}</span>`)
            }

            //Montar URL para redirecionamento
            var location_split = location.href.split('/');
            var url = "";
            console.log(location_split)
            for (let index = 0; index < location_split.length - 1; index++) {
                console.log(location_split[index]);
                url += `${location_split[index]}/` 
            }

            $(response.data).each(function () {

                var component = {
                    icon: "fa-bell",
                    bg: "bg-secondary",
                    href: "#",
                }
                switch ($(this)[0].categoria) {
                    case "comunicado":
                        component.icon = "fa-bullhorn";
                        component.bg = "bg-danger";
                        component.href = `${url}${$(this)[0].link}`;
                        break;
                    case "aniversario":
                        component.icon = "fa-birthday-cake";
                        component.bg = "bg-success";
                        component.href = `${url}${$(this)[0].link}`;
                        break;
                    case "noticia":
                        component.icon = "fa-newspaper";
                        component.bg = "bg-primary";
                        component.href = $(this)[0].link;
                        break;
                }

                //Constrói os elementos de notificação 
 
                var notification = `<a id="view-notification-${$(this)[0].id}" data-categoria="${$(this)[0].categoria}" class="dropdown-item d-flex align-items-center" onclick="viewNotification(${$(this)[0].id})" href="${component.href}">
                    <div class="mr-3">
                        <div class="icon-circle ${component.bg}">
                            <i class="fas ${component.icon} text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">${$(this)[0].titulo}</div>
                        <span class="font-weight-bold">${$(this)[0].texto}</span>
                    </div>
                </a>`;

                $("#dropdown-list-alerts").append(notification)
            })
        } else {
            $("#dropdown-list-alerts").append('<span class="dropdown-item d-flex align-items-center">Não possui nenhuma notificação!</span>')
        }
    }).catch((error) => {
        console.error(error);
    });
}*/

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