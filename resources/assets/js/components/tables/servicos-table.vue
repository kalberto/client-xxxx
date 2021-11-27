<table>
    <tbody>
    <tr v-for="item in container">
        <td>@{{item.nome}}</td>
        <td>Designador: @{{item.id}}</td>
        <td>Endereço: @{{item.endereco}}</td>
        <td><a href="{{url("/cliente/servicos/@{item.id}")}}">Detalhes</a></td>
    </tr>
    </tbody>
</table>