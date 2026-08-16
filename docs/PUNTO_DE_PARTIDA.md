# Calculadoravianka — Punto de partida

## Fecha

16 de agosto de 2026.

## Rama

`mejoras-local`.

Commit HEAD auditado: `0776468f2dea039ce760549e4cdada815f56c5bc` (`Agregar URL de afiliacion configurable`).

Al comenzar la auditoría existía `package-lock.json` sin seguimiento. Su presencia fue identificada y autorizada por la responsable del proyecto; se conservó sin eliminarlo ni modificarlo intencionalmente.

## Arquitectura

- Aplicación monolítica Laravel **13.15.0**, instalada desde `laravel/framework ^13.8`.
- PHP requerido: **^8.3**.
- Persistencia mediante Eloquent. `.env.example` usa SQLite por defecto; la base local auditada es `database/database.sqlite`.
- Plantillas Blade renderizadas en servidor.
- Frontend compilable con Vite **8**, Tailwind CSS **4** y `laravel-vite-plugin` **3.1**. Sin embargo, las vistas funcionales pública y administrativas no consumen actualmente el bundle Vite: cargan Bootstrap **5.3.6** desde jsDelivr y usan CSS/JavaScript inline.
- `resources/js/app.js` está vacío y `resources/css/app.css` contiene únicamente la configuración base de Tailwind e Instrument Sans.
- Autenticación administrativa propia mediante una bandera de sesión (`admin_logged_in`) y el middleware `AdminAuthMiddleware`; no utiliza el guard Eloquent ni la tabla `users`.
- Filesystem predeterminado `local`, con discos `local`, `public` y `s3` configurables. No existen cargas de archivos implementadas.
- El simulador se organiza alrededor de `CreditSimulatorController`, el modelo `CreditLevel`, el modelo clave/valor `SiteSetting` y una única vista pública.

## Rutas actuales

| Método | URL | Controlador o acción | Propósito |
|---|---|---|---|
| GET/HEAD | `/` | Cierre en `routes/web.php` | Redirige con HTTP 302 a `/simulador-creditos`. |
| GET/HEAD | `/simulador-creditos` | `CreditSimulatorController` (`__invoke`) | Muestra y procesa por query string el simulador público. |
| GET/HEAD | `/admin/login` | `AdminAuthController@showLogin` | Muestra el acceso administrativo o redirige al dashboard si ya hay sesión. |
| POST | `/admin/login` | `AdminAuthController@login` | Valida credenciales e inicia la sesión administrativa. |
| POST | `/admin/logout` | `AdminAuthController@logout` | Elimina la bandera de sesión y vuelve al login. |
| GET/HEAD | `/admin/dashboard` | `AdminDashboardController@index` | Muestra conteos y accesos del panel. Protegida por `admin.auth`. |
| GET/HEAD | `/admin/credit-levels` | `AdminCreditLevelController@index` | Lista los tipos/niveles de crédito. Protegida. |
| GET/HEAD | `/admin/credit-levels/create` | `AdminCreditLevelController@create` | Formulario de alta de tipo de crédito. Protegida. |
| POST | `/admin/credit-levels` | `AdminCreditLevelController@store` | Crea un tipo de crédito. Protegida. |
| GET/HEAD | `/admin/credit-levels/{creditLevel}/edit` | `AdminCreditLevelController@edit` | Formulario de edición. Protegida. |
| PUT | `/admin/credit-levels/{creditLevel}` | `AdminCreditLevelController@update` | Actualiza un tipo de crédito. Protegida. |
| GET/HEAD | `/admin/settings` | `AdminSettingController@edit` | Muestra la configuración administrable. Protegida. |
| PUT | `/admin/settings` | `AdminSettingController@update` | Actualiza valores de configuración existentes. Protegida. |

Laravel también expone la ruta de salud `/up` desde `bootstrap/app.php`.

## Funcionalidades existentes

### Página pública

