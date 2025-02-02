<?php
    if (isset($_COOKIE['token'])) {
        header('Location: ../../HTML/UserProfile/userpage.php');
        exit;
    }

    // Controlla se c'è un errore nella query string
    $errorMessage = '';
    if (isset($_GET['error']) && $_GET['error'] === 'wrong_credentials') {
        $errorMessage = 'Email o password non corretti. Riprova.';
    }
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>BowlScore - Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link href="../../CSS/Access/AccessStyleBase.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="container-fluid d-flex flex-column vh-100 justify-content-center align-items-center">
            <div>
                <div class="d-flex justify-content-center align-items-center">
                    <h1>BowlScore</h1>
                </div>
                
                <div id="MainBlock" class="col-12 flex-column justify-content-center align-items-center mb-3 pb-4">
                    <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                        Login
                    </div>

                    <!-- Mostra il messaggio di errore se presente -->
                    <?php if (!empty($errorMessage)): ?>
                        <div class="alert alert-danger text-center col-9 mt-3 mx-auto" style="max-width: 400px;">
                            <?= htmlspecialchars($errorMessage) ?>
                        </div>
                    <?php endif; ?>

                    <form id="loginForm" action="../../PHP/Process/processLogin.php" method="POST" novalidate>
                        <div class="d-flex justify-content-center">
                            <div class="col-9 mb-2 mt-4 d-flex flex-column justify-content-center">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="email" value="" maxlength="50" placeholder="email" required>
                                <div class="invalid-feedback" style="color:#8B0000 !important;">
                                    Please provide a valid email
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="col-9 mb-3 d-flex flex-column justify-content-center">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password" value="" maxlength="30" placeholder="password" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="col-9 mb-1 d-flex justify-content-center">
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" id="remember" name="remember">
                                    <label for="remember" class="mb-0 ml-2">Remember me</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div id="error-message" class="error-message d-none"></div>
                        </div>
                        <div class="d-flex justify-content-center mt-2">
                            <button class="btn btn-primary" type="submit">Login</button>
                        </div>
                    </form>
                    <div class="d-flex justify-content-center mt-3">
                        <a id="RegisterLink" href="RegistrationPage.php">Don't have an account? Register here</a>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>
