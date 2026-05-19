# Deploy this Laravel app to Render

This repository contains a Dockerfile configured to run the Laravel app on Render (or any Docker-capable host).

Quick steps to deploy on Render (recommended):

1. Create an account on https://render.com and connect your GitHub account.
2. In Render dashboard create a new **Web Service** and choose this repository.
3. Select **Docker** as the environment (Render will detect the `Dockerfile`).
4. Set the **Start Command** to empty; the Dockerfile starts PHP-FPM + nginx.
5. Add environment variables in Render (under Service > Environment) matching your `.env` values, for example:
   - `APP_ENV=production`
   - `APP_KEY` (generate locally via `php artisan key:generate --show`)
   - `APP_DEBUG=false`
   - `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (if using managed DB)
6. Click **Create Web Service**. Deploy will run and build the Docker image.

Notes:
- GitHub Pages cannot run this Laravel PHP app — you must use a host that supports PHP/Docker.
- If you want fully automated deploys without Render UI, I can add a GitHub Action that calls Render's API (you'll need to supply an API key).