- `/` funciona como acceso corto y redirige al simulador.
- La vista es `resources/views/public/simulador-creditos.blade.php`; no usa layout ni partials.
- Hero institucional con el texto fijo “Cooperativa Tierra Bendita”, título/subtítulo administrables y CTA de afiliación.
- Formulario de simulación con tipo de préstamo, monto y plazo progresivo.
- Ayuda contextual con rango, tasa, plazos y aviso especial para vivienda.
- Tres tarjetas informativas fijas para crédito productivo, vehículos y vivienda.
- Tabla de niveles activos.
- Sección fija de afiliación en tres pasos, credencial virtual y tarjeta física de 150 Bs.
- Requisitos generales administrables, advertencia legal y banda CTA final.
- Resultado presentado en un modal Bootstrap.
- Todos los CTA “Solicitar afiliación/información” usan `affiliate_url`; si no está configurada, se muestran deshabilitados.
- Aunque existen las claves `whatsapp_number` y `whatsapp_affiliation_message` en los seeders y el panel, la página pública no genera actualmente enlaces de WhatsApp ni consume esas claves.

### Simulador financiero

`CreditSimulatorController` consulta únicamente registros `CreditLevel` activos, ordenados por `sort_order`, y los indexa por `slug`. De cada nivel obtiene nombre, nivel, afiliaciones, costo de afiliación, montos mínimo/máximo, tasa anual, plazos, uso autorizado, indicador de vivienda y requisito de evaluación.

El origen de tasas, montos, plazos, niveles, afiliaciones y tipos de crédito es la tabla `credit_levels`. Los seeders definen seis tipos iniciales:

1. Crédito Bajo, nivel 1.
2. Crédito General / Consumo, nivel 2.
3. Crédito Productivo / Emprendimiento, nivel 3.
4. Crédito Vehículos, nivel 4.
5. Crédito Vivienda, nivel 5.
6. Crédito Profesores, nivel 6.

La solicitud se procesa mediante GET. La validación comprueba que el slug exista entre los niveles activos, que el monto sea positivo y esté dentro del rango, y que el plazo pertenezca exactamente a `available_terms`. Primero se valida tipo/monto y luego se habilita el selector de plazo.

La cuota usa el sistema francés de cuota constante:

```text
tasa_mensual = tasa_anual / 12 / 100
cuota = monto × (tasa_mensual / (1 - (1 + tasa_mensual)^(-plazo)))
total_pagado = cuota × plazo
interés_total = total_pagado - monto
```

El modal informa tipo, monto, rango, nivel, afiliaciones necesarias, costo de afiliación, tasa anual, plazo, cuota mensual, total aproximado, interés aproximado, uso autorizado y advertencia general o de vivienda. Esta lógica no fue modificada.

### Administración

- Login propio en `/admin/login`.
- Middleware `admin.auth` aplicado al dashboard, créditos y configuración.
- Dashboard con cantidad total de tipos, tipos activos y configuraciones.
- Alta y edición de tipos de crédito; no hay eliminación.
- Edición dinámica de los registros existentes de `SiteSetting`.
- Logout por POST.

El administrador puede crear tipos y modificar nombre, slug solo durante el alta, nivel, afiliaciones, costo de afiliación, montos mínimo/máximo, tasa, plazos, uso autorizado, orden, vivienda, evaluación requerida y estado activo. En configuración puede editar los valores de todas las claves existentes; el panel no crea ni elimina claves.

## Configuración administrable

`SiteSetting` implementa un registro clave/valor con metadatos. La tabla tiene `id`, `key` única, `value` nullable de tipo texto, `type` (por defecto `text`), `group` nullable, `label` nullable y timestamps. El modelo permite asignación masiva de esos cinco campos.

Claves definidas por `SiteSettingSeeder`:

| Clave | Tipo | Grupo | Uso actual |
|---|---|---|---|
| `whatsapp_number` | `text` | `contacto` | Editable y obligatorio al guardar; no consumido por la vista pública. |
| `whatsapp_affiliation_message` | `textarea` | `contacto` | Editable; no consumido por la vista pública. |
| `affiliate_url` | `url` | `enlace_afiliacion` | Alimenta todos los CTA públicos de afiliación/información. |
| `hero_title` | `text` | `textos` | `<title>` y H1 del hero. |
| `hero_subtitle` | `text` | `textos` | Subtítulo del hero. |
| `form_intro` | `textarea` | `textos` | Introducción de la calculadora. |
| `general_warning` | `textarea` | `textos` | Advertencia general y de resultados no vivienda. |
| `housing_warning` | `textarea` | `textos` | Advertencia del resultado de vivienda. |
| `general_requirements` | `textarea` | `requisitos` | Lista pública, separada por saltos de línea. |

