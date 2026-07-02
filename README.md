![https://crlibre.org](https://crlibre.org/wp-content/uploads/2018/03/cropped-CRLibre-Logo_15-1.png)

# API Hacienda — Facturación Electrónica de Costa Rica (Laravel 12)

Migración a Laravel 12 del [API_Hacienda de CRLibre](https://github.com/CRLibre/API_Hacienda)
(facturación electrónica v4.4 del Ministerio de Hacienda de Costa Rica),
manteniendo **compatibilidad total** con los clientes del API original.

## Estado de la migración

| Fase | Alcance | Estado |
|------|---------|--------|
| 0 | Golden masters del API legacy (60 fixtures) | ✅ |
| 1 | Scaffold Laravel 12 + capa de compatibilidad `?w=&r=` | ✅ |
| 2 | Núcleo FE: clave, genXML (7 tipos, byte-idéntico), firma XAdES, QR | ✅ |
| 3 | Integración Hacienda: token, send, consultar, callback | ✅ |
| 4 | BD nueva + migración de datos + facturador + users + correo | 🚧 |
| 5 | REST v1 + Sanctum + endurecimiento de seguridad | ⏳ |
| 6 | Eliminación de `/legacy` | ⏳ |

## Compatibilidad con clientes existentes

Los clientes siguen llamando `POST/GET /api.php?w=<modulo>&r=<accion>` con los
mismos parámetros y reciben exactamente las mismas respuestas (formato
`{status, resp}`, códigos HTTP, y peculiaridades históricas). Esto está
garantizado por una suite de **tests de paridad golden-master**
(`tests/Parity/`) capturada del API original en ejecución
(ver `legacy/golden/README.md`).

Puntos de entrada:

- `public/api.php` — capa de compatibilidad legacy (`routes/legacy.php` →
  `App\Legacy\*`).
- `public/index.php` — aplicación Laravel estándar (REST v1 en Fase 5).

## Desarrollo

```bash
composer install
cp .env.example .env && php artisan key:generate
./vendor/bin/pest              # suite completa (unit + feature + paridad)
php artisan serve              # el API queda en http://localhost:8000/api.php
```

Con Docker: `docker compose up -d` (app en :8000, MariaDB en :3307).

### Tests de paridad contra Hacienda real

Los fixtures que golpean el sandbox del Ministerio corren solo con:

```bash
HACIENDA_LIVE_TESTS=1 ./vendor/bin/pest tests/Parity
```

### Regenerar golden masters

El código original vive en `/legacy` y es ejecutable con Docker; ver
`legacy/golden/README.md`.

## Arquitectura

- `app/Legacy/` — dispatcher de compatibilidad: réplica exacta del contrato
  del framework casero original (params, respuestas, códigos de error).
- `app/Services/` — lógica de dominio compartida por la capa legacy y el
  REST nuevo: `Clave`, `Xml` (generadores v4.4), `Signature` (XAdES-EPES),
  `Hacienda` (token/recepción/estado), `Qr`, `Xsd`, `Files`.
- `packages/crlibre/xades-xmlseclibs` — fork de xmlseclibs con XAdES-EPES
  como paquete composer interno.
- `config/hacienda.php` — endpoints del Ministerio, política de firma, SSL.
- `legacy/` — código PHP vanilla original (solo referencia; se elimina al
  final de la migración).

## Documentación sobre la Factura Electrónica en Costa Rica

- [Información general del proceso](https://github.com/CRLibre/fe-hacienda-cr-docs)
- [Comunidad CRLibre](https://crlibre.org) · [Telegram @CRLibreFE](https://crlibre.org/chats/)

## Licencia

AGPL-3.0, igual que el proyecto original de CRLibre.
