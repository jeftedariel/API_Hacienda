# Código legacy (PHP vanilla) — SOLO REFERENCIA

Este directorio contiene el API_Hacienda original (micro-framework propio, PHP vanilla)
tal como existía en la rama `v4.4` antes de la migración a Laravel 12.

**No desplegar este código.** Se conserva únicamente como:

1. **Golden master**: para regenerar fixtures de los tests de paridad
   (`tests/Fixtures/golden/`) que garantizan que la capa de compatibilidad
   `?w=&r=` del proyecto Laravel responde exactamente igual que el original.
2. **Referencia de implementación** durante el port (genXML, firma XAdES,
   facturador, users).
3. **Origen de datos**: `recursos/sql/api_base.sql` y las tablas dinámicas
   `<idUser>_master_*` son la fuente del comando `php artisan legacy:migrate-data`.

Para levantarlo (solo para capturar fixtures):

```bash
cd legacy
docker compose up -d
# API disponible en http://localhost (ver docker-compose.md)
```

Este directorio se eliminará al completar la Fase 6 de la migración
(ver plan en la raíz del proyecto).
