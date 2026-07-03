![https://crlibre.org](https://crlibre.org/wp-content/uploads/2018/03/cropped-CRLibre-Logo_15-1.png)

# API Hacienda — Facturación Electrónica de Costa Rica 

Migración y modernización del proyecto de [API_Hacienda de CRLibre](https://github.com/CRLibre/API_Hacienda) en Laravel, manteniendo **compatibilidad total** con los clientes del API original.

Soporte actual para V4.4


> El código original en `/legacy` se mantiene **de forma permanente** como
> referencia histórica y golden master.

## API REST v1

API moderna bajo `/api/v1`, con **tokens Sanctum** de dos tipos (`master` =
dueño de empresa, `company` = sub-usuario):

- `POST /api/v1/auth/login` y `/api/v1/auth/company/login` emiten el token.
- **Emisión de comprobantes** (recurso principal): `POST /api/v1/documents`
  corre el flujo completo clave→XML v4.4→firma XAdES→token→envío a Hacienda→
  persistencia, reutilizando los mismos servicios que la capa legacy.
  `GET /api/v1/documents/{id}/status` consulta el estado en Hacienda.
- CRUD de empresa, credenciales ATV (cifradas), sucursales, terminales,
  receptores e inventario; catálogos geográficos públicos.

**Swagger/OpenAPI** autogenerado en `/docs/api` (spec en `/docs/api.json`),
**deshabilitable en producción** con `SCRAMBLE_ENABLED=false`. CORS del REST
configurable por `CORS_ALLOWED_ORIGINS`; rate limiting en login y emisión.

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

Este proyecto está licenciado bajo la **Licencia GNU Affero General Public
License v3 (AGPL v3)**.  
**Todos los usuarios y desarrolladores que utilicen, modifiquen o distribuyan
este módulo están obligados a colaborar en su mantenimiento y mejora, conforme a
los términos de la licencia.**

## 🔹 Condiciones principales

- Cualquier modificación o mejora debe ser publicada y compartida con la
  comunidad bajo la misma licencia AGPL v3.
- Si el módulo se utiliza en entornos privados o en servicios web, el código
  fuente debe estar disponible para todos los usuarios que interactúen con él.
- Se espera que todos los beneficiarios del módulo contribuyan con *
  *correcciones, mejoras o documentación** para asegurar su evolución y
  mantenimiento.

💡 _El incumplimiento de estas condiciones podría considerarse una violación de
los términos de la licencia AGPL v3._

