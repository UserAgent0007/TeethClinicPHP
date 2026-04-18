<?php
    require_once("db.php");
    
    $is_admin = !empty($_SESSION['is_admin']) && (int)$_SESSION['is_admin'] === 1;

    if ($is_admin) {
        $sql = "SELECT * FROM items ORDER BY date DESC, id DESC";
    } else {
        $sql = "SELECT * FROM items WHERE visible = 1 ORDER BY date DESC, id DESC";
    }

    
    $result = mysqli_query($link, $sql);

    if (!$result) {
        die('Помилка запиту: ' . mysqli_error($link));
    }
?>
<main>
    <h1>Всі товари</h1>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <ul class="list_deals_">
            <?php while($item = mysqli_fetch_assoc($result)): ?>
                <li class="deals_">
                    <b class="desc"><?php echo htmlspecialchars($item["name"]); ?></b><p class="desc"><?php echo htmlspecialchars ($item["description"]); ?></p>
                    <span><?php echo htmlspecialchars( $item["price"]); ?></span>
                    <?php if (isset($_SESSION['is_admin']) && (int)$_SESSION['is_admin'] === 1): ?>
                        <?php if ((int)$item['visible'] === 1 ): ?>
                            <span>Опубліковано</span>
                        <?php else: ?>
                            <span>Неопубліковано</span> 
                        <?php endif; ?>
                        <p><a href="index.php?action=update_item&id=<?php echo $item["id"]; ?>">Редагувати</a> <a href="index.php?action=delete_item&id=<?php echo $item["id"]; ?>">Видалити</a></p>
                    <?php endif; ?>
                    <p><a href="index.php?action=detail_item&id=<?php echo $item["id"]; ?>">Оглянути</a></p>
                </li>
            <?php endwhile; ?>
        <ul>

    <?php else: ?>
        <div class="empty">
            Наразі товари відсутні.
        </div>
    <?php endif; ?>
</main>