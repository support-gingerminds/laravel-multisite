# API

## Admin (web) routes

Registered under `web` + `gingerminds-core.auth` middleware, prefixed with your `admin_prefix`, named `gingerminds-multisite.`:

| Route | Purpose |
|---|---|
| `{admin_prefix}/sites` (full resource) | Site CRUD |
| `{admin_prefix}/languages` (full resource) | Language CRUD |

Neither controller implements `show()`. Rendered by `SiteController` / `LanguageController` (or your overrides, see [Configuration](./Configuration.md)).

## API Platform resources

Declared via `#[ApiResource]` attributes directly on the models — remember to add the package's `Models` directory to `config/api-platform.php` (see [Installation](./Installation.md)).

### Site

| Method | Path | Description |
|---|---|---|
| `GET` | `/api/sites` | Paginated collection of sites — the **only** operation exposed for `Site` (no single-item `GET`, no writes through this endpoint). |

Fields: `id`, `code`, `url`, `languages` (nested `Language` collection), `default_language`.

### Language

`Language` declares `#[ApiResource(operations: [])]` — it has **no direct HTTP endpoints** of its own. It only ever appears nested inside a `Site`'s `languages`/`default_language` fields.
