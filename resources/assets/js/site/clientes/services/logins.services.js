(function () {
    'use strict';
    angular.module('app').service('LOGINS', ['API', function (API) {
    	let currentServico = '';
    	let data = {
    		pagination: {
			    page:1,
			    query:'',
			    firstPage:1,
			    lastPage:1,
			    total:0,
			    limit:10
		    },
		    showing:{
			    primeiro: 1,
			    ultimo: 1
		    },
		    pages:[],
    		carregando:false,
		    logins:[],
	    };
	    this.data = data;

	    this.setServico = function(servicoId){
	    	currentServico = servicoId;
		    data.has = false;
	    };

    	function paginate(){
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
	    this.paginate = paginate;

        this.getLogins = function () {
	        data.logins = [];
	        API.get('cliente/servicos/' + currentServico + '/logins?page=' + data.pagination.page + '&limit=' + data.pagination.limit + '&search=' + data.pagination.query).then(
		        function successCallback(response) {
			        data.logins = response.data.data;
			        data.pagination.total = response.data.total;
			        data.pagination.limit = '' + response.data.per_page;
			        data.pagination.page = response.data.current_page;
			        paginate();
			        data.carregando = false;
		        },
		        function errorCallback(response) {
			        data.carregando = false;
			        data.pagination.total = 0;
			        paginate();
		        }
	        );
        };

        this.setLimit = function () {
        	if(!data.carregando){
        		data.carregando = true;
        		data.pagination.page = 1;
		        this.getLogins();
	        }
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
		        this.getLogins();
	        }
        };

        this.search = function () {
	        if (data.carregando) {
		        data.carregando = true;
		        data.pagination.page = 1;
		        this.getLogins();
	        }
        };
    }]);
})();