# TutorMatch

Plataforma de tutorías con tres roles: **estudiante**, **tutor** y **administrador**.

- Laravel 12 · PHP 8.2+ · MySQL · Blade + Tailwind (CDN) · Alpine.js
- Login/registro clásico y con Google (Socialite), verificación de correo
- Búsqueda de tutores, reservas, pagos (Culqi y comprobantes), retiros y suscripciones
- Reseñas, notificaciones, reportes PDF, sala de clases con Jitsi
- API JSON mínima en `/api` (Sanctum)

---

## 1. Requisitos

| Componente      | Versión                                                                                              |
| --------------- | ---------------------------------------------------------------------------------------------------- |
| PHP             | 8.2 o superior (extensiones `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`/`imagick` opcional) |
| Composer        | 2.x                                                                                                  |
| MySQL / MariaDB | 8.x / 10.4+                                                                                          |
| Node.js         | 20+ (para el build de assets con Vite)                                                               |
| XAMPP           | Apache + MySQL + PHP (Windows)                                                                       |

---

## 2. Instalación local (XAMPP)

1. **Ubica el proyecto** en `C:\xampp\htdocs\tutormatch` (o `E:\xampp\htdocs\tutormatch`).
2. **Inicia Apache y MySQL** desde el panel de XAMPP.
3. **Crea la base de datos** (phpMyAdmin → _Nueva_):
    ```sql
    CREATE DATABASE tutormatch CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    ```
4. **Dependencias PHP**:
    ```powershell
    composer install
    ```
5. **Variables de entorno**:
    ```powershell
    Copy-Item .env.example .env
    php artisan key:generate
    ```
    Ajusta en `.env` al menos `DB_*` (por defecto `root` sin contraseña en XAMPP) y `APP_URL`
    (`http://localhost/tutormatch` si sirves desde `htdocs`).
6. **Tablas y datos base**:
    ```powershell
    php artisan migrate
    php artisan db:seed --class=ConfiguracionSeeder
    php artisan db:seed --class=MateriaSeeder
    ```
7. **Enlace de almacenamiento** (para fotos y comprobantes públicos):
    ```powershell
    php artisan storage:link
    ```
8. **Assets** (recomendado: `npm run build`; el proyecto puede usar CDN durante desarrollo):
    ```powershell
    npm install
    npm run build
    ```
9. **Permisos** (solo en Linux/macOS; en Windows normalmente no hace falta):
    ```bash
    chmod -R ug+rwx storage bootstrap/cache
    ```
10. **Abre** `http://localhost/tutormatch/public` (o configura un VirtualHost con `DocumentRoot` en `…/tutormatch/public`).

### Configuraciones opcionales

- **Google (Socialite)**: crea credenciales OAuth en Google Cloud Console y define
  `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET` y `GOOGLE_REDIRECT_URI`
  (`${APP_URL}/auth/google/callback`).
- **Culqi**: `CULQI_MODE=simulated` aprueba los pagos sin llamar a la API (útil en desarrollo).
  Para cobros reales usa `CULQI_MODE=live` con tus claves.
- **Correo**: con `MAIL_MAILER=log` los emails se escriben en `storage/logs/laravel.log`.

### Comandos programados en local

Dos recordatorios están agendados en `routes/console.php`:

```powershell
php artisan schedule:work
```

---

## 3. Puesta en producción (paso a paso)

> Ejemplo para un servidor Linux (Ubuntu) con Nginx y PHP-FPM. En un hosting compartido (cPanel)
> los pasos son los mismos; solo cambia dónde se edita el `DocumentRoot`.

### 3.1 Código y dependencias

```bash
cd /var/www
git clone <URL_DEL_REPOSITORIO> tutormatch
cd tutormatch

composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

### 3.2 Configurar `.env` de producción

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tutormatch
DB_USERNAME=<usuario>
DB_PASSWORD=<contraseña>

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_SCHEME=tls
MAIL_USERNAME=<correo@gmail.com>
MAIL_PASSWORD=<contraseña de aplicación de Google>
MAIL_FROM_ADDRESS=<correo@gmail.com>

GOOGLE_CLIENT_ID=<...>
GOOGLE_CLIENT_SECRET=<...>
GOOGLE_REDIRECT_URI=https://tudominio.com/auth/google/callback

CULQI_MODE=live
CULQI_PUBLIC_KEY=<...>
CULQI_SECRET_KEY=<...>

ADMIN_EMAIL=<correo-del-admin>
ADMIN_PASSWORD=<contraseña-fuerte>
```

> `.env` **nunca** se versiona. No lo subas a git ni lo compartas.

### 3.3 Base de datos y datos base

```bash
php artisan migrate --force
php artisan db:seed --class=ConfiguracionSeeder
php artisan db:seed --class=MateriaSeeder
php artisan db:seed --class=AdminSeeder   # opcional: crea el administrador inicial
php artisan storage:link
```

> `--force` es obligatorio fuera del entorno `local`. **Nunca** uses `migrate:fresh` en producción.

### 3.4 Assets

```bash
npm ci
npm run build
```

### 3.5 Cachés de producción

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Si más adelante cambias `.env` o rutas, vuelve a ejecutar esos tres comandos.
Opcional: `php artisan event:cache` si usas eventos.