Los grupos son `contacto`, `enlace_afiliacion`, `textos` y `requisitos`; los tipos usados son `text`, `textarea` y `url`. `AdminSettingController` lista los registros por grupo/id y actualiza solo claves ya presentes. Valida formalmente `affiliate_url` y exige `whatsapp_number`; los demás valores no tienen reglas específicas.

La arquitectura es adecuada para ampliar textos simples, contacto y metadatos SEO sin crear columnas por cada valor. Sus límites actuales son: todos los valores se almacenan como texto; no hay casts, esquema de claves, caché, traducciones, versionado ni validación basada en tipo; el formulario solo sabe renderizar texto, textarea y URL; no hay soporte de archivos/uploads; cada carga pública consulta todos los registros; y varias claves solo se conocen por strings dispersos. Para logo y banner será necesario definir explícitamente semántica, validación y ciclo de vida de archivos, aunque la referencia o ruta final pueda guardarse como setting.

### Estado real de la base local

La base local auditada no está poblada conforme a los seeders: contiene **0** usuarios, **0** niveles y solo **1** setting (`affiliate_url`, valor nulo). Por tanto, en este entorno el simulador puede abrir, pero no ofrece tipos de préstamo y la mayoría de textos usa fallbacks Blade. No se ejecutó ningún seeder ni migración destructiva.

## Base de datos

Migraciones y tablas detectadas:

| Tabla | Finalidad / campos principales |
|---|---|
| `users` | `id`, nombre, email único, verificación, contraseña, remember token, timestamps. No participa en el login admin actual. |
| `password_reset_tokens` | Token de restablecimiento por email. |
| `sessions` | Sesiones en base de datos; `user_id` nullable sin clave foránea declarada, IP, agente, payload y actividad. |
| `cache`, `cache_locks` | Caché y bloqueos en base de datos. |
| `jobs`, `job_batches`, `failed_jobs` | Cola, lotes y fallos de trabajos. |
| `credit_levels` | Reglas y presentación de cada tipo de crédito. |
| `site_settings` | Configuración clave/valor con metadatos. |
| `migrations` | Control interno de migraciones de Laravel. |

Campos de `credit_levels`: `id`, `slug` único, `name`, `level`, `affiliations`, `affiliation_cost decimal(12,2)`, `min_amount decimal(12,2)`, `max_amount decimal(12,2)` nullable, `annual_rate decimal(5,2)`, `available_terms` JSON nullable, `authorized_use` texto nullable, `is_housing`, `evaluation_required`, `is_active`, `sort_order` y timestamps.

Campos de `site_settings`: `id`, `key` única, `value` texto nullable, `type`, `group` nullable, `label` nullable y timestamps. La migración posterior de afiliación no agrega una columna: inserta `affiliate_url` usando el modelo `SiteSetting`.

No hay relaciones Eloquent declaradas entre `users`, `credit_levels` y `site_settings`; son módulos independientes.

## Diseño actual

- No existe header de navegación convencional: el encabezado es directamente el hero, sin menú ni logo gráfico.
- La marca aparece como texto fijo “Cooperativa Tierra Bendita”. No hay logo local.
- Paleta inline: azul marino `#0B2545`, petróleo `#133B5C`, dorado `#C9A227`, fondo `#F4F6F8`, blanco y texto `#1F2937`.
- Tipografía pública: Arial/Helvetica; el Instrument Sans compilado por Vite no se utiliza en esta vista.
- Hero azul con kicker, H1, subtítulo y CTA. La tarjeta de calculadora se superpone visualmente al hero.
- Formulario responsivo Bootstrap; JavaScript inline actualiza ayuda, rango y plazos y abre el modal al haber resultado.
- Resultado completo en modal, con CTA y advertencia.
- Contenido posterior: tarjetas de crédito, tabla de niveles, proceso de afiliación/tarjeta, requisitos, advertencia y CTA final.
- No existe footer.

Debe conservarse la jerarquía funcional, la progresión del formulario, el detalle del resultado, las advertencias, requisitos, tabla de niveles y CTA conectados a configuración. En una fase visual posterior pueden reemplazarse el hero, la composición, tarjetas, paleta, tipografía, navegación y cierre/footer, manteniendo contratos de datos y reglas del simulador.

## Assets

