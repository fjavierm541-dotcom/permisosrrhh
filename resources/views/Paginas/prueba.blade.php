<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login UI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>

body {
    margin: 0;
    height: 100vh;
    background: linear-gradient(135deg, #1b3a2b, #0f241a);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: sans-serif;
}

/* CARD */
.login-card {
    width: 340px;
    padding: 40px 30px 30px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    box-shadow: 0 20px 45px rgba(0,0,0,0.4);
    border: 1px solid rgba(255,255,255,0.2);
    text-align: center;
    position: relative;
}

/* ICONO */
.login-avatar {
    width: 75px;
    height: 75px;
    background: #0f241a;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: -37px;
    left: 50%;
    transform: translateX(-50%);
    box-shadow: 0 6px 18px rgba(0,0,0,0.4);
}

.login-avatar i {
    font-size: 30px;
    color: white;
}

/* INPUTS */
.form-control {
    background: rgba(255,255,255,0.85);
    border: none;
    border-radius: 6px;
    font-size: 14px;
}

.form-control:focus {
    box-shadow: none;
}

/* BOTÓN */
.btn-login {
    background: #0f241a;
    color: white;
    width: 100%;
    border-radius: 6px;
    font-weight: 600;
    letter-spacing: 1px;
}

.btn-login:hover {
    background: #09160f;
}

/* OPCIONES */
.login-options {
    font-size: 12px;
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    color: #ddd;
}

.login-options a {
    color: #ddd;
    text-decoration: none;
}

.login-options a:hover {
    color: white;
}

h5 {
    color: white;
}

</style>

</head>

<body>

<div class="login-card">

    <div class="login-avatar">
        <i class="bi bi-person"></i>
    </div>

    <h5 class="mt-4 mb-4">Login</h5>

    <form onsubmit="return false;">
        
        <div class="mb-3">
            <input type="text" class="form-control" placeholder="Email ID">
        </div>

        <div class="mb-2">
            <input type="password" class="form-control" placeholder="Password">
        </div>

        <div class="login-options">
            <label>
                <input type="checkbox"> Remember me
            </label>

            <a href="#">Forgot Password?</a>
        </div>

        <button class="btn btn-login mt-3">
            LOGIN
        </button>

    </form>

</div>

</body>
</html>