<html>
  <head></head>
  <body>
    <h1>Criar novo cliente</h1>
      <form method="POST" action="/admin/client/cadastrar">
          {!! csrf_field() !!}

          <label for="name">Nome</label>
          <input type="text" name="name" id="name" />

          <label for="name">Email</label>
          <input type="text" name="email" id="email" />

          <button type='submit'>Enviar</button>
      </form>
  </body>
</html>