### 3.6 Permisos

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo find storage bootstrap/cache -type d -exec chmod 775 {} \;
sudo find storage bootstrap/cache -type f -exec chmod 664 {} \;
```

La **única** carpeta que debe ser escribible por el servidor web es `storage/` y `bootstrap/cache/`.

### 3.7 DocumentRoot → `/public`

El código de la aplicación no debe ser accesible directamente. Dos opciones:

**Nginx**

```nginx
server {
    listen 80;
    server_name tudominio.com;
    root /var/www/tutormatch/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
```

**Apache (XAMPP / hosting compartido)**

```apache
<VirtualHost *:80>
    ServerName tudominio.com
    DocumentRoot "C:/xampp/htdocs/tutormatch/public"

    <Directory "C:/xampp/htdocs/tutormatch/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 3.8 HTTPS

Instala el certificado (Let's Encrypt / certbot) y usa `APP_URL=https://…`.
Si el servidor está detrás de un proxy/CDN, configura los proxies de confianza en `bootstrap/app.php`
(`$middleware->trustProxies(at: '*')`) para que las URLs se generen con `https`.

### 3.9 Scheduler (cron)

Los recordatorios deben ejecutarse cada minuto:

```cron
* * * * * cd /var/www/tutormatch && php artisan schedule:run >> /dev/null 2>&1
```

Comandos agendados en `routes/console.php`:

| Comando                      | Qué hace                                                                                              |
| ---------------------------- | ----------------------------------------------------------------------------------------------------- |
| `clases:recordatorios-30min` | Notifica (BD + email) 30 min antes de la clase aceptada                                               |
| `clases:notificar-salas`     | Avisa "sala lista" 5 min antes                                                                        |
| `clases:recordatorios`       | Recordatorio 30 min solo por BD (equivalente a `recordatorios-30min` sin email; **no está agendado**) |

### 3.10 Cola de trabajos (opcional)

Si en producción usas `QUEUE_CONNECTION=database` y envías notificaciones/emails en cola:

```bash
php artisan queue:work --daemon
```

o supervisa un worker con systemd/supervisor.

### 3.11 Notificaciones en tiempo real (WebSocket)

Las notificaciones se guardan en la base de datos como antes y, cuando el broadcasting está habilitado, también se envían en tiempo real por un canal privado del usuario. Para activarlo:

1. Instala/configura Reverb y genera credenciales para tu entorno:

   ```bash
   php artisan reverb:install
   ```

2. En `.env`, configura `BROADCAST_CONNECTION=reverb`, `QUEUE_CONNECTION=database` y las variables `REVERB_*`/`VITE_REVERB_*` generadas. En XAMPP, habilita `extension=curl` en `php.ini` para el PHP que sirve Laravel.
3. Compila los assets y ejecuta estos procesos en terminales separadas:

   ```bash
   npm run build
   php artisan reverb:start
   php artisan queue:work
   ```

En desarrollo, `npm run dev` puede usarse en lugar de `npm run build`. En producción, mantén Reverb y el worker de cola bajo un supervisor y configura HTTPS/WSS en el proxy. Si el broadcasting sigue en `log` o `null`, las notificaciones continúan guardándose normalmente, pero no se emiten por WebSocket.

---

## 4. Checklist rápido de despliegue

```bash
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
npm ci && npm run build
php artisan config:cache && php artisan route:cache && php artisan view:cache
php artisan storage:link
sudo chown -R www-data:www-data storage bootstrap/cache
```

Verifica:

- `APP_DEBUG=false` y `APP_ENV=production`.
- `https` en `APP_URL` y en `GOOGLE_REDIRECT_URI`.
- La carpeta `public/build` existe (si usas Vite en el layout).
- `php artisan about` no muestra errores.
- `http://tudominio.com/up` responde `200`.

---

## 5. API

Requiere Sanctum (`php artisan migrate` crea `personal_access_tokens`).

```bash
# Login
curl -X POST https://tudominio.com/api/login \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{"email":"usuario@dominio.com","password":"secret","device_name":"cli"}'

# Lectura pública
curl -H "Accept: application/json" https://tudominio.com/api/materias
curl -H "Accept: application/json" "https://tudominio.com/api/tutores?precio_max=40"

# Token
curl -H "Accept: application/json" -H "Authorization: Bearer <TOKEN>" https://tudominio.com/api/user
curl -X POST -H "Accept: application/json" -H "Authorization: Bearer <TOKEN>" https://tudominio.com/api/logout
```

---

## 6. Estructura relevante

```
app/Http/Controllers/        Controladores web (por rol en Admin/ y Tutor/)
app/Http/Controllers/Api/    Controladores de la API JSON
app/Models/                  Modelos Eloquent
routes/web.php               Rutas web (incluye admin.php y tutor.php)
routes/api.php               Rutas de la API
resources/views/layouts/     app.blade.php (panel) y guest.blade.php (auth/portada)
database/migrations|seeders  Esquema y datos base
```

---

## 7. Notas de seguridad antes de publicar

- Nunca subir `.env`, `storage/logs`, `vendor/`, `node_modules/` ni `public/build`.
- Mantener `APP_DEBUG=false` en producción (con él activo se filtran rutas y trazas).
- Revisar el botón **"Omitir verificación y continuar al panel"** (marcado como temporal en el
  código): en producción conviene quitarlo junto con su ruta `verification.skip`.
- Rotar `ADMIN_PASSWORD` después del primer inicio de sesión.
