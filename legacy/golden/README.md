# Captura de golden masters

Herramientas para capturar el comportamiento HTTP exacto del API legacy,
usado por los tests de paridad de la migración a Laravel 12.

## Contenido

- `capture.php` — script de captura: ejecuta ~50 requests contra el legacy y
  guarda cada par request/respuesta como fixture JSON en
  `<repo>/tests/Fixtures/golden/`.
- `.env` — configuración determinista montada en el contenedor legacy
  (credenciales de BD de prueba y `cryptoKey` fija). **No contiene secretos
  reales.**
- `test-cert.p12` — certificado autofirmado de prueba (PIN `1234`, serial
  decimal 1234567890, cifrado 3DES/SHA1 para que lo lea el OpenSSL 1.1.1 del
  contenedor PHP 7.4). **No es un certificado real de Hacienda.**
- `../docker-compose.override.yml` — monta `.env` dentro del contenedor.

## Cómo regenerar los fixtures

```bash
cd legacy
docker compose up -d --build

# resetear estado para una captura reproducible
docker exec crlibre-mariadb mariadb -utestuser -ptestpassword testdb \
  -e "DELETE FROM sessions; DELETE FROM files; DELETE FROM users;"
docker exec crlibre-app bash -c 'rm -rf /var/www/api/files/[0-9]*'

cd ..
php legacy/golden/capture.php
```

## Semántica del campo `compare` en los fixtures

| valor        | comparación en el test de paridad                                  |
|--------------|--------------------------------------------------------------------|
| `exact`      | status + body byte a byte                                          |
| `json-exact` | status + JSON semánticamente idéntico                              |
| `signature`  | status + shape; el XML firmado se valida estructural/criptográfico |
| `shape`      | status + claves del JSON (valores aleatorios: sessionKey, códigos) |
| `status-only`| solo el código HTTP                                                |

## Peculiaridades del legacy capturadas (¡son contrato!)

- Falta de parámetro requerido ⇒ **HTTP 200** con
  `{"status":"error","resp":"ERROR: Falta el parametro requerido: X"}`.
- `w`/`r` desconocidos ⇒ `"Function not found"` con `status: ok`.
- Sesión inválida en ruta protegida ⇒ **HTTP 403** ("Acceso denegado"),
  no 440 (el 440 sale de otro flujo).
- `firmar` con PIN incorrecto ⇒ HTTP 200 con **body vacío** (fatal silencioso).
- Body JSON inválido ⇒ texto plano `La informacion json enviada contiene errores.`
- Cada login destruye las sesiones previas del usuario.

## Pendiente (Fase 4)

- Fixtures del módulo `facturador` (~50 rutas; requieren registro de empresa
  y tablas dinámicas `<id>_master_*`).
- Fixtures de `geoloc` y `sendMail`.
