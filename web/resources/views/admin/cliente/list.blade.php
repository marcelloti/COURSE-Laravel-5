<html>
  <head>
    <script>
      function excluiRegistro(url){
        let resposta = confirm("Deseja excluir este registro ?");
        if (resposta){
          window.location.href=url;
        }
      }
    </script>
  </head>
  <body>
    <h1>Listar clientes</h1>
    <a href="/admin/client/form-cadastrar">Novo cliente</a>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>E-mail</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        @foreach($clients as $client)
          <tr>
            <td>{{$client->id}}</td>
            <td>{{$client->name}}</td>
            <td>{{$client->email}}</td>
            <td>
              <a href="{{"/admin/client/{$client->id}/form-editar"}}">Editar</a> | 
              <a href="{{"/admin/client/{$client->id}/excluir"}}" onclick="event.preventDefault();excluiRegistro(this.href)">Excluir</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </body>
</html>