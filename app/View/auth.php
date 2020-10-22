<?
//echo"<pre>"; print_r($tasks); echo"</pre>";
?>
<nav class="navbar navbar-light bg-primary">
  <a class="navbar-brand text-light" href="/">
       Управление задачами v 1.0
  </a>
</nav>
<div class="container">
  <div class="row">
    <div class="col-lg-6 mt-4 mx-auto">
      <div class="card">
  
        <div class="card-body">
          <h5 class="card-title">Авторизация</h5>
          <form>
              <div><span class="auth-err err text-danger" id="auth-err"></span></div>
              <div class="form-group">
                <label for="login">Логин</label>
                <input type="text" class="form-control" id="login" placeholder="">
              </div>
              <div class="form-group">
                <label for="pass">Пароль</label>
                <input type="password" class="form-control" id="pass" placeholder="">
              </div>
              <button id="auth-user" type="button" class="btn btn-primary">Войти</button>
            </form>
        </div>
    </div>
    </div>
  </div>
</div>