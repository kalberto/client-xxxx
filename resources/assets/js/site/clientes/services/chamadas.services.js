(function () {
    'use strict';
    angular.module('app').service('CHAMADAS', ['API', function (API) {
    	let currentServico = '';
    	let data = {
    		pagination: {
			    page:1,
			    query:'',
			    firstPage:1,
			    lastPage:1,
			    total:0,
			    limit:0
		    },
		    showing:{
			    primeiro: 1,
			    ultimo: 1
		    },
		    pages:[],
    		carregando:false,
		    carregandoList: false,
		    chamadas:[],
		    has:false,
		    date:'',
		    lista:[],
	    };
	    this.data = data;

	    this.setServico = function(servicoId){
	    	currentServico = servicoId;
		    data.has = false;
	    };

    	function paginateChamadas(){
		    let starPage, endPage;
		    let x = 0;
		    let number_pages = Math.ceil(data.pagination.total / data.pagination.limit);
		    while (data.pages.length > 0) {
			    data.pages.pop();
		    }
		    if (number_pages >= 1) {
			    starPage = 1;
			    endPage = number_pages;
			    for (let i = starPage; i <= endPage; i++) {
				    data.pages[x] = i;
				    x++;
			    }
			    data.pagination.firstPage = starPage;
			    data.pagination.lastPage = endPage;
		    }
		    if (data.pagination.page === 1)
			    data.showing.primeiro = 1;
		    else
			    data.showing.primeiro = (data.pagination.limit * (data.pagination.page - 1)) + 1;
		    data.showing.ultimo = (data.pagination.page === data.pagination.lastPage) ? data.pagination.total : data.pagination.limit * data.pagination.page;
	    }
	    this.paginateChamadas = paginateChamadas;

        this.getChamadas = function () {
	        data.chamadas = [];
	        API.get('cliente/servicos/' + currentServico + '/call-detailed-time?page=' + data.pagination.page + '&limit=' + data.pagination.limit + '&search=' + data.pagination.query).then(
		        function successCallback(response) {
			        data.chamadas = response.data.data;
			        data.has = true;
			        data.pagination.total = response.data.total;
			        data.pagination.limit = '' + response.data.per_page;
			        data.pagination.page = response.data.current_page;
			        paginateChamadas();
			        data.carregando = false;
		        },
		        function errorCallback(response) {
			        data.carregando = false;
			        data.pagination.total = 0;
			        paginateChamadas();
		        }
	        );
        };

        this.setDate = function(date){
        	data.date = date;
	    };

        this.getList = function () {
	        data.lista = [];
	        data.carregandoList = true;
	        API.get('cliente/servicos/' + currentServico + '/calls?intervalo=' + data.date).then(
		        function successCallback(response) {
			        data.lista = response.data.chamadas;
			        data.carregandoList = false;
		        },
		        function errorCallback(response) {
			        data.carregandoList = false;
		        }
	        );
        };

        this.setPage = function (navigate) {
	        if (!data.carregando) {
		        data.carregando = true;
		        if (navigate !== null) {
			        if (navigate === 'ante')
				        data.pagination.page -= 1;
			        else if (navigate === 'prox')
				        data.pagination.page += 1;
		        }
		        this.getChamadas();
	        }
        };

        this.search = function () {
	        if (data.carregando) {
		        data.carregando = true;
		        data.pagination.page = 1;
		        this.getChamadas();
	        }
        };

        this.popUp = function (ver) {
	        if (ver === 1) {
		        $('#popup-chamadas').addClass('ativo');
		        if (!data.has) {
			        data.carregando = true;
			        API.get('cliente/servicos/' + currentServico + '/call-detailed-time').then(
				        function successCallback(response) {
					        data.chamadas = response.data.data;
					        data.has = true;
					        data.carregando = false;
					        data.pagination.total = response.data.total;
					        data.pagination.limit = '' + response.data.per_page;
					        data.pagination.page = response.data.current_page;
					        paginateChamadas();
				        },
				        function errorCallback(response) {
					        data.chamadas = [];
					        data.carregando = false;
				        }
			        );
		        }
	        } else if (ver === 0) {
		        $('#popup-chamadas').removeClass('ativo');
	        }

        };
    }]);
})();