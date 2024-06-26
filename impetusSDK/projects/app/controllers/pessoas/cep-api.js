/**
 * CEP API
 * https://apicep.com/
 */

getCep = () => {

    estados = {
        "AC":"Acre",
        "AL":"Alagoas",
        "AP":"Amapá",
        "AM":"Amazonas",
        "BA":"Bahia",
        "CE":"Ceará",
        "DF":"Distrito Federal",
        "ES":"Espírito Santo",
        "GO":"Goiás",
        "MA":"Maranhão",
        "MT":"Mato Grosso",
        "MS":"Mato Grosso do Sul",
        "MG":"Minas Gerais",
        "PA":"Pará",
        "PB":"Paraíba",
        "PR":"Paraná",
        "PE":"Pernambuco",
        "PI":"Piauí",
        "RJ":"Rio de Janeiro",
        "RN":"Rio Grande do Norte",
        "RS":"Rio Grande do Sul",
        "RO":"Rondônia",
        "RR":"Roraima",
        "SC":"Santa Catarina",
        "SP":"São Paulo",
        "SE":"Sergipe",
        "TO":"Tocantins",
        }

    cep = $("#form-edit-enderecoCep").val()
    
    axios.get(`https://viacep.com.br/ws/`+cep+`/json/`).then((response) => {
        $("#form-edit-enderecoLogradouro").val(response.data.logradouro)
        $("#form-edit-enderecoBairro").val(response.data.bairro)
        $("#form-edit-enderecoCidade").val(response.data.localidade)
        $("#form-edit-enderecoPais").val("Brasil")
        $("#form-edit-enderecoEstado").val(estados[response.data.uf])
    }).catch((error) => {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Falha ao coletar CEP, preencha os demais campos novamente ou tente novamente'
        })
    });
}