- No existen `public/images`, `resources/images` ni imágenes almacenadas en `public/storage`/`storage/app/public`.
- No hay logos locales ni iconos propios.
- Las tres fotografías públicas son URLs externas de Unsplash declaradas dentro de la vista.
- Bootstrap CSS y JS llegan desde jsDelivr; no hay integridad SRI declarada.
- `public/favicon.ico` existe, pero mide **0 bytes**, por lo que no es un favicon funcional.
- El build ignorado por Git contiene CSS/JS compilados y fuentes Instrument Sans 400/500/600 en WOFF/WOFF2; ninguna vista activa del simulador/admin incluye `@vite`.
- `resources/views/welcome.blade.php` sí usa Vite, pero ninguna ruta web actual la renderiza; es un remanente del starter de Laravel.
- `resources/js/app.js` genera un archivo JS vacío.

No se identificaron imágenes locales sin uso porque no hay imágenes locales. Como elementos probablemente prescindibles o desconectados, sujeto a confirmar en la siguiente fase, están la vista `welcome`, el bundle Vite/Tailwind no enlazado y sus fuentes compiladas. No se eliminó nada.

## SEO actual

- Existe `<title>` dinámico, tomado de `hero_title`.
- Existe un H1 único en la página y una estructura amplia de H2/H3.
- Las imágenes externas incluyen `alt` descriptivo.
- `public/robots.txt` permite el rastreo general (`Disallow:` vacío).
- No existen meta description, Open Graph, Twitter Cards, canonical, sitemap.xml, Schema.org/JSON-LD ni información de negocio estructurada.
- No hay enlace explícito a favicon en la vista y el archivo convencional `favicon.ico` está vacío.
- No hay configuración independiente para título SEO: el mismo `hero_title` controla `<title>` y H1.
- La redirección `/` → `/simulador-creditos` es temporal (302), aspecto que debe decidirse según la URL canónica futura.

## Tests

Comandos ejecutados:

```text
php artisan optimize:clear
php artisan test
```

La limpieza de configuración, caché, compilados, eventos, rutas y vistas finalizó correctamente.

PHPUnit ejecutó **2 pruebas / 2 aserciones**: **1 aprobada y 1 fallida**. El fallo es exclusivamente `Tests\Feature\ExampleTest::test_the_application_returns_a_successful_response`: espera HTTP 200 en `/`, pero la aplicación está diseñada como `/` → **302** → `/simulador-creditos`. Esto no indica que la aplicación esté rota. Se recomienda actualizar posteriormente el test para afirmar la redirección y agregar cobertura del simulador y panel; no se modificó en esta fase.

La suite actual no cubre reglas financieras, rangos, plazos, configuración, autenticación ni CRUD administrativo.

## Build frontend

`npm run build` finalizó correctamente con Vite **8.2.1**: 3 módulos transformados y archivos emitidos en `public/build`.

Aviso no bloqueante:

```text
[plugin laravel:fonts] Optimized font fallbacks require the optional "fontaine" package.
```

No hubo errores de compilación. El build no modificó archivos rastreados porque `public/build` está ignorado.

## Decisión frontend

La futura landing pública utilizará Tailwind CSS 4 + Vite como stack principal. El panel administrativo existente podrá conservar temporalmente Bootstrap para evitar un refactor simultáneo.

Esta decisión no implica todavía una migración de la vista pública actual: Bootstrap CDN, los estilos inline y la composición visual existente permanecen intactos hasta la fase de landing.

## Riesgos detectados

