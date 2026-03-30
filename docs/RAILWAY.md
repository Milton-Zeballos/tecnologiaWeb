# Desplegar en Railway

Railway inyecta **`PORT`** y, si agregás PostgreSQL al proyecto, **`DATABASE_URL`**. Este repo incluye **`Dockerfile.railway`** (PHP + `php artisan serve`), alineado con esa forma de desplegar.

## Pasos rápidos

1. **Cuenta y repo**  
   Entrá en [railway.app](https://railway.app), **New Project → Deploy from GitHub** y elegí este repositorio.

2. **Base de datos**  
   En el mismo proyecto: **+ New → Database → PostgreSQL**. Railway crea la variable `DATABASE_URL` (o podés **conectar** la BD al servicio web desde la pestaña **Variables** / “Reference”).

3. **Dockerfile**  
   El repo incluye **`railway.json`** apuntando a `Dockerfile.railway`. Si el panel no lo toma, en el servicio web: **Settings → Build → Dockerfile path** = `Dockerfile.railway`.

4. **Variables de entorno** (servicio web)

   | Variable | Valor |
   |----------|--------|
   | `APP_ENV` | `production` |
   | `APP_DEBUG` | `false` |
   | `APP_URL` | `https://TU-DOMINIO.up.railway.app` (copiá la URL pública que te da Railway en **Settings → Networking → Public URL**) |
   | `SESSION_DRIVER` | `cookie` |
   | `APP_KEY` | Generá en tu PC: `php artisan key:generate --show` y pegá el `base64:...` (opcional: el entrypoint puede generar una si falta, pero conviene fijarla) |

   **`DATABASE_URL`** debería venir de la base PostgreSQL del proyecto (no la pegues a mano si Railway ya la referencia).

5. **SSL con PostgreSQL**  
   Si `migrate` falla por conexión, probá en el servicio web: `DB_SSLMODE=require`.

6. **Desplegar**  
   **Deploy** (o push a la rama conectada). En **Deployments → View logs** deberías ver migraciones y luego `Laravel development server started`.

7. **Probar**  
   Abrí la URL HTTPS pública: registro, login, subastas.

## Notas

- **Scheduler** (`subastas:finalizar` cada minuto): en Railway hace falta un **cron** o un **servicio aparte** que ejecute `php artisan schedule:run` cada minuto; para la demo, al **listar subastas** ya se ejecuta la finalización en código. Podés mencionarlo en la defensa como mejora de producción.
- **Archivos subidos** (`storage/app`): en un PaaS el disco suele ser **efímero**; para una defensa suele alcanzar; en serio conviene S3 u otro almacén.
- Si el build usa otro Dockerfile por error, revisá que el path sea **`Dockerfile.railway`**, no el `Dockerfile` de Apache (ese está pensado para puerto 80 y Docker Compose local).
