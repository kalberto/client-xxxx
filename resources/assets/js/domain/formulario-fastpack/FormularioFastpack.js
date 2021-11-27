import axios from 'axios';
import _ from 'lodash';

export default class FormularioFastpack {

    constructor(route) {
        this._route = route;
        this._empresa = null;
        this._contato = null;
        this._email = null;
        this._telefone = null;
        this._cep = null;
        this._mensagem = null;
        this._endereco = null;
        this._numero = null;
        this._errors = {};
    }

    send() {
        return axios.post(this._route, {
            'empresa': this._empresa,
            'contato': this._contato,
            'email': this._email,
            'cep': this._cep,
            'telefone': this._telefone,
            'mensagem': this._mensagem,
            'numero': this._numero,
            'endereco': this._endereco
        });
    }

    parseForm(el_formulario) {
        this._empresa = el_formulario.form_empresa.value;
        this._contato = el_formulario.form_contato.value;
        this._email = el_formulario.form_email.value;
        this._telefone = el_formulario.form_telefone.value;
        this._cep = el_formulario.form_cep.value;
        this._mensagem = el_formulario.form_mensagem.value;
        this._endereco = el_formulario.form_endereco.value;
        this._numero = el_formulario.form_numero.value;
        return this;
    }
}