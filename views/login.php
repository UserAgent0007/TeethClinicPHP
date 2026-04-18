 <?php
  require_once("db.php");

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === 'login') {

      $login = trim($_POST['login'] ?? '');
      $password = trim($_POST['password'] ?? '');

      if ($login === '' || $password === '') {
          $_SESSION["error"] = "Невірний логін або пароль";
          header('Location: index.php?action=login#log_f');
          exit;
      } else {
          $stmt = mysqli_prepare($link, "SELECT id, login, password, admin FROM users WHERE login = ?");
          mysqli_stmt_bind_param($stmt, "s", $login);
          mysqli_stmt_execute($stmt);

          $result = mysqli_stmt_get_result($stmt);
          $user = mysqli_fetch_assoc($result);
  

          if ($user && password_verify($password, $user['password'])) {

              $_SESSION['user_id'] = $user['id'];
              $_SESSION['login'] = $user['login'];
              $_SESSION['is_admin'] = $user['admin'];

              // Перенаправлення
              header("Location: index.php?action=login_successful");
              exit;

          } else {
              $_SESSION["error"] = "Невірний логін або пароль";
              header('Location: index.php?action=login#log_f');
              exit;
          }
      }
  }
  $error = $_SESSION["error"] ?? null;
  unset($_SESSION["error"]);
?>
 <div id="log_f" class="modal no-scroll">
    <div class="modal-dialog">
      <div class="modal-content">
        <a id = "reg_close" href="#" class="closebtn">×</a>
        
        <form method="POST" action="index.php?action=login">  
          <?php if (!empty($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
          <?php endif; ?>
            <div>
              <label for="name_log">Ім'я:</label>
              <p class="nomargin"><input id="name_log" type="text" placeholder="Ім'я" required="required" name="login" /></p>
              <label for="passw_log">Пароль:</label>
              <p class="nomargin"><input id="passw_log" type="password" placeholder="пароль" required="required" name="password" /></p>
            </div>
          

          
          <p><button type="submit" >Логін</button> </p>

        </form>
        
      </div>
    </div>
  </div>