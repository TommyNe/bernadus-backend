# Bernadus Backend

Laravel 12 Backend fuer die Vereinsseite mit Navigationsstruktur und kategoriebasierten API-Endpunkten.

## Voraussetzungen

- PHP 8.5+
- Composer
- Eine konfigurierte Datenbank

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

## Entwicklung

API-Routen anzeigen:

```bash
php artisan route:list --path=api --except-vendor
```

Tests ausfuehren:

```bash
php artisan test --compact
```

## Datenmodell

Die Anwendung enthaelt zwei Ebenen:

- `navigation_items` fuer die Menuestruktur
- Eigene Tabellen pro Hauptkategorie:
  - `start_pages`
  - `about_sections`
  - `event_items`
  - `service_materials`
  - `gallery_honors`
  - `participation_options`
  - `contact_entries`

## API-Endpunkte

### Navigation

- `GET /api/navigation`
- `GET /api/navigation/{slug}`

### Kategorie-Inhalte

- `GET /api/category-content`
- `GET /api/category-content/{category}`

Gueltige Kategorien:

- `start`
- `about`
- `events`
- `service-materials`
- `gallery-honors`
- `participation`
- `contact`

## Seeder

Folgende Seeder werden standardmaessig geladen:

- `NavigationItemSeeder`
- `CategoryContentSeeder`

## Hinweise

- Wenn API-Aenderungen im Frontend nicht sichtbar sind, pruefe den laufenden Dev-Server oder fuehre den passenden Build aus.
- In der aktuellen lokalen CLI-Umgebung koennen Tests an fehlenden PDO-Datenbanktreibern scheitern.
