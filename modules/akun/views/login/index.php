<!-- views/login/index.php -->

<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Login';
?>

<div class="login-container">
    <h1><?= Html::encode($this->title) ?></h1>

    <form id="login-form">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>

    <div id="message"></div>
</div>

<?php
$script = <<< JS
$(document).ready(function() {
    $('#login-form').on('submit', function(e) {
        e.preventDefault();
        
        var email = $('#email').val();
        var password = $('#password').val();
        
        $.ajax({
            url: '<?= Url::to(['login/index']) ?>',
            type: 'POST',
            data: {
                email: email,
                password: password
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#message').html('<div class="alert alert-success">' + response.message + '</div>');
                    // Simpan token akses ke localStorage atau sessionStorage
                    localStorage.setItem('access_token', response.data.access_token);
                    // Redirect ke halaman dashboard atau halaman utama
                    setTimeout(function() {
                        window.location.href = '<?= Url::to(['/site/index']) ?>';
                    }, 1500);
                } else {
                    $('#message').html('<div class="alert alert-danger">Login failed</div>');
                }
            },
            error: function(xhr) {
                $('#message').html('<div class="alert alert-danger">' + xhr.responseJSON.message + '</div>');
            }
        });
    });
});
JS;
$this->registerJs($script);
?>