<?= flash('error') ?>

Всего денег: <?= $user->cash ?>

<form action="?action=cash" method="POST">
    <label for="count">Сколько вывести</label>
    <input type="number" name="count" id="count">
    <button>Вывести</button>
</form>