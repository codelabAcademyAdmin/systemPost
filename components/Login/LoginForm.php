<div class="login-container">
    <div class="login-header">
        <div class="login-title">Iniciar Sesión</div>
    </div>
    <form id="frm" class="login-form">
        <div class="input-group">
            <input id="email" class="input-field" type="email" name="email" placeholder="Correo" required>
            <div class="password-container">
                <input id="password" class="input-field input-password" type="password" name="password" id="password" placeholder="Contraseña" required>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-password">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                </svg>
            </div>  
        </div>
        <div class="remember-me">
            <input class="checkbox-mark" type="checkbox" id="rememberMe" name="rememberMe">
            <label class="checkbox" for="rememberMe">Mantenerme conectado</label>
        </div>
        <div class="login-button">
            <button id="btn_submit" type="submit" class="button">Iniciar Sesión</button>
            <button id="btn_disabled" disabled class="button">Procesando</button>
        </div>
        <div class="content-forgot-password">
           <div class="forgot-password">¿Olvidaste la contraseña?</div>
        </div>
    </form>
</div>