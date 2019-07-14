<?php include_once ("connection.php");?>
<?php include_once ("index-functions.php");?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>test php application</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="container">
        <h1>Пользователи</h1>
	<div class="add-user-form">
            <h2>Добавить нового пользователя</h2>
            <form class="addition-form" method="post">
                <div class="add-user-form__component" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <label for="first-name">Имя</label>
                    <input type="text" id="first-name" class="add-user-form__text-input" name="first-name" value="<?php echo $firstName; ?>" />
                    <span class="add-user-form__component-error">* <?php echo $errFirstName;?></span>
                </div>
                <div class="add-user-form__component">
                    <label for="last-name">Фамилия</label>
                    <input type="text" id="last-name" class="add-user-form__text-input" name="last-name" value="<?php echo $lastName; ?>" />
                    <span class="add-user-form__component-error">* <?php echo $errLastName;?></span>
                </div>
                <div class="add-user-form__component">
                    <label for="age">Возраст</label>
                    <input type="text" id="age" class="add-user-form__text-input" name="age" value="<?php echo $age; ?>" />
                    <span class="add-user-form__component-error">* <?php echo $errAge;?></span>
                </div>
                <div class="add-user-form__component">
                    <label for="city">Город</label>
                    <input type="text" id="city" class="add-user-form__text-input" name="city" value="<?php echo $city; ?>" />
                    <span class="add-user-form__component-error">* <?php echo $errCity;?></span>
                </div>
                <input type="submit" value="Добавить" name="add-user"/>
            </form>
        </div>
		
        <div class="users">
	    <h2>Список пользователей</h2>
            <?php $users = getUsersArray($connection); ?>
            <?php if(count($users) > 0): ?>
                <table class="users__table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Фамилия</th>
                            <th>Возраст</th>
                            <th>Город</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $firstKey => $row): ?>
                            <tr>
                                <?php foreach ($row as $secondKey => $val):?>
                                    <td><?php echo $val; ?></td>
                                <?php endforeach; ?>
                                <td>
                                    <form class="deletion-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                        <input type="hidden" name="user-id" value="<?php echo $row['id']; ?>" />
                                        <input type="submit" name="delete-user" value="Удалить" />
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="users__not-found">Пользователей не найдено...</p>
            <?php endif; ?>
        </div>  
    </div>
</body>
</html>
