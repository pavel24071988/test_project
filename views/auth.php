<?= flash('error') ?>

<form action="?action=auth" method="post">
    <label for="email">Email</label>
    <input type="text" name="email" id="email" required>
    <label for="password">Пароль</label>
    <input type="password" name="password" id="password" required>
    <button>Sign In</button>
</form>