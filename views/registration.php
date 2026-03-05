<?php 
  $errors = [];

  if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $login = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_repeat = $_POST['password_repeat'] ?? '';
    
    // Валідація
    if (!preg_match('/^[a-zA-Zа-яА-ЯіІїЇєЄ0-9_-]{4,}$/', $login)) {
        $errors['name'] = "Ім'я має містити >= 4 символи";
    }
    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9]*([.][a-zA-Z0-9]+)*@[a-zA-Z]+([.][a-zA-Z]{2,})+$/', $email)) {
        $errors['email'] = "Введіть коректний email.";
    }
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{7,}$/', $password)) {
        $errors['password'] = "Пароль має містити мінімум 7 символів. Має містити хочаб 1 велику, маленьку літеру та цифру";
    }
    if ($password !== $password_repeat) {
        $errors['password_repeat'] = "Паролі не збігаються.";
    }

    if (empty($errors)) {
        // TODO: Зберегти користувача в БД
        header('Location: index.php?action=registration_successful');
        exit;
    }

    // Зберегти помилки та дані в сесію
    $_SESSION['reg_errors'] = $errors;
    $_SESSION['reg_old'] = ['name' => $login, 'email' => $email];
    header('Location: index.php?action=registration#open');
    exit;
}

// Отримати помилки з сесії
$errors = $_SESSION['reg_errors'] ?? [];
$old = $_SESSION['reg_old'] ?? [];
unset($_SESSION['reg_errors'], $_SESSION['reg_old']);
?>

  <div id="open" class="modal no-scroll">
    <div class="modal-dialog">
      <div class="modal-content">
        <a id = "reg_close" href="#" class="closebtn">×</a> 
          <!-- <div class="name_surname">
            <div class="name">
              <label for="name">Ім'я:</label>
              <p class="nomargin"><input id="name" type="text" placeholder="Ім'я" required="required"></p>
            </div>
            
          </div> -->
          <form method="POST" action="index.php?action=registration">
          <div>
              
              <label for="name">Ім'я:</label>
              <p class="nomargin"><input id="name" name="name" type="text" placeholder="Ім'я" required="required" value= <?php echo htmlspecialchars($old['name'] ?? '') ?>></p>
              <?php if (isset($errors['name'])): ?>
                    <div class="error-msg"><?php echo $errors['name'] ?></div>
              <?php endif; ?>
            
              <label for="passw">Пароль:</label>
              <p class="nomargin"><input id="passw" name="password" type="password" placeholder="пароль" required="required" /></p>
              <?php if (isset($errors['password'])): ?>
                    <div class="error-msg"><?php echo $errors['password'] ?></div>
              <?php endif; ?>
              
              <label for="passw">Повтори пароль:</label>
              <p class="nomargin"><input id="passw_rep" name="password_repeat" type="password" placeholder="пароль" required="required" /></p>
              <?php if (isset($errors['password_repeat'])): ?>
                    <div class="error-msg"><?php echo $errors['password_repeat'] ?></div>
              <?php endif; ?>

              <label for="email_">email:</label>
              <p class="nomargin"> <input id="email_" name="email" type="text" placeholder="адреса" required="required" value= <?php echo htmlspecialchars($old['email'] ?? '') ?>></p>
              
              <?php if (isset($errors['email'])): ?>
                    <div class="error-msg"><?php echo $errors['email'] ?></div>
              <?php endif; ?>

              <fieldset>
                <legend>Стать:</legend>

                <input type="radio" id="male" name="gender" value="male" checked>
                <label for="male">Чоловіча</label>

                <input type="radio" id="female" name="gender" value="female">
                <label for="female">Жіноча</label>
              </fieldset>
          </div>
          

          
          <p><button type="submit" id = "complite_reg">зареєструватися</button> </p>

          </form>
      </div>
    </div>
  </div>
