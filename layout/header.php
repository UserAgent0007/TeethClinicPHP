<!-- ДЛЯ ВСІХ ФАЙЛІВ ВИДАЛЯТИ JS REGISTER , LOGIN -->
<!DOCTYPE html>
<html lang="uk">
<!-- сщьутефк -->
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Teeth dent</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="CSS/style.css">
  <link rel="stylesheet" href="CSS/dopstyle.css">
  <link rel="stylesheet" href="CSS/create_item.css">
  <link rel="stylesheet" href="CSS/products.css">
  <!-- <link rel="stylesheet" href="../../CSS/style.css"> -->
</head>

<body>

  <header>

    <section id="top" class="top-nav">
      <div class="logo">
        <img src="imagies/логотип.jpg" alt="teeth dent" widt="40" height="40" />
        <span class="logo_text">Teeth Dent</span>
      </div>
      <input id="menu-toggle" type="checkbox" />
      <label class='menu-button-container' for="menu-toggle">
      <div class='menu-button'></div>
      </label>
      <ul class="menu">
        <li class="hov_sp"><a class="active" href="index.php?action=main">Головна</a></li>
        <li class="hov_sp"><a href="index.php?action=about">Про нас</a></li>
        <li><a href="index.php?action=items">Товари</a></li>
        <!-- <li class="hov_sp"><a href="pages/news.html">Товари</a></li> -->
        
        <!-- <li class="hov_sp"><a href="pages/doctors.html">Наші лікарі</a></li> -->
        <!-- <li class="hov_sp"><a href="pages/products.html">Товари</a></li> -->
        
        <?php if (empty($_SESSION['user_id'])): ?>
            <li class="hov_sp"><a id = "log" href="index.php?action=login#log_f">Логін</a></li>
            <li class="hov_sp"><a id = "reg" href="index.php?action=registration#open">Реєстрація</a></li>
        <?php else: ?>
            <li class="hov_sp"><a href="index.php?action=create_item">Додати товар</a></li>
            <li class="hov_sp"><a id = "reg" href="index.php?action=logout">Вийти</a></li>
        <?php endif; ?>
        <li class="last_item hov_sp"><a id = "login" href="#open-app">Запис до лікаря</a></li>
      </ul>
    </section>

  </header>


  <div id="open-app" class="modal no-scroll">
    <div class="modal-dialog">
      <div class="modal-content">
        <a id = "log_close" href="#" class="closebtn">×</a>
        <div class="align-cont">
          <form class="make_appointment" action="../index.html" class="form_">
            <p><label>Виберіть дату: <input type="date" required="required"></label></p>
            <p><label for="doctors">Виберіть лікаря: </label>
              <input type="text" required="required" id="doctors" placeholder="виберіть лікаря" list="doct">
            </p>
            <datalist id="doct">
              <option value="Максим Олегович Рудик"></option>
              <option value="Сидорчук Михайло Михайлович"></option>
              <option value="Наружко Анна Сергіївна"></option>
            </datalist>
            <p><label for="problems">Виберіть послугу:</label>
              <input type="text" required="required" id="problems" placeholder="виберіть проблему" list="prob">
            </p>
            <datalist id="prob">
              <option value="Імплантація"></option>
              <option value="Лікування"></option>
              <option value="Видалення"></option>
              <option value="Пломбування"></option>
              <option value="Діагностика зубів"></option>
              <option value="Відбілювання"></option>
            </datalist>
            
            <p><label for="problem">Опишіть проблему:</label></p>
            <textarea name="prob" id="problem" placeholder="опишіть проблему" required="required"></textarea>
            <p><input type="submit" value="Записатися"></p>
          </form>
        </div>
      </div>
    </div>
  </div>
