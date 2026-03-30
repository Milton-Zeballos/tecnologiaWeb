# Despliegue rápido en Render.com

Render permite subir el proyecto desde **GitHub** y generar una **URL pública** (tipo `https://ganatelo.onrender.com`). Plan **gratis**: el servicio “se duerme” tras un rato y el primer acceso puede tardar **~30–60 s** (normal en la defensa: abrí la URL antes).

---

## Qué archivos usar

| Archivo | Rol |
|---------|-----|
| `Dockerfile.render` | Build en la nube: instala Composer, dependencias y arranca `php artisan serve` en el puerto que define Render (`PORT`). |
| `render.yaml` | Opcional: despliegue tipo “Blueprint” desde el repo. |

Si en Render dejás el **Dockerfile path** por defecto (`Dockerfile`), la imagen ahora también ejecuta **`composer install`** en el build (antes no, y por eso faltaba `vendor/`). Para el plan free, **`Dockerfile.render`** suele ser más simple: `php artisan serve` y el puerto `PORT` de Render. Cualquiera de los dos es válido si el path en el panel coincide con el archivo que quieres usar.

---

## Pasos (resumen)

1. **Sube el código a GitHub** (repo público o privado con Render conectado).
2. En [render.com](https://render.com): **New → Web Service**.
3. Conectá el repositorio, elegí:
   - **Runtime:** Docker  
   - **Dockerfile path:** `Dockerfile.render`  
   - **Instance type:** Free (si alcanza).
4. **Variables de entorno** (Environment), mínimo:

| Clave | Valor |
|-------|--------|
| `APP_KEY` | Generá en tu PC: `php artisan key:generate --show` y pegá la línea `base64:...` |
| `APP_URL` | `https://TU-SERVICIO.onrender.com` (la da Render al crear el servicio; si cambia el nombre, actualizá esto). |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `SESSION_DRIVER` | `cookie` (recomendado: evita depender de la tabla `sessions` si algo falla en migraciones o en la BD) |
| `APP_KEY` | **Opcional si usás el `Dockerfile` actual:** si no la definís, el contenedor genera una clave al arrancar (cada redeploy invalida sesiones). Si la definís vos en el panel, queda fija entre deploys. |

Si ves **500**, lo habitual era **`APP_KEY` vacía** (Laravel no puede cifrar sesión/CSRF). El `Dockerfile` ahora escribe logs en **stderr**, así que el mensaje real debería verse en **Logs** del servicio en Render. Comprobá también **`DATABASE_URL`** / **`DB_*`** y, con PostgreSQL, **`DB_SSLMODE=require`** si falla la conexión. Para depurar un rato podés usar `APP_DEBUG=true` y luego volver a `false`.

5. **Base de datos**

   **Opción A — PostgreSQL en Render (recomendada en free)**  
   - **New → PostgreSQL**, creá una instancia.  
   - Copiá la **Internal Database URL** (o los datos host/user/pass).  
   - En el Web Service, añadí:
     - `DATABASE_URL` = URL que te da Render (formato `postgresql://...`), **o**
     - `DB_CONNECTION=pgsql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` según el panel.

   Luego, en el servicio web (shell o deploy command), ejecutá migraciones una vez si no usás el `CMD` del Dockerfile que ya las intenta al arrancar.

   **Nota:** Tu proyecto está pensado para **MySQL**; Laravel suele migrar bien a **PostgreSQL** con el driver `pgsql`. Si alguna migración falla, revisá tipos específicos de MySQL.

   **Opción B — Solo MySQL**  
   Si querés seguir con MySQL puro, necesitás una base MySQL accesible desde internet (servicio externo o la misma app en otro VPS). Render **no** incluye MySQL “gratis” como al PostgreSQL; por eso en muchos tutoriales Laravel + Render usan **PostgreSQL**.

6. **Desplegar** → esperá el build. La URL aparece arriba en el dashboard.

7. **Probar** login y subastas; desde el **celular** abrís la misma URL `https://....onrender.com`.

---

## Detalles importantes

- **`PORT`:** Render lo define solo; `Dockerfile.render` usa `php artisan serve` en ese puerto. No hace falta poner `PORT` a mano.
- **`APP_URL`:** Debe coincidir con la URL HTTPS de Render (evita redirects raros y problemas de sesión/CSRF).
- **Sesiones:** En producción podés usar `SESSION_DRIVER=cookie` (como en Docker local) o `database` si la tabla `sessions` existe tras migrar.
- **Archivos subidos (storage):** En plan gratis el disco es **efímero**; al redeploy pueden perderse imágenes. Para una defensa suele alcanzar; en serio conviene **S3** o similar más adelante.
- **Cold start:** El primer hit después del sleep puede tardar; para la presentación dejá la página abierta un minuto antes.

---

## Si el build falla

- Revisá los **logs** del deploy en Render.
- Asegurate de que **`composer.lock`** esté en el repo.
- Que no falte extensión PHP: `Dockerfile.render` incluye `pdo_mysql` y `pdo_pgsql` por si cambiás de motor.

---

## Comparación rápida

| | VPS (Docker Compose) | Render |
|---|----------------------|--------|
| Control total | Sí | Menos (PaaS) |
| HTTPS | Lo configurás vos | Incluido |
| Precio free | Depende del proveedor | Sí, con sleep |
| MySQL “incluido” | Tu contenedor `db` | Mejor PostgreSQL en panel |

Para **algo rápido para la presentación**, Render + PostgreSQL en el mismo panel suele ser el camino más corto.
