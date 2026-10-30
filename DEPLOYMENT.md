# Demo Deployment

This project is prepared for a Render Docker web service and a separate PostgreSQL database. The demo database is independent from the local database and starts with only the admin account and five rooms.

## Before Deploying

1. Create a private GitHub repository and push this project. This workspace does not currently have a Git repository initialized.
2. Create a PostgreSQL database with Neon and copy its connection URL. Keep the URL private.
3. Generate an application key locally with `php artisan key:generate --show`. Copy the result directly into Render; do not commit it or share it in chat.
4. Create a Render Blueprint from the GitHub repository and use the included `render.yaml`.
5. In Render's environment settings, provide `APP_KEY`, `APP_URL` using the assigned `https://...onrender.com` address, and `DB_URL` using the Neon PostgreSQL connection URL with SSL enabled.

On container startup, the app runs database migrations and repeat-safe seeders. The default demo login is `admin` / `admin`. The database receives no patient, appointment, invoice, or payment records by default.

## Demo Limitations

The host may sleep when idle, so the first request can take longer. Anyone with the demo link and credentials can edit or delete demo records. Do not enter real patient information or connect this deployment to the local database.