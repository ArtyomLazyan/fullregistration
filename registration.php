<?php
    require_once "index.php";
    require_once "User.php";
    User::actionRegister();
?>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-4 padding-right">
                    <?php if ($result): ?>
                       <h2>Регистрация пошла успешно пож авторизуйтесь</h2>
                    <?php else: ?>
                        <?php if (isset($errors) && is_array($errors)): ?>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li> <b style="color:red;">*<?php echo $error; ?></b></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <div class="signup-form"><!--sign up form-->
                            <h2>Регистрация на сайте</h2>
                            <form action="#" method="post">
                                <input type="text" name="name" placeholder="Имя" value="<?php echo $name; ?>"/>
                                <br><br>
                                <input type="email" name="email" placeholder="E-mail" value="<?php echo $email; ?>"/>
                                <br><br>
                                <input type="password" name="password" placeholder="Пароль" value="<?php echo $password; ?>"/>
                                <br><br>
                                <input type="submit" name="register" class="btn btn-default" value="Регистрация" />
                            </form>
                        </div><!--/sign up form-->
                    <?php endif; ?>
                    <br/>
                    <br/>
                </div>
            </div>
        </div>
    </section>