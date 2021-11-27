{{-- @include('site/shared/clientes-busca') --}}
<div class="busca">
    <form>
        <div class="busca">
            <input   type="search" ng-model="q" placeholder="Digite um número de protocolo ou palavra-chave"/>
            <button type="button" ng-click="search(q)"></button>
        </div>
    </form>
</div>