1. **Acceso administrativo inseguro:** usuario `admin` y contraseña `admin123` están fijos y visibles en el controlador. No se utiliza hashing, usuario persistente, roles ni el sistema Auth de Laravel.
2. **Sesión administrativa básica:** al iniciar no se regenera el identificador de sesión; al cerrar solo se elimina una clave, sin invalidar sesión ni regenerar token. El middleware confía exclusivamente en un booleano.
3. **Sin limitación de intentos:** el login no tiene rate limiting ni bloqueo.
4. **Validaciones financieras administrativas incompletas:** se admite tasa anual 0 aunque la fórmula divide usando esa tasa; la edición permite plazos vacíos; no se exige `max_amount >= min_amount`, unicidad/orden de plazos ni coherencia entre campos. Esto puede producir niveles inutilizables o errores de cálculo si un administrador introduce datos inconsistentes.
5. **Base local incompleta:** no contiene niveles ni los ocho settings restantes definidos por los seeders. La página abre con fallbacks, pero el simulador no puede operar hasta contar con niveles.
6. **Dependencias externas en runtime:** Bootstrap e imágenes dependen de CDNs/Unsplash; una caída, bloqueo o cambio externo afecta presentación y contenido. Bootstrap no usa SRI.
7. **HTML deliberadamente sin escapar en CTA:** la vista usa `{!! !!}` para HTML construido por una función local. Actualmente clases, URL y etiqueta pasan por `e()`, por lo que no se detectó una inyección activa, pero esta protección debe conservarse si se refactoriza.
8. **Migración acoplada al modelo de aplicación:** la migración de `affiliate_url` llama a `SiteSetting`; cambios futuros del modelo podrían dificultar reconstruir bases desde cero.
9. **Configuración débilmente tipada:** agregar archivos o SEO sin un contrato de validación puede introducir rutas inválidas, contenido incoherente o claves huérfanas.
10. **Cobertura de pruebas mínima:** no protege la lógica financiera ni flujos administrativos durante el futuro rediseño.
11. **Configuración de ejemplo no apta para producción:** `.env.example` activa `APP_DEBUG=true` y conserva nombre/locale genéricos. El `.env` real está ignorado y no se inspeccionaron ni expusieron secretos.

No se encontraron uploads actuales. Los formularios POST/PUT incluyen `@csrf`; los valores Blade ordinarios se imprimen escapados. `.env`, SQLite y `public/build` están ignorados por Git.

## Elementos que debemos conservar

- `CreditLevel` y su tabla como fuente única de tasas, rangos, plazos, niveles, afiliaciones y usos.
- Fórmula y flujo progresivo del simulador hasta disponer de pruebas de caracterización y una decisión explícita de negocio.
- Validación servidor del tipo, monto, rango y plazo; el JavaScript debe seguir siendo solo una mejora de experiencia.
- Resultado completo, advertencias diferenciadas y carácter referencial del cálculo.
- Estado activo y orden administrable de niveles.
- Gestión administrativa sin eliminación de niveles.
- `SiteSetting` como base para contenido simple administrable, reforzando tipos y validaciones al ampliarlo.
- Claves actuales, especialmente `affiliate_url`, textos, requisitos y advertencias.
- CTA con estado seguro/deshabilitado cuando falta URL y `rel="noopener noreferrer"` en enlaces externos.
- Separación entre rutas públicas y grupo administrativo protegido.
- CSRF, escape Blade y atributos `alt` existentes.
- El archivo `package-lock.json` identificado, para mantener instalaciones reproducibles una vez incorporado formalmente por la responsable.

## Áreas seguras para ampliar

- Vista pública y composición de una landing institucional, conservando el controlador y sus datos.
- Layout/partials públicos para header, navegación, secciones y footer.
- `SiteSetting` para textos, contacto y SEO escalar, después de definir catálogo, valores por defecto y validaciones.
- Gestión de assets para logo/banner con disco, validación, reemplazo y limpieza explícitos.
- Metadatos SEO, canonical, Open Graph, JSON-LD, sitemap y favicon.
- Conexión real de WhatsApp usando las claves ya sembradas.
- Pruebas de caracterización del simulador y pruebas de autorización/CRUD antes del rediseño.
- Unificación consciente del sistema frontend: decidir Bootstrap o Vite/Tailwind para evitar dos stacks visuales desconectados.

## Próxima fase propuesta

La siguiente fase será **Landing institucional Tierra Bendita**. Antes de implementarla se recomienda:

1. Congelar el comportamiento actual mediante tests de caracterización de cada nivel, límites, plazos y fórmula.
2. Definir contenido, arquitectura de URL y canonical de la landing frente a `/simulador-creditos`.
3. Decidir un único pipeline CSS/JS y si las dependencias se servirán localmente.
4. Diseñar un catálogo explícito de nuevas claves `SiteSetting`, con validaciones y defaults.
5. Definir almacenamiento y administración de logo/banner antes de agregar uploads.
6. Corregir la autenticación administrativa antes de exponer el panel en producción.
7. Alinear la base local con los datos aprobados mediante un procedimiento no destructivo y respaldado.
8. Mantener intactas tasas, niveles, montos, plazos, afiliaciones y fórmula durante el trabajo visual.

