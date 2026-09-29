# Quotes

A small Laravel app for collecting quotes. Each quote has:

- **Quote** — the text itself (required)
- **Citation** — who/where it's from
- **Tags** — comma or Enter to add, with autocomplete from your existing tags
- **Comment** — your own note on it

Magic-link login, instant search across quote, citation, comment and tags, a tag index with per-tag pages, one-click copy (copies `“quote” — citation`), and inline edit/delete on each card. Built off the Bookmarks app.

## Setup

```sh
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm install && npm run build
```

Set `APP_URL` and the `MAIL_*` values in `.env` so login links actually get sent.

## Tests

```sh
php artisan test
```