Esta auditoría no implementa la landing, banner, logo, colores ni SEO.

## Preparación previa a landing

### Seeders ejecutados

El 16 de agosto de 2026 se ejecutó de forma no destructiva:

```text
php artisan db:seed
```

`DatabaseSeeder` llamó a `CreditLevelSeeder` y `SiteSettingSeeder`. Ambos usan `updateOrCreate`, por lo que resultaron adecuados para la base existente. No se ejecutó `migrate:fresh`, no se borraron tablas y no se creó usuario: el proyecto no contiene un seeder de administrador.

Estado local verificado después del seeding:

- 6 niveles totales y 6 activos: `bajo`, `consumo`, `productivo`, `vehiculos`, `vivienda` y `profesores`.
- 9 settings: `affiliate_url`, `whatsapp_number`, `whatsapp_affiliation_message`, `hero_title`, `hero_subtitle`, `form_intro`, `general_warning`, `housing_warning` y `general_requirements`.
- 0 usuarios, consistente con los seeders y con el mecanismo administrativo actual.

### Tests de caracterización

El test genérico de `/` ahora comprueba la redirección real a `/simulador-creditos`. Se agregaron pruebas para:

- respuesta HTTP 200 del simulador;
- uso exclusivo de niveles activos y respeto de `sort_order`;
- monto, tasa, plazo, cuota mensual, total pagado e intereses de un caso controlado;
- monto bajo mínimo, sobre máximo, tipo inválido y plazo no permitido;
- `affiliate_url` opcional y entrega correcta a la vista cuando existe;
- contrato `SiteSetting::pluck('value', 'key')`;
- redirección del dashboard administrativo sin sesión;
- login con credenciales configuradas, logout, rate limiting y validaciones administrativas críticas.

Resultado actualizado: **18 pruebas, 46 aserciones, 0 fallos**.

### Cambios mínimos de seguridad

- Se eliminaron las credenciales fijas del controlador.
- El login lee `ADMIN_USERNAME` y `ADMIN_PASSWORD` desde `config/admin.php`; `.env.example` solo contiene valores ficticios.
- Un login correcto regenera el identificador de sesión.
- El logout invalida la sesión y regenera el token CSRF.
- El POST de login tiene límite `throttle:5,1`.
- No se creó un sistema nuevo de usuarios ni se cambió el middleware administrativo.

### Validaciones administrativas reforzadas

- `level`, `affiliations` y `sort_order` deben ser enteros dentro de sus mínimos existentes.
- `max_amount` continúa siendo opcional, pero si existe debe ser mayor o igual que `min_amount`.
- Los plazos son obligatorios tanto al crear como al editar y deben ser enteros positivos separados por comas.
- Se mantienen `annual_rate >= 0`, `min_amount >= 0`, `affiliation_cost >= 0` y las demás reglas existentes.

### Riesgos aún pendientes

- El login sigue siendo un mecanismo propio basado en una bandera de sesión; no usa usuarios persistentes, hashes, roles ni recuperación de contraseña.
- El `.env` real de cada entorno debe definir `ADMIN_USERNAME` y una contraseña fuerte en `ADMIN_PASSWORD`; sin ambos valores el login falla de forma segura.
- Una contraseña en variable de entorno se compara como secreto configurado, pero no sustituye un sistema de identidad con hash persistente.
- La tasa administrativa puede ser 0 por regla explícita (`>= 0`), mientras la fórmula actual no tiene un caso especial para tasa cero. No se cambió por tratarse de lógica financiera.
- No se impide repetir plazos ni se valida una política comercial de duración máxima.
- Persisten dependencias externas Bootstrap/Unsplash, configuración débilmente tipada de settings y ausencia de uploads administrados.
- La migración de `affiliate_url` continúa acoplada al modelo `SiteSetting`.
- La cobertura protege el núcleo solicitado, pero aún faltan pruebas exhaustivas de todo el CRUD y de cada nivel sembrado.

### Estado frontend

Tailwind CSS 4 + Vite queda establecido como estándar de la futura landing pública. Bootstrap puede permanecer temporalmente en el panel. El build continúa siendo exitoso; persiste únicamente el aviso no bloqueante sobre la dependencia opcional `fontaine` para fallbacks optimizados de fuentes.
